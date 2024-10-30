=== LTL Freight Quotes - Worldwide Express Edition ===
Contributors: enituretechnology
Tags: eniture. worldwide express,LTL freight rates, LTL freight quotes,shipping rates
Requires at least: 6.4
Tested up to: 6.6.2
Stable tag: 5.0.19
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Real-time LTL freight quotes from Worldwide Express. Fifteen day free trial.

== Description ==

Worldwide Express (wwex.com ) is a third party logistics company that gives its customers access
to UPS and over 60 LTL freight carriers through a single account relationship. The plugin retrieves 
the LTL freight rates you negotiated Worldwide Express, takes action on them according to the plugin settings, and displays the 
result as shipping charges in your WooCommerce shopping cart. To establish a Worldwide Express account call 1-800-758-7447.

**Key Features**

* Three rating options: Cheapest, Cheapest Options and Average.
* Custom label results displayed in the shopping cart.
* Control the number of options displayed in the shopping cart.
* Display transit times with returned quotes.
* Restrict the carrier list to omit specific carriers.
* Product specific freight classes.
* Support for variable products.
* Option to determine a product's class by using the built in density calculator.
* Option to include residential delivery fees.
* Option to include fees for lift gate service at the destination address.
* Option to mark up quoted rates by a set dollar amount or percentage.

**Requirements**

* WooCommerce 6.4 or newer.
* A Worldwide Express account number.
* Your username and password to Worldwide Express's online shipping system.
* Your Worldwide Express web services authentication key.
* An API key from Eniture Technology.

== Installation ==

**Installation Overview**

Before installing this plugin you should have the following information handy:

* Your Worldwide Express account number.
* Your username and password to Worldwide Express's online shipping system.
* Your Worldwide Express web services authentication key.

If you need assistance obtaining any of the above information, contact your local Worldwide Express office
or call the [Worldwide Express](http://wwex.com) corporate headquarters at 1-800-758-7447.

A more extensive and graphically illustrated set of instructions can be found on the *Documentation* tab at
[eniture.com](https://eniture.com/woocommerce-worldwide-express-ltl-freight-plugin/).

**1. Install and activate the plugin**
In your WordPress dashboard, go to Plugins => Add New. Search for "eniture ltl freight quotes", and click Install Now.
After the installation process completes, click the Activate Plugin link to activate the plugin.

**2. Get an API key from Eniture Technology**
Go to [Eniture Technology](https://eniture.com/woocommerce-worldwide-express-ltl-freight-plugin/) and pick a
subscription package. When you complete the registration process you will receive an email containing your API key and
your login to eniture.com. Save your login information in a safe place. You will need it to access your customer dashboard
where you can manage your API keys and subscriptions. A credit card is not required for the free trial. If you opt for the free
trial you will need to login to your [Eniture Technology](http://eniture.com) dashboard before the trial period expires to purchase
a subscription to the API key. Without a paid subscription, the plugin will stop working once the trial period expires.

**3. Establish the connection**
Go to WooCommerce => Settings => Speedfreight. Use the *Connection* link to create a connection to your Worldwide Express
account.

**4. Identify the carriers**
Go to WooCommerce => Settings => Speedfreight. Use the *Carriers* link to identify which carriers you want to include in the 
dataset used as input to arrive at the result that is displayed in your cart. Including all carriers is highly recommended.

**5. Select the plugin settings**
Go to WooCommerce => Settings => Speedfreight. Use the *Quote Settings* link to enter the required information and choose
the optional settings.

**6. Define warehouses and drop ship locations**
Go to WooCommerce => Settings => Speedfreight. Use the *Warehouses* link to enter your warehouses and drop ship locations.  You should define at least one warehouse, even if all of your products ship from drop ship locations. Products are quoted as shipping from the warehouse closest to the shopper unless they are assigned to a specific drop ship location. If you fail to define a warehouse and a product isn’t assigned to a drop ship location, the plugin will not return a quote for the product. Defining at least one warehouse ensures the plugin will always return a quote.

**7. Enable the plugin**
Go to WooCommerce => Settings => Shipping. Click on the Shipping Zones link. Add a US domestic shipping zone if one doesn’t already exist. Click the “+” sign to add a shipping method to the US domestic shipping zone and choose SEFL from the list.

**8. Configure your products**
Assign each of your products and product variations a weight, Shipping Class and freight classification. Products shipping LTL freight should have the Shipping Class set to “LTL Freight”. The Freight Classification should be chosen based upon how the product would be classified in the NMFC Freight Classification Directory. If you are unfamiliar with freight classes, contact the carrier and ask for assistance with properly identifying the freight classes for your  products.

== Frequently Asked Questions ==

= What happens when my shopping cart contains products that ship LTL and products that would normally ship FedEx or UPS? =

If the shopping cart contains one or more products tagged to ship LTL freight, all of the products in the shopping cart 
are assumed to ship LTL freight. To ensure the most accurate quote possible, make sure that every product has a weight 
and dimensions recorded.

= What happens if I forget to identify a freight classification for a product? =

In the absence of a freight class, the plugin will determine the freight classification using the density calculation method. 
To do so the products weight and dimensions must be recorded.

= Why was the invoice I received from Worldwide Express more than what was quoted by the plugin? =

One of the shipment parameters (weight, dimensions, freight class) is different, or additional services (such as residential 
delivery, lift gate, delivery by appointment and others) were required. Compare the details of the invoice to the shipping 
settings on the products included in the shipment. Consider making changes as needed. Remember that the weight of the packaging 
materials,such as a pallet, is included by the carrier in the billable weight for the shipment.

= How do I find out what freight classification to use for my products? =

Contact your local Worldwide Express office for assistance. You might also consider getting a subscription to ClassIT offered 
by the National Motor Freight Traffic Association (NMFTA). Visit them online at classit.nmfta.org.

= How do I get a Worldwide Express account number? =

Worldwide Express is a US national franchise organization. Check your phone book for local listings or call its corporate 
office at 1-800-758-7447 and ask how to contact the sales office serving your area.

= Where do I find my Worldwide Express username and password? =

Usernames and passwords to Worldwide Express’s online shipping system are issued by Worldwide Express. Contact the Worldwide 
Express office servicing your account to request them. If you don’t have a Worldwide Express account, contact the Worldwide 
Express corporate office at 1-800-758-7447.

= Where do I get my Worldwide Express authentication key? =

You can can request an authentication key by logging into Worldwide Express’s online shipping system (speedship.wwex.com) and 
navigating to Services > Web Services. An authentication key will be emailed to you, usually within the hour.

= How do I get an API key for my plugin? =

You must register your installation of the plugin, regardless of whether you are taking advantage of the trial period or 
purchased an API key outright. At the conclusion of the registration process an email will be sent to you that will include the 
API key. You can also login to eniture.com using the username and password you created during the registration process 
and retrieve the API key from the My API keys tab.

= How do I change my plugin API key from the trail version to one of the paid subscriptions? =

Login to eniture.com and navigate to the My API keys tab. There you will be able to manage the licensing of all of your 
Eniture Technology plugins.

= How do I install the plugin on another website? =

The plugin has a single site API key. To use it on another website you will need to purchase an additional API key. 
If you want to change the website with which the plugin is registered, login to eniture.com and navigate to the My API keys tab. 
There you will be able to change the domain name that is associated with the API key key.

= Do I have to purchase a second API key for my staging or development site? =

No. Each API key allows you to identify one domain for your production environment and one domain for your staging or 
development environment. The rate estimates returned in the staging environment will have the word “Sandbox” appended to them.

= Why isn’t the plugin working on my other website? =

If you can successfully test your credentials from the Connection page (WooCommerce > Settings > Speedfreight > Connections) 
then you have one or more of the following licensing issues:

1) You are using the API key on more than one domain. The API keys are for single sites. You will need to purchase an additional API key.
2) Your trial period has expired.
3) Your current API key has expired and we have been unable to process your form of payment to renew it. Login to eniture.com and go to the My API keys tab to resolve any of these issues.

== Screenshots ==

1. Carrier inclusion page
2. Quote settings page
3. Quotes displayed in cart

== Changelog ==

= 5.0.19 =
* Update:Introduced In-store pickup fee. 

= 5.0.18 =
* Update: Compatibility with the new Pallet Packaging algorithm

= 5.0.17 =
* Update: Compatibility with WooCommerce version 9.2.3
* Fix: Corrected the tab navigation order in the plugin.

= 5.0.16 =
* Update: Updated connection tab according to wordpress requirements 

= 5.0.15 =
* Fix:Fixed Test Connection error message. 

= 5.0.14 =
* Update: Introduced a shipping rule for the liftgate weight limit
* Update: Introduced backup rate feature
* Update: Introduced error management feature
* Fix: Corrected the tab navigation order in the plugin
* Fix: Fixed the display of shipping rates on draft orders.

= 5.0.13 =
* Update: Compatibility with WordPress version 6.5.1
* Update: Compatibility with PHP version 8.2.0

= 5.0.12 =
* Update: Display "Free Shipping" at checkout when handling fee in the quote settings is  -100% .
* Fix: Added validation on the handling fee field 

= 5.0.11 =
* Update: Changed text on "Enable Log" description text. 

= 5.0.10 =
* Fix: Fixed the plugin is not generating shipping costs for WooCommerce backend.

= 5.0.9 =
* Update: Changed required plan from standard to basic for Show Delivery Estimates on the checkout.
* Update: Implement a parameter to differentiate between old and new API requests in the logs.

= 5.0.8 =
* Update: Compatibility with WooCommerce HPOS(High-Performance Order Storage)

= 5.0.7 =
* Update: Modified shipping method name from "LTL Freight" to "WWE LTL Freight Quotes".  

= 5.0.6 =
* Update: Adds GLS carrier in the carriers tab
* Fix: Fixed empty shipping log requests issue
* Fix: Fixed a special characters issue in the error message on the connection tab  

= 5.0.5 =
* Update: Removed extra data from logs. 

= 5.0.4 =
* Update: Add programming to switch the Worldwide account to New/Old API.   
* Update: Changed endpoint URL for logs.  

= 5.0.3 =
* Update: Added programming to automatically switch Worldwide Express account on new API.

= 5.0.2 =
* Update: Allow international shipping rates.

= 5.0.1 =
* Update: Added a new carrier "CrossCountry Freight Solutions"
* Fix: Fixed an issue while inherent parent shipping class to variants.

= 5.0.0 =
* Update: Introduced Worldwide Express new API OAuth process with client ID and client secret.

= 4.14.11 =
* Update: Modified expected delivery message at front-end from "Estimated number of days until delivery" to "Expected delivery by".
* Fix: Allow space characters in the city field in the warehouse tab.
* Fix: Tab navigation in the warehouse form.

= 4.14.10 =
* Fix: Fixed residential address detection not working

= 4.14.9 =
* Update: Text changes in FreightDesk.Online coupon expiry notice

= 4.14.8 =
* Fix: Fixed a issue while inherent parent shipping class to variants   

= 4.14.7 =
* Update: Introduced Limited access delivery feature.

= 4.14.6 =
* Update: Added compatibility with "Address Type Disclosure" in Residential address detection 

= 4.14.5 =
* Update: Removed product level and origin level markup from order detail widget.

= 4.14.4 =
* Update: Show "product level markup" and "origin level markup" on order detail widget
* Fix: Fixed "product level markup" and "origin level markup" adding in hold a terminal
* Fix: Fixed duplicate quotes issues on hold at the terminal for a single shipment
* Fix: Fixed the issue of adding a handling fee for single shipment on the average rating method

= 4.14.3 =
* Update: Compatibility with WordPress version 6.1
* Update: Compatibility with WooCommerce version 7.0.1

= 4.14.2 =
* Fix: Fixes in the release 4.14.0

= 4.14.1 =
* Update: Added a new carrier "CrossCountry Freight Solutions"
* Update: Show selected shipping option at checkout on order detail page. 

= 4.14.0 =
* Update: Added origin level markup. 
* Update: Added product level markup. 

= 4.13.5 =
* Update: Compatibility with "WooCommerce Payments" plugin
* Fix: Fixed waraning issue on carriers tab

= 4.13.4 =
* Update: Compatibility with custom work lonestargrillz[Ticket:763445899]

= 4.13.3 =
* Update: Introduced connectivity from the plugin to FreightDesk.Online using Company ID
* Update: Compatibility with WordPress version 6.0

= 4.13.2 =
* Update: By default mark all carriers checked. 

= 4.13.1 =
* Fix: Fixed quoting issue for composite products.

= 4.13.0 =
* Update: Introduced coupon code for freightdesk.online and validate-addresses.com.

= 4.12.8 =
* Fix: Fixed php warnings on weight and dimensions.
* Fix: Fixed Cron scheduling.

= 4.12.7 =
* Update: Compatibility with Crowlernation custom work Flat Shipping. 
* Fix: Fixed support link. 

= 4.12.6 =
* Update: Compatibility with PHP version 8.1.
* Update: Compatibility with WordPress version 5.9.
* Update: Added support for ticket#732783627

= 4.12.5 =
* Update: Product level markup support in Rental Products Addon.

= 4.12.4 =
* Update: In-store pickup and local delivery support in Rental Products Addon.

= 4.12.3 =
* Update: Relocation of "Enable logs" option.

= 4.12.2 =
* Update: Relocation of NMFC Number field along with freight class.

= 4.12.1 =
Fix: issue fixed in data analysis.

= 4.12.0 =
Update: Added features, Multiple Pallet Packaging and data analysis.

= 4.11.8 =
* Fix: issue fixed in release 4.11.7

= 4.11.7 =
* Update: Changes in compatibility with custom work addon(Product level markup).

= 4.11.6 =
* Update: Compatibility with PHP version 8.0.
* Update: Compatibility with WordPress version 5.8.
* Fix: Corrected product page URL in connection settings tab.

= 4.11.5 =
* Update: Included compatibility with custom work addon(Product level markup).

= 4.10.5 =
* Update: Removed truncate functionality for carriers table on plugin deactivate.

= 4.9.5 =
* Update: Added feature "Weight threshold limit".
* Update: Added feature In-store pickup with terminal information.

= 4.7.5 =
* Update: Added weight threshold feature for LTL freight on the quote settings page.
* Update: Added feature to show In-store pickup and local delivery with terminal address.
* Update: Added CTBV, MTJG as new carriers

= 4.5.3 =
* Update: Cut off time, Micro-warehouse, CSV columns updated, NMFC number addon compatibility, Shippable addon compatibility.

= 4.4.3 =
* Update: Added compatibility with WP 5.7, compatibility with shippable ad-don, compatibility with account number ad-don fields showing on the checkout page.

= 4.4.2 =
* Update: Sync orders to freightdesk.online

= 4.4.0 =
* Update: Compatibility with WordPress 5.6

= 4.3.12 =
* Fix: Fixed issue with liftgate as option

= 4.3.11 =
* Fix: Fixed database warning issue and insurance plan issue on product page.

= 4.3.10 =
* Fix: Fixed bug on order detail widget 

= 4.3.9 =
* Fix: Addressed customer issue ticket#212080299

= 4.3.8 =
* Update: Added insurance feature

= 4.3.7 =
* Update: Virtual products support for freightdesk.online

= 4.3.6 =
* Fix: Fixed CSS issue

= 4.3.5 =
* Update: Compatibility with WordPress 5.5

= 4.3.4 =
* Fix: Fixed conflict of the plugin with order actions dropdown on order detail page.

= 4.3.3 =
* Fix: In case of multi Shipment, generate order detail widget and shipping data for freightdesk.online even shipment fails to get rates.

= 4.3.2 =
* Fix: Fixes in compatibility with shipping solution freightdesk.online

= 4.3.1 =
* Fix: Fixed suppress live rates according to local delivery setting

= 4.3.0 =
* Update: Compatibility with shipping solution freightdesk.online

= 4.2.5 =
* Update: Compatibility with WordPress 5.4

= 4.2.4 =
* Update: Ignore items with given Shipping Class(es). In case Freight triggers on exceeding parcel threshold.

= 4.2.3 =
* Update: Introduced features, weight of handing unit, maximum weight per handling unit and exclude shipping class

= 4.2.2 =
* Fix: Compatibility with Small Package Quotes – Worldwide Express Edition V4.1.1 

= 4.2.1 =
* Update: Introduce weight of handling unit on quote setting page.

= 4.2.0 =
* Update: Introduce product nesting property feature and auto correct origin city name for warehouses tab.

= 4.1.0 =
* Update: This update introduces: 1 Compatibility for the WooCommerce Measurement Price Calculator plugin; 4) Customizable error message in the event the plugin is unable to retrieve rates from UPS; 5) Hold at terminal feautre on shopping cart

= 4.0.3 =
* Fix: Fix PHP warnings on count function 

= 4.0.2 =
* Update: Compatibility with WordPress 5.1

= 4.0.1 =
* Fix: Identify one warehouse and multiple drop ship locations in basic plan.

= 4.0.0 =
* Update: Introduced new features and Basic, Standard and Advanced plans.

= 3.1.1 =
* Fix: Added compatibility for Opcache and corrected an invalid entry appearing in the error logs.

= 3.1.0 =
* Update: Compatibility with WordPress 5.0

= 3.0.3 =
* Update: Updated to incorporate structural changes to the WWE API 

= 3.0.2 =
* Update: Included new carrier 'Averitt' 

= 3.0.1 =
* Fix: Fixed UI issue on quote settings page.

= 3.0.0 =
* Update: Plugin compatibility with the Residential Address Detection plugin.

= 2.1.3 =
* Fix: Corrected user guide link.

= 2.1.2 =
* Fix: Fixed an issue with the setting that controlled the display of alternate shipping methods.

= 2.1.1 =
* Fix: Fixed issue with new reserved word in PHP 7.1

= 2.1.0 =
* Update: Compatibility with WordPress 4.9

= 2.0.13 =
* Fix:   Multiplication of product quantity with product weight

= 2.0.12 =
* Update:  Ignore product that don’t have shipping data ((freight class or length, width, height) & weight)

= 2.0.11 =
* Update: Standardization of shipping parameter units of measure for API requests

= 2.0.10 =
* Update:  Compatibility with WooCommerce 3.0

= 2.0.9 =
* Update: Enhancements to carrier specific results.

= 2.0.8 =
* Update: Move handling fee from shipment level to cart level. 

= 2.0.7 =
* Update: Drop ship locations on variations 

= 2.0.6 =
* Fix:  Fixed jQuery error in warehouse tab

= 2.0.5 =
* Fix:  Set default param in calculate_shipping

= 2.0.4 =
* Fix:  Fix for average rate calculation 

= 2.0.3 =
* Update: Compatibility with WooCommerce 2.6

= 2.0.2 =
* Fix:  Fix for query error in previous update

= 2.0.1 =
* Update:  Allow negative handling fee.

= 2.0 =
* Update:  Introduced multiple warehouses and drop ship locations.

= 1.2.6 =
* Fix: Fixes an issues that arose when the order contained different billing and shipping addresses.

= 1.2.5 =
* Update: Show LTL rates on parcel shipment, in case weight exceeds 150lb.

= 1.2.4 =
* Update: All communication sent and received is through Secured Communications.

= 1.2.3 =
* Update: Clarified test connection response for an expired API key.

= 1.2.2 =
* Update: Shipping zone added

= 1.2.1 =
* Update: Compatibility WordPress 4.6

= 1.1.3 =
* Update: Backward compatibility PHP 5.3 

= 1.1.2 =
* Update: WooCommerce 2.6x compatibility added

= 1.1.1 =
* Fix: Detect if WooCommerce was installed.

= 1.1.0 =
* New feature: Automatically return LTL quote if parcel shipment weight exceeds 150 lbs.

= 1.0 =
* Initial release.

== Upgrade Notice ==

