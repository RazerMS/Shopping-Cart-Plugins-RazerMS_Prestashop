<?php
/**
 * MOLPay Prestashop Plugin callback script
 * 
 * @package Payment Method
 * @author MOLPay Technical Team <technical@molpay.com>
 * @version 1.0.0
 * 
 */

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/molpay.php');

$molpay 	= new MOLPay();
$mp_name 	= 'MOLPay Malaysia Online Payment Gateway ';
$cart 		= new Cart(intval($_POST['orderid']));
$amount_cart 	= number_format($cart->getOrderTotal(), 2, '.', '');

if(round($amount_cart)== $_POST['amount']) { 
    $amt_cart = $amount_cart;    
}

$nbcb 		= $_POST['nbcb'];
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
if ( $nbcb=="1" ) { 
    // successful transaction
    if ($status == "00") {
        $molpay->validateOrder(intval($orderid), _PS_OS_PAYMENT_, $amount_cart, $mp_name, $molpay->getL('00') . '' .$tranID . ')');        
    }
    // failure transaction
    else { 
        $molpay->validateOrder(intval($orderid), _PS_OS_ERROR_, $amount_cart, $mp_name, $molpay->getL('-1') . '' . $tranID . ')');        
    }
}
?>