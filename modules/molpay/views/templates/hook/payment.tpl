{*
* MOLPay Payment method selection
*}

<form action="{$molpayUrl}" method="post" id="molpay_form" class="hidden">
    <input type="hidden" name="amount" value="{$amount}" />
    <input type="hidden" name="orderid" value="{$orderid}" />
    <input type="hidden" name="bill_name" value="{$bill_name}" />
    <input type="hidden" name="bill_email" value="{$bill_email}" />
    <input type="hidden" name="bill_desc" value="{$bill_desc}" />
    <input type="hidden" name="currency" value="{$currency}" />
    <input type="hidden" name="country" value="{$country}" />
    <input type="hidden" name="vcode" value="{$vcode}" />
    <input type="hidden" name="returnurl" value="{$returnurl}" />
</form>

<p class="payment_module">
    <a href="javascript:document.forms['molpay_form'].submit();" title="{l s='Online Payment with MOLPay' mod='molpay'}">
        <img src="{$module_template_dir}molpay.gif" alt="{l s='Online Payment with MOLPay' mod='molpay'}"/>
        {l s='Pay with MOLPay Malaysia Online Payment Gateway' mod='molpay'}
    </a>
</p>