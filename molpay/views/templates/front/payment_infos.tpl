{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<section>
<div id="pm_molpay" class="payment_module">
  <p>
    {l s='Pay by' mod='molpay'}&nbsp;<small>{l s='(Please select bank listed below to proceed)' mod='molpay'}</small>
  </p>
  <form id="frm_molpay" method="POST" action="{$link->getModuleLink('molpay', 'payment')|escape:'html'}" role="molpayseamless">
    <div class="container">
      <div class="row">
        {if $av_channels}
        {foreach from=$av_channels  key=k item=v}
        <div class='col-sm-4 text-center p-t-sm p-b-sm'>
                <label for='{$k}_on'><img class='img-responsive' title='{$v['name']}' src='{$v['logo']}'></label>
                <center><input id='{$k}_on' type='radio' required value='{$k}' name='payment_options'><center>
                </div>
        {/foreach}
        {else}
            <div class='alert alert-danger'>There is no payment option available for this currency. Please use others currency.</div>
        {/if}
    </div>
  </form>
</div>
</section>
<style>
#pm_molpay {
  border: 1px solid #d6d4d4;
  border-radius: 4px;
  color: #333;
  display: block;
  font-size: 17px;
  font-weight: bold;
  padding: 0;
  position: relative;
}
#pm_molpay {
  padding: 15px;
  margin-bottom: 10px;
}
#pm_molpay .container img,
#pm_molpay .container input {
  cursor: pointer;
}
#pm_molpay .p-t-sm {
  padding-top: 5px;
}
#pm_molpay .p-b-sm {
  padding-bottom: 5px;
}
#frm_molpay img {
  border: 2px solid grey;
  border-radius: 10px;
}
</style>

<script>
$(document).ready( function(){
  $.getScript("{$seamless_js_file}");
  $('input[name=payment_options]').on('click', function(){
    var $myForm = $(this).closest('form');
        $myForm.trigger("submit");

    // $('[role="molpayseamless"]').trigger("submit");
    // e.preventDefault();
    //return false;
  });
});
</script>