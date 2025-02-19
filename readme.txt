=== Paystack for The Events Calendar ===
Contributors: paystack, feedmymedia, krugazul, lightspeed, kaneahabagale
Tags: the events calendar, paystack, payment gateway
Requires at least: 5.8.6
Tested up to: 6.7.2
Stable tag: 1.0.7
Requires PHP: 8.0 and higher
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

The Events Calendar provides calendars, ticketing, and powerful WordPress tools to manage your events from start to finish, and with this plugin, you can accept payments for your ticket sales in Nigeria, Ghana, South Africa, and Kenya.

== Description ==

To get started, you can install the Paystack plugin from the plugins page of your WordPress installation by searching for “Paystack Gateway for The Events Calendar”.

Alternatively, you can [download the plugin from this page](https://wordpress.org/plugins/paystack-gateway-the-events-calendar/) and follow the instructions on the page to install the plugin. Please be sure to activate the plugin when you have installed it.

> ☝🏻 Ensure you have installed the Events tickets add-on on your WordPress site before installing the Paystack for The Events Calendar plugin. The Paystack Gateway for The Events Calendar plugin cannot work without Events Tickets.

After you have installed and activated the plugin, you should go to the Event Tickets **Plugin Settings** on the menu tab of your WordPress site and click the **Payments** tab. This will show a tab containing all the Payment options on your site. If you have installed the plugin correctly, you should see **Paystack** on that list. This is where you can configure the Paystack Plugin. Click *Connect to Paystack*


=== Configuring the plugin ===

Here we will discuss all the settings you'll find in your Paystack for Events Calendar Plugin and how they should be set to function properly for your plugin.

1. **Enable Paystack:** Ensure that the Enable Paystack toggle is on so that Paystack can show up on the list of Payment options during checkout
2. **Test Mode/Live Mode:** Paystack provides test parameters that allow you to simulate a transaction without using real money. If you select “Test Mode”, Paystack will be using your Test API keys to parse the payments, meaning that the orders processed then will be done with test cards, no real money is exchanged therefore no real value should be delivered.

When you are ready to go live, you can select the “Live Mode” option and the corresponding Live API keys.

3. **API keys:** [Kindly follow this link to your Paystack Dashboard Settings](https://dashboard.paystack.com/#/settings/developer) to find your API keys. Copy the right keys and enter them in the right fields in the plugin settings.
4. **Checkout Mode:** You can select the *Popup* or *Redirect* method to specify how you want the Paystack checkout to be displayed to the customer. 
5. **Webhooks:**  Copy the auto-generated URL  and save it as your webhook URL on your [Paystack Dashboard Settings, under the API Keys & Webhook](https://dashboard.paystack.com/#/settings/developer) tab.

== Frequently Asked Questions ==

= How to get your Test and Live API keys? =

There are 2 states on your dashboard: **Live Mode** and **Test Mode**. You'll see the **Test Mode/Live Mode toggle** on the **top right corner of your dashboard**.

If there is no toggle and it's just Test Mode, this means that your Paystack account has not been activated.  [Please follow this link to activate your account](https://dashboard.paystack.com/#/get-started/activate)

When you go to the Settings Page to get your API keys, please note the mode that your dashboard is in, as that will determine the keys that will be displayed. So if the dashboard is on **Test Mode,** you can only see the **Test API keys** and vice versa**.** To see the other Keys, switch the toggle from one mode to another.

== Changelog ==

= 1.0.7 =
* Compatibility with WordPress 6.7.2 and PHP 8.3.8

= 1.0.6 =
* Compatibility with WordPress 6.6.2 and PHP 8.3.8

= 1.0.5 =
* Bug fixes

= 1.0.4 =
* Compatibility with WordPress 6.2.2 and PHP 8.1.17

= 1.0 =
* First Release

== Screenshots ==

== Upgrade Notice ==

= 1.0.7 =
* Compatibility with WordPress 6.7.2 and PHP 8.3.8

= 1.0.6 =
* Compatibility with WordPress 6.6.2 and PHP 8.3.8

= 1.0.4 =
* Compatibility with WordPress 6.2.2 and PHP 8.1.17


