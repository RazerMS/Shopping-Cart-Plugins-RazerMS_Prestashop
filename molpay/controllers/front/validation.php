<?php
/**
* MOLPay Prestashop Plugin
*
* @package Payment Method
* @author MOLPay Technical Team <technical@molpay.com>
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
	public function postProcess()
	{
		$cart = $this->context->cart;
		if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
			Tools::redirect('index.php?controller=order&step=1');

		// Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
		$authorized = false;
		foreach (Module::getPaymentModules() as $module)
			if ($module['name'] == 'molpay')
			{
				$authorized = true;
				break;
			}
		if (!$authorized)
			die($this->module->l('This payment method is not available.', 'validation'));

		$customer = new Customer($cart->id_customer);
		if (!Validate::isLoadedObject($customer))
			Tools::redirect('index.php?controller=order&step=1');

		$amount		= $_POST['amount'];             
		$cartid	= $_POST['orderid'];
		$tranID		= $_POST['tranID'];
		$status		= $_POST['status'];
		$domain		= $_POST['domain']; 
		$currency	= $_POST['currency'];
		$appcode	= $_POST['appcode'];
		$paydate	= $_POST['paydate'];
		$skey			= $_POST['skey'];
		$response	= array();
		foreach( $_POST As $k => $v )
			$response[] = "[".$k."]=".$v;
		$vkey			= Configuration::get('MOLPAY_MERCHANT_VKEY');

		$key0 = md5($tranID.$cartid.$status.$domain.$amount.$currency);
		$key1 = md5($paydate.$domain.$key0.$appcode.$vkey);

		$PS_STATUS = Configuration::get('PS_OS_ERROR');
		if($status == "00") {
			if ($skey == $key1)
				$PS_STATUS = Configuration::get('PS_OS_PAYMENT');
		}
		elseif($status == "22")
			$PS_STATUS = Configuration::get('PS_OS_PREPARATION');
		else{
			$PS_STATUS = Configuration::get('PS_OS_ERROR');
		}	
		$objOrder = new Order( (int)Order::getOrderByCartId($cartid) );
		if (Order::getOrderByCartId($cartid) === false )
		{
			$cart = new Cart($cartid);
			if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
				exit;
			
			// Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
			$authorized = false;
			foreach (Module::getPaymentModules() as $module)
				if ($module['name'] == 'molpay')
				{
					$authorized = true;
					break;
				}
			if (!$authorized)
				exit;

			$customer = new Customer($cart->id_customer);
			if (!Validate::isLoadedObject($customer))
				exit;

			$extra_vars['transaction_id'] = $tranID;
			$this->module->validateOrder($cartid, $PS_STATUS, $amount, $this->module->displayName, implode("\n ", $response), $extra_vars, (int)$currency->id, false, $customer->secure_key);
		}
		else
		{
			$current_state = $objOrder->current_state;
			if($current_state != 2)
			{
				$new_history = new OrderHistory();
				$new_history->id_order = (int)$objOrder->id;
				$new_history->changeIdOrderState($PS_STATUS, $objOrder, true);
				$new_history->addWithemail(true);
				
				$payment = $objOrder->getOrderPaymentCollection();
				if (isset($payment[0]))
				{
					$payment[0]->transaction_id = $tranID;
					$payment[0]->save();
				}
				
				$msg = new Message();
				$msg->message = ( (count($response) > 0)  ? implode("\n ", $response) : "Missing POST value" );
				$msg->id_cart = (int)$cartid;
				$msg->id_customer = (int)($objOrder->id_customer);
				$msg->id_order = (int)$objOrder->id;
				$msg->private = 1;
				$msg->add();
			}
		}

		if( function_exists('curl_version') )
		{
			$_POST['treq']= '1'; // Additional parameter for IPN
			while ( list($k,$v) = each($_POST) ) 
				$postData[]= $k."=".$v;
			$postdata = implode("&",$postData);
			$url = "https://www.onlinepayment.com.my/MOLPay/API/chkstat/returnipn.php";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST , 1 );
			curl_setopt($ch, CURLOPT_POSTFIELDS , $postdata );
			curl_setopt($ch, CURLOPT_URL , $url );
			curl_setopt($ch, CURLOPT_HEADER , 0 );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER , 0 );
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , FALSE);
			$result = curl_exec( $ch );
			curl_close( $ch );
		}
	        if($status == "00" || $status == "22") {
		Tools::redirect('index.php?controller=order-confirmation&status='.$status.'&id_cart='.$cartid.'&id_module='.$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
		}else{
		Tools::redirect('index.php?controller=cart&action=show');
		}
	}
}