{*
* MOLPay Template for Payment Return Hook
*}
{if $status == '00'}
<p>
    <img src="modules/molpay/img/ok.png" align="left">
    <strong>{l s='Your order on %s is complete.' sprintf=$shop_name mod='molpay'}</strong>
    <br />
    <br />
    <strong>{l s='Your order will be sent soon.' mod='molpay'}</strong>
    <br />
    <br />
    {l s='You can view your order history by following this link:' mod='molpay'}
    <a href="{$link->getPageLink('history', true)}">{l s='Order History' mod='molpay'}</a>
    <br />
    <br />
    {l s='For any questions or for further information, please contact our' mod='molpay'} 
    <a href="{$link->getPageLink('contact', true)}">{l s='customer support' mod='molpay'}</a>.
    <br />
    <br />
    <strong>{l s='Thank you!' mod='molpay'}</strong>
</p>
{else}
<p class="warning">
    <img src="modules/molpay/img/not_ok.png" align="left">{l s='We noticed a problem with your order. Please, contact us as soon as possible' mod='molpay'}.
    <br />
    <br />
    {l s='Your order will not be shipped until the issue is addressed' mod='molpay'} 
    <br />
    <br />
</p>
{/if}