MOLPay Prestashop Plugin
=====================

MOLPay Plugin for Prestashop Shopping Cart develop by MOLPay technical team.


Notes
-----

MOLPay Sdn. Bhd. is not responsible for any problems that might arise from the use of this module. 
Use at your own risk. Please backup any critical data before proceeding. For any query or 
assistance, please email support@molpay.com 


Installations for Prestashop version 1.5.6.x/1.6.0.x and above
------------------------------------------------------

- Download or clone this repository.

- Upload or copy those file and folder into your Prestashop root folder.

- Please ensure the file permission is correct. It's recommended to CHMOD to 775.
(Skip this if your magento is not hosted not in UNIX environment).

- Login as Prestashop Store Admin, click to `Modules` > `Modules` menu.

- Search MOLPay Malaysia Online Payment Gateway. Click the install button if it's not being install yet.

- After installation is completed, Click on the `Configure` text to open the MOLPay back-end configuration.

- Fill in the Merchant ID and Merchant Vkey. If you ain't get it. Please contact our support.

- Click on the MERCHANT PROFILE menu. Fill in return URL and Callback URL with your shopping cart URL.
```Return URL : http://xxxxxxxxxxxxxx/index.php?fc=module&module=molpay&controller=validation```
```Callback URL : http://xxxxxxxxxxxxxx/index.php?fc=module&module=molpay&controller=callback```  
*Replace xxxxxxxxxxxxxx with your shoppingcart domain

- Save the configuration.

## Notes

* Please test with sandbox account first before using a real one
* If you encounter any problem, Submit an issued or mail to our support@molpay.com


Contribution
------------

You can contribute to this plugin by sending the pull request to this repository.


Issues
------------

Submit issue to this repository or email to our support@molpay.com


Support
-------

Merchant Technical Support / Customer Care : support@molpay.com <br>
Sales/Reseller Enquiry : sales@molpay.com <br>
Marketing Campaign : marketing@molpay.com <br>
Channel/Partner Enquiry : channel@molpay.com <br>
Media Contact : media@molpay.com <br>
R&D and Tech-related Suggestion : technical@molpay.com <br>
Abuse Reporting : abuse@molpay.com
