<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/**
 * @since 1.5.0
 */
class MOLPayPaymentModuleFrontController extends ModuleFrontController {
	public $ssl = true;
	public $display_column_left = false;

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent() {
		parent::initContent();

		header('Content-Type: application/json');
		$cart = $this->context->cart;
		if(!$this->module->checkCurrency($cart)) {
			$molpayparams = array(
				'status'        => false,      // Set False to show an error message.
				'error_code'	=> "500",
				'error_desc'    => "Internal Server Error",
				'failureurl'    => ( ($_SERVER['HTTPS'] == "on") ? "https" : "http" )."://".$_SERVER['HTTP_HOST'].__PS_BASE_URI__."index.php?controller=order"
			);
			echo json_encode($molpayparams);
			exit;
		}

		$customer 		= new Customer((int)$cart->id_customer);
		$address     	= new Address(intval($cart->id_address_invoice));
		$prod_obj		= $cart->getProducts();
		$country_obj 	= new Country(intval($address->id_country));
		
		$currency_obj 	= $this->context->currency;
		$currency_code 	= $currency_obj->iso_code;
		
		$desc_str = "Billing Address\n";
		$desc_str .= "\t".$address->address1." ".$address->address2." ".$address->postcode." ".$address->city." ".$address->country."\n";
		$desc_str .= "Product(s) Info\n";
		$desc_str .= "\t".count($prod_obj)." unique item(s)\n";
		
		foreach($prod_obj as $prod_obj_each) {
			$desc_str .= $prod_obj_each['name']." x ".$prod_obj_each['cart_quantity']."\n";
		}
		
		if($_POST['payment_options'] == "BOOST")
			if(strlen($desc_str) > 250 - 3) //250 is max length for BOOST, more will cause error
				$desc_str = str_split($desc_str, 250 - 3)[0]."...";
		
		// MOLPay Parameters
		$molpayparams['status']			= true;
		$molpayparams['mpsmerchantid']	= Configuration::get('MOLPAY_MERCHANT_ID');
		$molpayparams['mpsamount']		= number_format( $this->context->cart->getOrderTotal(true, Cart::BOTH), 2, ".", "");
		$molpayparams['mpsorderid']		= intval($this->context->cart->id);
		$molpayparams['mpsbill_name']	= $customer->firstname." ".$customer->lastname;
		$molpayparams['mpsbill_email']	= $customer->email;
		$molpayparams['mpsbill_mobile']	= (empty($address->phone) ? $address->phone_mobile : $address->phone);
		$molpayparams['mpsbill_desc']	= $desc_str;
		$molpayparams['mpscountry']		= $country_obj->iso_code;
		$molpayparams['mpschannel']		= $_POST['payment_options'];
		$molpayparams['mpsvcode']		= md5($molpayparams['mpsamount'].$molpayparams['mpsmerchantid'].$molpayparams['mpsorderid'].Configuration::get('MOLPAY_MERCHANT_VKEY'));
		$molpayparams['mpscurrency']	= $currency_code;
		$molpayparams['mpsreturnurl']	= (isset($_SERVER['HTTPS'])  ? "https" : "http")."://".$_SERVER['HTTP_HOST'].__PS_BASE_URI__."index.php?fc=module&module=molpay&controller=validation";

		echo json_encode($molpayparams);
		exit;
	}
}
