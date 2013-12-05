<?php
/**
 * MOLPay Prestashop Plugin return script
 * 
 * @package Payment Method
 * @author MOLPay Technical Team <technical@molpay.com>
 * @version 1.0.0
 * 
 */
include(dirname(__FILE__). '/../../config/config.inc.php');
include(dirname(__FILE__). '/../../init.php');
include(dirname(__FILE__). '/molpay.php');	
	
$molpay 	= new MOLPay();
$mp_name 	= 'MOLPay Malaysia Online Payment Gateway ';
$cart 		= new Cart(intval($_POST['orderid']));
$amount_cart 	= number_format($cart->getOrderTotal(), 2, '.', '');

if(round($amount_cart)== $_POST['amount']) { 
    $amt_cart = $amount_cart;    
}	
	
if ($cart->id_customer == 0 OR $cart->id_address_delivery == 0 OR $cart->id_address_invoice == 0 OR !$molpay->active) 
    Tools::redirectLink(__PS_BASE_URI__ . 'order.php?step=1');

$amount 	= $_POST['amount']; 			
$orderid 	= $_POST['orderid'];
$tranID 	= $_POST['tranID'];
$status 	= $_POST['status'];
$domain 	= $_POST['domain'];	
$vkey 		= Configuration::get('MOLPAY_VKEY');
$currency 	= $_POST['currency'];
$appcode 	= $_POST['appcode'];
$paydate 	= $_POST['paydate'];
$skey 		= $_POST['skey'];

$key0 = md5($tranID . $orderid . $status . $domain . $amount . $currency);
$key1 = md5($paydate . $domain . $key0 . $appcode . $vkey);

if ($skey != $key1) {
    $status = "-1";
}
 
if ($status == "00"){ 
    $mp_msg = $molpay->getL('00') . '' . $tranID . ')';
    $molpay->validateOrder((int)$orderid, Configuration::get('PS_OS_PAYMENT'), $amount_cart, $mp_name, $mp_msg, NULL, NULL, false, $customer->secure_key);
}
else {
    $mp_msg = $molpay->getL('-1') . '' . $tranID . ')';
    $molpay->validateOrder((int)$orderid, Configuration::get('PS_OS_ERROR'), $amount_cart, $mp_name, $mp_msg, NULL, NULL, false, $customer->secure_key);
}

Tools::redirect(__PS_BASE_URI__.'order-confirmation.php?id_module='.$molpay->id.'&id_cart='.(int)$cart->id.'&key='.$customer->secure_key);	
?>