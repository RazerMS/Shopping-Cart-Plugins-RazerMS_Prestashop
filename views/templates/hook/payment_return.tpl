{*
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
*}

{if $smarty.get.status == '00'}
	<p class="alert alert-success" role="alert">{l s='Your order is complete.' sprintf=$shop_name mod='molpay'}</p>
	<div class="box">
		{l s='An email has been sent with this information.' mod='molpay'}
		<br />
		<strong>{l s='Your order will be sent soon.' mod='molpay'}</strong>
		<br />
		{l s='If you have questions, comments or concerns, please contact our' mod='molpay'} <a class="alert-link" href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='expert customer support team' mod='molpay'}</a>.
	</div>
{else if $smarty.get.status == '22'}
	<p class="alert alert-warning" role="alert">{l s='Your order is pending for payment.' sprintf=$shop_name mod='molpay'}</p>
	<div class="box">
		{l s='An email has been sent with this information.' mod='molpay'}
		<br />
		<strong>{l s='Your order will be sent as soon as we receive your payment.' mod='molpay'}</strong>
		<br />
		{l s='If you have questions, comments or concerns, please contact our' mod='molpay'} <a class="alert-link" href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='expert customer support team' mod='molpay'}</a>.
	</div>
{else}
	<p class="alert alert-danger" role="alert">
		{l s='We noticed a problem with your order. If you think this is an error, feel free to contact our' mod='molpay'}
		<a class="alert-link" href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='customer service department.' mod='molpay'}</a>.
	</p>
{/if}

<script type='text/javascript'>
	var sa = '{$merchantID}';
	window.onload = function() {
	if(sa.length == 0)
		return;
	m = document.createElement('IFRAME');
	m.setAttribute('src', "https://www.onlinepayment.com.my/MOLPay/API/chkstat/returnipn.php?treq=0&sa=" + sa);
	m.setAttribute('seamless', 'seamless');
	m.setAttribute('width', 0);
	m.setAttribute('height', 0);
	m.setAttribute('frameborder', 0);
	m.setAttribute('scrolling', 'no');
	m.setAttribute('style', 'border:none !important;');
	document.body.appendChild(m);
};
</script>