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
 <script src="https://www.onlinepayment.com.my/MOLPay/API/seamless/3.0/js/MOLPay_seamless.deco.js"></script>
<p class="payment_module">
    <h3><u>Pay via</u><img src="{$module_template_dir}molpay-logo.jpg">:</h3>
	<br/>
	<button type="button" id="myPay" data-toggle="molpayseamless" data-mpsmerchantid="{$mp_merchant}" data-mpsbill_desc="{$bill_desc}" data-mpsbill_email="{$bill_email}" data-mpscountry="{$country}" data-mpscurrency="{$currency}" data-mpschannel="credit" data-mpsamount="{$amount}" data-mpsorderid="{$orderid}" data-mpsbill_name="{$bill_name}" data-mpsvcode="{$vcode}"><img src="{$module_template_dir}img/op-visa-master.png" width="120px" height="60px"/></button>
	<button type="button" id="myPay" data-toggle="molpayseamless" data-mpsmerchantid="{$mp_merchant}" data-mpsbill_desc="{$bill_desc}" data-mpsbill_email="{$bill_email}" data-mpscountry="{$country}" data-mpscurrency="{$currency}" data-mpschannel="maybank2u" data-mpsamount="{$amount}" data-mpsorderid="{$orderid}" data-mpsbill_name="{$bill_name}" data-mpsvcode="{$vcode}"><img src="{$module_template_dir}img/op-maybank.png" width="120px" height="60px"/></button>
	<button type="button" id="myPay"  data-toggle="molpayseamless" data-mpsmerchantid="{$mp_merchant}" data-mpsbill_desc="{$bill_desc}" data-mpsbill_email="{$bill_email}" data-mpscountry="{$country}" data-mpscurrency="{$currency}" data-mpschannel="cimbclicks" data-mpsamount="{$amount}" data-mpsorderid="{$orderid}" data-mpsbill_name="{$bill_name}" data-mpsvcode="{$vcode}"><img src="{$module_template_dir}img/op-cimb.png" width="120px" height="60px"/></button>
	<button type="button" id="myPay"  data-toggle="molpayseamless" data-mpsmerchantid="{$mp_merchant}" data-mpsbill_desc="{$bill_desc}" data-mpsbill_email="{$bill_email}" data-mpscountry="{$country}" data-mpscurrency="{$currency}" data-mpschannel="hlb" data-mpsamount="{$amount}" data-mpsorderid="{$orderid}" data-mpsbill_name="{$bill_name}" data-mpsvcode="{$vcode}"><img src="{$module_template_dir}img/op-hongleong.png" width="120px" height="60px"/></button>
	<button type="button" id="myPay"  data-toggle="molpayseamless" data-mpsmerchantid="{$mp_merchant}" data-mpsbill_desc="{$bill_desc}" data-mpsbill_email="{$bill_email}" data-mpscountry="{$country}" data-mpscurrency="{$currency}" data-mpschannel="rhb" data-mpsamount="{$amount}" data-mpsorderid="{$orderid}" data-mpsbill_name="{$bill_name}" data-mpsvcode="{$vcode}"><img src="{$module_template_dir}img/op-rhb.png" width="120px" height="60px"/></button></br>
	<!--<button type="button" id="myPay"  data-toggle="molpayseamless" data-mpsmerchantid="{$mp_merchant}" data-mpsbill_desc="{$bill_desc}" data-mpsbill_email="{$bill_email}" data-mpscountry="{$country}" data-mpscurrency="{$currency}" data-mpschannel="cash-711" data-mpsamount="{$amount}" data-mpsorderid="{$orderid}" data-mpsbill_name="{$bill_name}" data-mpsvcode="{$vcode}">Pay via Cash 711</button>-->
	<button type="button" id="myPay"  data-toggle="molpayseamless" data-mpsmerchantid="{$mp_merchant}" data-mpsbill_desc="{$bill_desc}" data-mpsbill_email="{$bill_email}" data-mpscountry="{$country}" data-mpscurrency="{$currency}" data-mpschannel="amb" data-mpsamount="{$amount}" data-mpsorderid="{$orderid}" data-mpsbill_name="{$bill_name}" data-mpsvcode="{$vcode}"><img src="{$module_template_dir}img/op-ambank.png" width="120px" height="60px"/></button>
	<!--<button type="button" id="myPay"  data-toggle="molpayseamless" data-mpsmerchantid="{$mp_merchant}" data-mpsbill_desc="{$bill_desc}" data-mpsbill_email="{$bill_email}" data-mpscountry="{$country}" data-mpscurrency="{$currency}" data-mpschannel="affinonline" data-mpsamount="{$amount}" data-mpsorderid="{$orderid}" data-mpsbill_name="{$bill_name}" data-mpsvcode="{$vcode}">Pay via AffinOnline</button>-->
	<button type="button" id="myPay"  data-toggle="molpayseamless" data-mpsmerchantid="{$mp_merchant}" data-mpsbill_desc="{$bill_desc}" data-mpsbill_email="{$bill_email}" data-mpscountry="{$country}" data-mpscurrency="{$currency}" data-mpschannel="fpx" data-mpsamount="{$amount}" data-mpsorderid="{$orderid}" data-mpsbill_name="{$bill_name}" data-mpsvcode="{$vcode}"><img src="{$module_template_dir}img/op-mepsfpx.png" width="120px" height="60px"/>	</button>
	<button type="button" id="myPay"  data-toggle="molpayseamless" data-mpsmerchantid="{$mp_merchant}" data-mpsbill_desc="{$bill_desc}" data-mpsbill_email="{$bill_email}" data-mpscountry="{$country}" data-mpscurrency="{$currency}" data-mpschannel="pbb" data-mpsamount="{$amount}" data-mpsorderid="{$orderid}" data-mpsbill_name="{$bill_name}" data-mpsvcode="{$vcode}"><img src="{$module_template_dir}img/op-publicbank.png" width="120px" height="60px"/>	</button>
	<button type="button" id="myPay"  data-toggle="molpayseamless" data-mpsmerchantid="{$mp_merchant}" data-mpsbill_desc="{$bill_desc}" data-mpsbill_email="{$bill_email}" data-mpscountry="{$country}" data-mpscurrency="{$currency}" data-mpschannel="cash-711" data-mpsamount="{$amount}" data-mpsorderid="{$orderid}" data-mpsbill_name="{$bill_name}" data-mpsvcode="{$vcode}"><img src="{$module_template_dir}img/op-7e.png" width="120px" height="60px"/>	</button>
</p>