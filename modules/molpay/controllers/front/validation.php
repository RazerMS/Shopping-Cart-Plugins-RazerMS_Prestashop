<?php
/**
* MOLPay Prestashop Plugin
*
* @package Payment Method
* @author MOLPay Technical Team <technical@molpay.com>
* @version 2.1
*
*/

class MOLPayValidationModuleFrontController extends ModuleFrontController {
    public $display_column_left = false;
    public $display_column_right = false;
    public $ssl = true;
	
    /**
     * 
     * 
     * @see FrontController::postProcess()
     */
    public function postProcess() {
    
        $cart = $this->context->cart;
        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
            Tools::redirect('index.php?controller=order&step=1');

        // Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if($module['name'] == 'molpay') {
                $authorized = true;
                break;
            }
        }
        if(!$authorized)
            die($this->module->l('This payment method is not available.', 'validation'));

        $customer = new Customer($cart->id_customer);
        if (!Validate::isLoadedObject($customer))
            Tools::redirect('index.php?controller=order&step=1');

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

        $key0 = md5($tranID.$orderid.$status.$domain.$amount.$currency);
        $key1 = md5($paydate.$domain.$key0.$appcode.$vkey);

        //if ($skey != $key1)
        //    $status = "-1";

        $currency = $this->context->currency;
        $total = (float)$cart->getOrderTotal(true, Cart::BOTH);

        if(!isset($_GET['gotoorder'])) {
            echo include_once 'modules/molpay/views/templates/custom.php';
            die();
        }

        if($status == "00") {
             if ($skey != $key1)
             {
                $this->module->validateOrder($orderid, Configuration::get('PS_OS_ERROR'), $amount, $this->module->displayName, $errors . '/r/n MOLPay Transaction ID: ' . $tranID, NULL, (int)$currency->id, false, $customer->secure_key);
             }
             else
             {
                $this->module->validateOrder($orderid, Configuration::get('PS_OS_PAYMENT'), $amount, $this->module->displayName, 'MOLPay Transaction ID: ' . $tranID, NULL, (int)$currency->id, false, $customer->secure_key);
             }
        }
        else {
            if($status == "22"){
                 $this->module->validateOrder($orderid, Configuration::get('PS_OS_PREPARATION'), $amount, $this->module->displayName, 'MOLPay Transaction ID: ' . $tranID, NULL, (int)$currency->id, false, $customer->secure_key);
            }
            else{
                $this->module->validateOrder($orderid, Configuration::get('PS_OS_ERROR'), $amount, $this->module->displayName, $errors . '/r/n MOLPay Transaction ID: ' . $tranID, NULL, (int)$currency->id, false, $customer->secure_key);
            }   
        }
        Tools::redirect('index.php?controller=history');
    }
}