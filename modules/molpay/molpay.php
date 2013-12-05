<?php
/**
 * MOLPay Prestashop Plugin
 * 
 * @package Payment Method
 * @author MOLPay Technical Team <technical@molpay.com>
 * @version 1.0.0
 * 
 */

class MOLPay extends PaymentModule {	

    /**
     * Prestashop HTML code
     *  
     * @var string
     */
    private $_html = '';
    
    /**
     * Prestashop error
     * 
     * @var array
     */
    private $_postErrors = array();
    
    /**
     * MOLPay API URI
     * 
     * @var string
     */
    private $molpay_url;

    /**
     * Construct molpay object
     * 
     */
    public function __construct() {        
        parent::__construct();
        $this->molpay_url = 'https://www.onlinepayment.com.my/MOLPay/pay/';
        $this->name = 'molpay';
        $this->tab = 'payments_gateways';
        $this->version = '1.2';
        $this->displayName = 'MOLPay Malaysia Online Payment Gateway';
        $this->description = $this->l('Accepts payment by <a href=http://www.molpay.com target=_blank>MOLPay Sdn Bhd</a>');
        $this->confirmUninstall = $this->l('Are you sure you want to delete your MOLPay Payment Module details ?');
    }

    /**
     * Install the module using Prestashop installer
     * 
     * @return boolean
     */
    public function install() {        
        if (!parent::install() || !Configuration::updateValue('MOLPAY_MERCHANT') || !Configuration::updateValue('MOLPAY_VKEY') || !$this->registerHook('payment'))
            return false;
        else
            return true;		
    }

    /**
     * Uninstall the module using Prestashop Configuration Class
     * 
     * @return mixed
     */
    public function uninstall() {        
        Configuration::deleteByName('MOLPAY_MERCHANT');
        Configuration::deleteByName('MOLPAY_VKEY');
        return parent::uninstall();
    }

    /**
     * Display backend view (after validation) for merchant to register MOLPay configuration
     * 
     * @return string
     */
    public function getContent() {
        $this->_html = '<h2>'.$this->displayName.'</h2>';
        if (!empty($_POST)) {
            $this->_postValidation();
            if (!sizeof($this->_postErrors))
                $this->_postProcess();
            else {
                foreach ($this->_postErrors AS $err) {
                    $errs.= '<p>- '.$err.'</p>';                    
                }            
                $this->_html .= '<div class="alert error">' . $errs . '</div>';
            }
        }
        else
            $this->_html .= '<br />';

        $this->_displayForm();
        return $this->_html;
    }

    /**
     * Validate MOLPay backend configurations at backend code
     * 
     */
    private function _postValidation() {
        if (isset($_POST['submitModule'])) {
            if (empty($_POST['mp_merchant']))
                $this->_postErrors[] = $this->l('MOLPay Merchant ID is required.');
            if (empty($_POST['mp_vkey']))
                $this->_postErrors[] = $this->l('MOLPay Verify Key is required.');
        }
    }

    /**
     * Process the MOLPay backend configurations at backend code
     * 
     */
    private function _postProcess() {
        if (isset($_POST['submitModule'])) {
            Configuration::updateValue('MOLPAY_MERCHANT', $_POST['mp_merchant']);
            Configuration::updateValue('MOLPAY_VKEY', $_POST['mp_vkey']);
        }
        $this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="' . $this->l('ok') . '" /> '.
        $this->l('Settings updated') . '</div>';
    }

    /**
     * Display the HTML form for admin to input the MOLPay configuration
     * 
     */
    private function _displayForm() {
	$this->_html .= '
        <fieldset><legend><img src="../modules/' . $this->name.'/logo.gif" alt="" /> ' . $this->l('MOLPay Module Guide') . '</legend>
            <h4>' . $this->l('Please follow instruction below to configure this MOLPay module :') . '</h4>
            - <b>' . $this->l('MOLPay Merchant ID : ') . '</b>Put your Merchant ID provided by MOLPay.<br><br>
            - <b>' . $this->l('MOLPay Verify Key : ') . '</b>Provide your Verify Key. It makes the transaction more secure.<br><br>
        </fieldset><br />
        <form action="' . Tools::htmlentitiesutf8($_SERVER['REQUEST_URI']) . '" method="post">
            <fieldset class="width2">
                <legend><img src="../img/admin/contact.gif" alt="" />' . $this->l('Settings') . '</legend>
                <label for="mp_merchant">' . $this->l('MOLPay Merchant ID') . '</label>
                <div class="margin-form"><input type="text" size="20" id="mp_merchant" name="mp_merchant" value="' . Configuration::get('MOLPAY_MERCHANT') . '" /></div>
                <label for="mp_vkey">' . $this->l('MOLPay Verify Key') . '</label>
                <div class="margin-form"><input type="text" size="20" id="mp_vkey" name="mp_vkey" value="' . Configuration::get('MOLPAY_VKEY') . '" /></div>
                <br /><center><input type="submit" name="submitModule" value="' . $this->l('Update settings') . '" class="button" /></center>
            </fieldset>
        </form>';       
    }
    
    /**
     * Display the HTML form when user selecting the payment method and bind to smarty view
     * 
     * @global object $smarty
     * @global object $cart
     * @global object $cookie
     * @param array $params
     * @return string
     */
    public function hookPayment($params) {
        global $smarty, $cart, $cookie;

        $address = new Address(intval($params['cart']->id_address_invoice));
        $customer = new Customer(intval($params['cart']->id_customer));
        $mp_merchant = Configuration::get('MOLPAY_MERCHANT');
        $mp_vkey = Configuration::get('MOLPAY_VKEY');
        $mp_paylink = $this->getNBUrl() . $mp_merchant . "/";

        $currency_obj = $this->getCurrency();
        $curr_cart_id = $cart->id_currency;
        while ( list($k, $v) = each($currency_obj)){
            if ( $currency_obj[$k]['id_currency'] == $curr_cart_id )
                $curr_ret = $currency_obj[$k];
        }
                  
        $currency_code = $curr_ret['iso_code'];
        $orderid = intval($params['cart']->id);
        $amount = $params['cart']->getOrderTotal(true, 3);
        $bill_name = $customer->firstname . " " . $customer->lastname;
        $bill_email = $customer->email;

        $country_obj = new Country(intval($address->id_country));
        $country = $country_obj->iso_code;
        $country_name_obj = $country_obj->name;
        $country_name = $country_name_obj[1];

        $add_obj = new Address(intval($params['cart']->id_address_invoice));
        $add .= "----------------------------------\nBilling Address\n----------------------------------\n";
        $add .= $add_obj->address1 . " " . $add_obj->address2 . " " . $add_obj->postcode . " " . $add_obj->city . " " . $country_name;

        $prod_obj = $params['cart']->getProducts();
        $size = sizeof($prod_obj);
        $prod .= "\n\n\n----------------------------------\nProduct(s) Info\n----------------------------------\n";
        for( $i=0; $i<$size; $i++ ) {
            $prod .= $prod_obj[$i]['name'] . " x " . $prod_obj[$i]['cart_quantity'];
            $prod .= "\n";
        }

        //$cart = new Cart(intval($orderid));
        $bill_desc = $add . $prod;
        $vcode = md5($amount . $mp_merchant . $orderid . $mp_vkey);

        $smarty->assign(array(            
            'amount' => $amount,
            'orderid' => $orderid,
            'mp_merchant' => $mp_merchant,
            'bill_name' => $bill_name,
            'bill_email' => $bill_email,
            'bill_desc' => $bill_desc,
            'mp_paylink' => $mp_paylink,
            'currency' => $currency_code,
            'country' =>  $country,
            'vcode'   => $vcode,
            'returnurl' => 'http://' . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8') . __PS_BASE_URI__ . 'modules/molpay/validation.php',
            'this_path' => $this->_path
        ));

        return $this->display(__FILE__, 'molpay.tpl');
    }
    
    /**
     * Get the MOLPay URL
     * 
     * @return string
     */
    public function getNBUrl() {
        return $this->molpay_url;
    }

    /**
     * Define the message when status have been received (return payment)
     * 
     * @param string|int $key
     * @return string
     */
    public function getL($key) {
        $translations = array(
            '00' => $this->l('Payment with MOLPay Malaysia Online Payment Gateway (Transaction ID :'),
            '-1' => $this->l('Payment with MOLPay Malaysia Online Payment Gateway is Failed (Transaction ID : ')
        );
        return $translations[$key];
    }
}
?>
