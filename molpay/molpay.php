<?php
/**
* MOLPay Prestashop Plugin
*
* @package Payment Method
* @author MOLPay Technical Team <technical@molpay.com>
*
*/
use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_'))
    exit;

class MOLPay extends PaymentModule {
	private $_html = '';
	private $_postErrors = array();
	private $molpay_channels = array(
			'credit' 	=> array( 'name' => "Credit Card/ Debit Card", 'logo' => "http://molpay.com/v3/images/molpay/channel/credit.png", 'currency' => array("MYR") ),
			'maybank2u' 	=> array( 'name' => "Maybank(Maybank2u)", 'logo' => "http://molpay.com/v3/images/molpay/channel/maybank2u.png", 'currency' => array("MYR") ),
			'cimbclicks' 	=> array( 'name' => "CIMB Bank(CIMB Clicks)", 'logo' => "http://molpay.com/v3/images/molpay/channel/cimb.png", 'currency' => array("MYR") ),
			'affinonline' 	=> array( 'name' => "Affin Bank(Affin Online)", 'logo' => "http://molpay.com/v3/images/molpay/channel/affin-epg.png", 'currency' => array("MYR") ),
			'amb' 		=> array( 'name' => "Am Bank (Am Online)", 'logo' => "http://molpay.com/v3/images/molpay/channel/amb.png", 'currency' => array("MYR") ),
			'fpx' 		=> array( 'name' => "MyClear FPX (Maybank2u, CIMB Clicks, HLB Connect, RHB Now, PBB Online, Bank Islam, etc)", 'logo' => "http://molpay.com/v3/images/molpay/channel/fpx.png", 'currency' => array("MYR") ),
			'fpx_amb'	=> array( 'name' => "FPX Am Bank (Am Online)", 'logo' => "http://molpay.com/v3/images/molpay/channel/amb.png", 'currency' => array("MYR") ),
			'fpx_bimb'	=> array( 'name' => "FPX Bank Islam", 'logo' => "http://molpay.com/v3/images/molpay/channel/bankislam.png", 'currency' => array("MYR") ),
			'fpx_cimbclicks'=> array( 'name' => "FPX CIMB Bank(CIMB Clicks)", 'logo' => "http://molpay.com/v3/images/molpay/channel/cimb.png", 'currency' => array("MYR") ),
			'fpx_hlb'	=> array( 'name' => "FPX Hong Leong Bank(HLB Connect)", 'logo' => "http://molpay.com/v3/images/molpay/channel/hlb.png", 'currency' => array("MYR") ),
			'fpx_mb2u'	=> array( 'name' => "FPX Maybank(Maybank2u)", 'logo' => "http://molpay.com/v3/images/molpay/channel/maybank2u.png", 'currency' => array("MYR") ),
			'fpx_pbb'	=> array( 'name' => "FPX PublicBank (PBB Online)", 'logo' => "http://molpay.com/v3/images/molpay/channel/publicbank.png", 'currency' => array("MYR") ),
			'fpx_rhb'	=> array( 'name' => "FPX RHB Bank(RHB Now)", 'logo' => "http://molpay.com/v3/images/molpay/channel/rhb.png", 'currency' => array("MYR") ),
			'hlb' 		=> array( 'name' => "Hong Leong Bank(HLB Connect)", 'logo' => "http://molpay.com/v3/images/molpay/channel/hlb.png", 'currency' => array("MYR") ),
			'pbb' 		=> array( 'name' => "PublicBank (PBB Online)", 'logo' => "http://molpay.com/v3/images/molpay/channel/publicbank.png", 'currency' => array("MYR") ),
			'rhb' 		=> array( 'name' => "RHB Bank(RHB Now)", 'logo' => "http://molpay.com/v3/images/molpay/channel/rhb.png", 'currency' => array("MYR") ),
			'cash-711' 	=> array( 'name' => "7-Eleven(MOLPay Cash)", 'logo' => "http://molpay.com/v3/images/molpay/channel/cash.png", 'currency' => array("MYR") ),
			'ATMVA' 	=> array( 'name' => "ATM Transfer via Permata Bank", 'logo' => "http://molpay.com/v3/images/molpay/channel/Cash-PermataBank.png", 'currency' => array("IDR") ),
			'dragonpay' 	=> array( 'name' => "Dragonpay", 'logo' => "http://molpay.com/v3/images/molpay/channel/dragonpay.png", 'currency' => array("PHP") ),
			'paysbuy' 	=> array( 'name' => "PaysBuy", 'logo' => "http://molpay.com/v3/images/molpay/channel/paysbuy.png", 'currency' => array("THB","AUD","GBP","EUR","HKD","JPY","NZD","SGD","CHF","USD") ),
			'Point-BCard' 	=> array( 'name' => "Bcard points", 'logo' => "http://molpay.com/v3/images/molpay/channel/pointbcard.png", 'currency' => array("MYR") ),
			'NGANLUONG' 	=> array( 'name' => "NGANLUONG", 'logo' => "http://molpay.com/v3/images/molpay/channel/nganluong.png", 'currency' => array("VND","USD") ),
			'enetsD' 	=> array( 'name' => "eNETS", 'logo' => "http://molpay.com/v3/images/molpay/channel/enetsD.png", 'currency' => array("SGD") ),
			'UPOP'	 	=> array( 'name' => "China Union pay", 'logo' => "http://molpay.com/v3/images/molpay/channel/Unionpay.png", 'currency' => array("MYR","USD","CNY") ),
			'alipay' 	=> array( 'name' => "Alipay", 'logo' => "http://molpay.com/v3/images/molpay/channel/alipay.png", 'currency' => array("MYR","USD","CNY") ),
			'paypal'        => array( 'name' => "PayPal", 'logo' => "http://molpay.com/v3/images/molpay/channel/paypal.png", 'currency' => array("USD","AUD","GBP","CAD","CZK","DKK","EUR","HKD","HUF","ILS","JPY","MYR","MXN","NZD","NOK","PHP","PLN","SGD","SEK","CHF","TWD","THB") ),
			'cash-epay' 	=> array( 'name' => "e-Pay", 'logo' => "http://molpay.com/v3/images/molpay/channel/epay.png", 'currency' => array("MYR") ),
			'molwallet' 	=> array( 'name' => "MOLWallet", 'logo' => "http://molpay.com/v3/images/molpay/channel/MOLWallet.png", 'currency' => array("MYR") ),
			'PEXPLUS' 	=> array( 'name' => "PEx+", 'logo' => "http://molpay.com/v3/images/molpay/channel/pexplus.png", 'currency' => array("MYR") ),
			'jompay' 	=> array( 'name' => "JOMPay", 'logo' => "http://molpay.com/v3/images/molpay/channel/jompay.png", 'currency' => array("MYR") ),
			'Cash-Esapay' 	=> array( 'name' => "Cash Esapay", 'logo' => "http://molpay.com/v3/images/molpay/channel/esapay.png", 'currency' => array("MYR") )
		);

	public function __construct() {
		$this->name = 'molpay';
		$this->tab = 'payments_gateways';
		$this->version = '2.2.8';
		$this->author = 'MOLPay Technical Teams';
		$this->author_uri = 'https://github.com/MOLPay/Prestashop_Plugin';
		$this->controllers = array('payment', 'validation');
		$this->bout_valide = $this->l('Validate');        
		$this->currencies = true;
		$this->currencies_mode = 'checkbox';        
		$this->bootstrap = true;

		$config = Configuration::getMultiple(array('MOLPAY_MERCHANT_VKEY', 'MOLPAY_MERCHANT_PVKEY', 'MOLPAY_MERCHANT_ACCTYPE', 'MOLPAY_MERCHANT_ID', 'MOLPAY_SEAMLESS_JS'));
		if(isset($config['MOLPAY_MERCHANT_VKEY']))
			$this->MOLPAY_MERCHANT_VKEY = $config['MOLPAY_MERCHANT_VKEY'];
		if(isset($config['MOLPAY_MERCHANT_PVKEY']))
                        $this->MOLPAY_MERCHANT_PVKEY = $config['MOLPAY_MERCHANT_PVKEY'];
                if(isset($config['MOLPAY_MERCHANT_ACCTYPE']))
                        $this->MOLPAY_MERCHANT_PVKEY = $config['MOLPAY_MERCHANT_ACCTYPE'];
		if(isset($config['MOLPAY_MERCHANT_ID']))
			$this->MOLPAY_MERCHANT_ID = $config['MOLPAY_MERCHANT_ID'];
		if(isset($config['MOLPAY_SEAMLESS_JS']))
			$this->MOLPAY_SEAMLESS_JS = $config['MOLPAY_SEAMLESS_JS'];


		parent::__construct();
		$this->displayName = 'MOLPay - Leading Payment Gateway in South East Asia';
		$this->description = $this->l('The leading payment gateway in South East Asia Grow your business with MOLPay payment solutions & free features: Physical Payment at 7-Eleven, Seamless Checkout, Tokenization, Loyalty Program and more.');
		$this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');

		if(!count(Currency::checkPaymentCurrencies($this->id)))
				$this->warning = $this->l('No currency set for this module');
		if(!isset($this->MOLPAY_MERCHANT_VKEY) || !isset($this->MOLPAY_MERCHANT_ID))
				$this->warning = $this->l('Your MOLPay account must be set correctly');
                if(!isset($this->MOLPAY_MERCHANT_PVKEY) || !isset($this->MOLPAY_MERCHANT_ID))
                                $this->warning = $this->l('Your MOLPay account must be set correctly');
		if(!isset($this->MOLPAY_SEAMLESS_JS) || !isset($this->MOLPAY_SEAMLESS_JS))
				$this->warning = $this->l('This plugin required MOLPay Seamless API javascript file to be loaded.');
	}


	
	/**
	 * Install the MOLPay module into prestashop
	 * 
	 * @return boolean
	 */
	public function install() {
		if (!parent::install() || !$this->registerHook('paymentOptions') || !$this->registerHook('paymentReturn') || !$this->registerHook('payment') || !$this->registerHook('header') ) {
                    return false;
                    }
                    return true;
	}

	/**
	 * Uninstall the MOLPay module from prestashop
	 * 
	 * @return boolean
	 */
	public function uninstall() {
		foreach( $this->molpay_channels As $k => $v ) {
			if( Configuration::get($k."_on") ) {
				if( !Configuration::deleteByName($k."_on") )
					return false;
			}
		}
		if (!Configuration::deleteByName('MOLPAY_MERCHANT_VKEY') || !Configuration::deleteByName('MOLPAY_MERCHANT_PVKEY') || !Configuration::deleteByName('MOLPAY_MERCHANT_ACCTYPE') || !Configuration::deleteByName('MOLPAY_MERCHANT_ID') || !Configuration::deleteByName('MOLPAY_SEAMLESS_JS') || !parent::uninstall())
			return false;
		else
			return true;
	}

	/**
	 * Validate the form submited by MOLPay configuration setting
	 * 
	 */
	protected function _postValidation() {
		if (Tools::isSubmit('btnSubmit')) {
			if (!Tools::getValue('merchant_id'))
				$this->_postErrors[] = $this->l('Merchant ID is required');
			else if (!Tools::getValue('merchant_vkey'))
				$this->_postErrors[] = $this->l('Merchant Verify Key is required.');
			else if (!Tools::getValue('merchant_pvkey'))
                                $this->_postErrors[] = $this->l('Merchant Secret Key is required.');	
			else if (!Tools::getValue('merchant_acctype'))
                                $this->_postErrors[] = $this->l('Merchant Account Type is required.');
			else if (!Tools::getValue('seamless_js'))
				$this->_postErrors[] = $this->l('Seamless javascript URL is required.');
		}
	}

	/**
	 * Save/update the MOLPay configuration setting
	 * 
	 */
	protected function _postProcess() {

		if (isset($_POST['btnSubmit'])) {
			Configuration::updateValue('MOLPAY_MERCHANT_ID', Tools::getValue('merchant_id'));
			Configuration::updateValue('MOLPAY_MERCHANT_VKEY', Tools::getValue('merchant_vkey'));
                        Configuration::updateValue('MOLPAY_MERCHANT_PVKEY', Tools::getValue('merchant_pvkey'));
			Configuration::updateValue('MOLPAY_MERCHANT_ACCTYPE', Tools::getValue('merchant_acctype'));
			Configuration::updateValue('MOLPAY_SEAMLESS_JS', Tools::getValue('merchant_acctype')."MOLPay/API/seamless/latest/js/MOLPay_seamless.deco.js");
			foreach( $this->molpay_channels As $k => $v )
				Configuration::updateValue($k."_on", Tools::getValue($k."_on"));
		}
		$this->_html .= $this->displayConfirmation($this->l('Settings updated'));
	}

	/**
	 * Display notification after saving the MOLPay configuration setting
	 * 
	 */
	private function _displayMOLPay() {
		return $this->display(__FILE__, 'infos.tpl');
	}

	/**
	 * Display the MOLPay configuration setting. <call private method>
	 * 
	 * @return string
	 */
	public function getContent()
	{

		if (Tools::isSubmit('btnSubmit'))
		{
			$this->_postValidation();
			if (!count($this->_postErrors))
				$this->_postProcess();
			else
				foreach ($this->_postErrors as $err)
					$this->_html .= $this->displayError($err);
		}
		else
			$this->_html .= '<br />';


		$this->_html .= $this->_displayMOLPay();
		$this->_html .= $this->renderForm();

		return $this->_html;
	}

	/**
	 * Hook the payment form to the prestashop Payment method. Display in payment method selection
	 * 
	 * @param array $params
	 * @return string
	 */
	public function hookPaymentOptions($params)
	{
          if (!$this->active) {
            return;
          }
          
          if (!$this->checkCurrency($params['cart'])) {
            return;
          }

          $currency_obj = $this->context->currency;
		$currency_code = $currency_obj->iso_code;
		$av_channels = array();
		foreach( $this->molpay_channels As $k => $v ) {
			if( Configuration::get($k."_on") && in_array($currency_code,$v['currency']) )
				$av_channels[ $k ] = $v;
		}

		$this->smarty->assign(array(
				'seamless_js_file' => Configuration::get('MOLPAY_SEAMLESS_JS'),
				// 'payment_options' => $payment_options,
				'av_channels' => $av_channels,
				'this_path' => $this->_path
		));

           $newOption = new PaymentOption();
           $newOption->setModuleName($this->name)
                     ->setCallToActionText($this->l('MOLPay'))
                     ->setAdditionalInformation($this->fetch('module:molpay/views/templates/front/payment_infos.tpl'));

           return [$newOption];
        }
	////

	/**
	 * Hook the payment return to the prestashop payment return method
	 * 
	 * @param array $params
	 * @return string
	 */
	public function hookPaymentReturn($params) 
	{
		if (!$this->active)
				return;

		// $state = $params['objOrder']->getCurrentState();
		if ($state == Configuration::get('PS_OS_PAYMENT')) {
			$this->smarty->assign(array(
				'status' => '00',
				'merchantID' => Configuration::get('MOLPAY_MERCHANT_ID'),
				'id_order' => $params['objOrder']->id
			));
			if(isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
					$this->smarty->assign('reference', $params['objOrder']->reference);
		}		
		else if ($state == Configuration::get('PS_OS_PREPARATION')) {
			$this->smarty->assign(array(
					'status' => '22',
					'merchantID' => Configuration::get('MOLPAY_MERCHANT_ID'),
					'id_order' => $params['objOrder']->id
			));
			if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
				$this->smarty->assign('reference', $params['objOrder']->reference);
		}
		else if ($state == Configuration::get('PS_OS_ERROR')) {
			$this->smarty->assign(array(
					'status' => '11',
					'merchantID' => Configuration::get('MOLPAY_MERCHANT_ID'),
					'id_order' => $params['objOrder']->id
			));
			if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
				$this->smarty->assign('reference', $params['objOrder']->reference);
		}
		else
			$this->smarty->assign('status', 'other');
        
		return $this->fetch('module:molpay/views/templates/hook/payment_return.tpl');
	}

	/**
	 * Check the currency
	 * 
	 * @param object $cart
	 * @return boolean
	 */
	public function checkCurrency($cart) {
		$currency_order = new Currency($cart->id_currency);
		$currencies_module = $this->getCurrency($cart->id_currency);

		if (is_array($currencies_module))
			foreach ($currencies_module as $currency_module)
				if ($currency_order->id == $currency_module['id_currency'])
					return true;
		return false;
	}	

	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Account details'),
					'icon' => 'icon-envelope'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Merchant ID'),
						'name' => 'merchant_id',
						'required' => true
					),
					array(
						'type' => 'text',
						'label' => $this->l('Verify Key'),
						'name' => 'merchant_vkey',
						'required' => true
					),
					array(
                                                'type' => 'text',
                                                'label' => $this->l('Secret Key'),
                                                'name' => 'merchant_pvkey',
                                                'required' => true
                                        ),
                                        array(
                                                'type' => 'radio',
                                                'label' => $this->l('Account Type'),
                                                'name' => 'merchant_acctype',
						    'values' => array(
						                array(
                                                'id' => 'production',
                                                'label' => $this->l('Production'),
                                                'value' => 'https://www.onlinepayment.com.my/'
                                                                     ),
								array(
								        'id' => 'sandbox',
								        'label' => $this->l('Sandbox'),
									    'value' => 'https://sandbox.molpay.com/'
                                                                     ),
							   	 ),
                                                'required'  => true,
                                                'class'     => 't'
                                        ),
					array(
						'type' => 'hidden',
						'label' => $this->l('Seamless JS URL'),
						'name' => 'seamless_js',
						'desc' => ' Get latest version at <a target="_blank" href="https://github.com/MOLPay/Seamless_Integration">here</a>.',
						//'readonly' => 'readonly',
						'required' => true
					),
				),
				
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);
		$fields_form['form']['input'][] =array(
						
						'label' => $this->l('Payment Channel'),
					);
			
		foreach( $this->molpay_channels As $k => $v )
		{
			$fields_form['form']['input'][] = array(
				'type' => 'checkbox',
				'name' => $k,
				'values' => array(
					'query' => array(
						array(
							'id' => 'on',
							'name' => $this->l($v['name'])." <small>(".implode(", ", $v['currency']).")</small>",
							'val' => '1'
						),
					),
					'id' => 'id',
					'name' => 'name'
				)
			);
		}

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->id = (int)Tools::getValue('id_carrier');
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'btnSubmit';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		$MOLPAY_MERCHANT_ACCTYPE = Configuration::get('MOLPAY_MERCHANT_ACCTYPE');
		$MOLPAY_SEAMLESS_JS = Configuration::get('MOLPAY_SEAMLESS_JS');
		
		//$MOLPAY_SEAMLESS_JS = $MOLPAY_MERCHANT_ACCTYPE."MOLPay/API/seamless/latest/js/MOLPay_seamless.deco.js";
		/*if ($MOLPAY_MERCHANT_ACCTYPE == 'https://www.onlinepayment.com.my/')
		{
			$MOLPAY_SEAMLESS_JS = "https://www.onlinepayment.com.my/MOLPay/API/seamless/latest/js/MOLPay_seamless.deco.js";
		}else{
			$MOLPAY_SEAMLESS_JS = "https://sandbox.molpay.com/MOLPay/API/seamless/latest/js/MOLPay_seamless.deco.js";
		}*/
		/*if( empty($MOLPAY_SEAMLESS_JS) )
			$MOLPAY_SEAMLESS_JS = "https://www.onlinepayment.com.my/MOLPay/API/seamless/latest/js/MOLPay_seamless.deco.js"; */

		$result = array(
			'merchant_id' => Tools::getValue('merchant_id', Configuration::get('MOLPAY_MERCHANT_ID')),
			'merchant_vkey' => Tools::getValue('merchant_vkey', Configuration::get('MOLPAY_MERCHANT_VKEY')),
            'merchant_pvkey' => Tools::getValue('merchant_pvkey', Configuration::get('MOLPAY_MERCHANT_PVKEY')),
            'merchant_acctype' => Tools::getValue('merchant_acctype', Configuration::get('MOLPAY_MERCHANT_ACCTYPE')),
			'seamless_js' => Tools::getValue('seamless_js', Configuration::get('MOLPAY_SEAMLESS_JS')),
		);
		
		foreach( $this->molpay_channels As $k => $v )
			$result[ $k."_on" ] = Tools::getValue($k."_on", Configuration::get($k."_on"));

		return $result;
	}

}
?>
