<?php
/**
* MOLPay Prestashop Plugin
*
* @package Payment Method
* @author MOLPay Technical Team <technical@molpay.com>
* @version 2.1
*
*/
class MOLPaynotification_ipnModuleFrontController extends ModuleFrontController {

    public $ssl = true;
  
    /**
     * 
     * 
     * @see FrontController::postProcess()
     */
    public function postProcess() {

        if($_POST['orderid']!='')
            $cart = new Cart($_POST['orderid']);
        else
            exit;

        $customer = new Customer($cart->id_customer);

        $amount = $_POST['amount'];       
        $orderid = $_POST['orderid'];
        $tranID = $_POST['tranID'];
        $status = $_POST['status'];
        $domain = $_POST['domain']; 
        $currency = $_POST['currency'];
        $appcode = $_POST['appcode'];
        $paydate = $_POST['paydate'];
        $skey = $_POST['skey'];
        $vkey = Configuration::get('MOLPAY_MERCHANT_VKEY');
        $nbcb = $_POST['nbcb'];

        $key0 = md5($tranID.$orderid.$status.$domain.$amount.$currency);
        $key1 = md5($paydate.$domain.$key0.$appcode.$vkey);

        //if ($skey != $key1)
            //$status = "-1";

        if ( $nbcb=="2" ) {
            if($status == "00") {
                if ($skey != $key1)
                {
                    $this->module->validateOrder($orderid, Configuration::get('PS_OS_ERROR'), $amount, $this->module->displayName, $errors . '/r/n MOLPay Transaction ID: ' . $tranID, NULL, (int)$cart->id_currency, false, $customer->secure_key);
                }
                else
                {
                    $this->module->validateOrder($orderid, Configuration::get('PS_OS_PAYMENT'), $amount, $this->module->displayName, 'MOLPay Transaction ID: ' . $tranID, NULL, (int)$cart->id_currency, false, $customer->secure_key);
                    echo "CBTOKEN:MPSTATOK";
                    exit;                
                }

            }
            elseif ($status == "22") {
                // Pending status
                $this->module->validateOrder($orderid, Configuration::get('PS_OS_PREPARATION'), $amount, $this->module->displayName, 'MOLPay Transaction ID: ' . $tranID, NULL, (int)$currency->id, false, $customer->secure_key);
            }
            else {
                $this->module->validateOrder($orderid, Configuration::get('PS_OS_ERROR'), $amount, $this->module->displayName, $errors . '/r/n MOLPay Transaction ID: ' . $tranID, NULL, (int)$cart->id_currency, false, $customer->secure_key);
            }
        }

    }
}