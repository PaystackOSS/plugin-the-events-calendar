
![Email (1).jpg](https://res.cloudinary.com/drps6uoe4/image/upload/v1669979254/Email__1_tyiofm.jpg)


##### Table of Contents
- [Before you start](#before-you-start)
- [Using Paystack for The Events Calendar](#using-paystack-for-the-events-calendar)
  * [Configuring the plugin](#configuring-the-plugin)
  * [How to get your Test and Live API keys](#how-to-get-your-test-and-live-api-keys)
  * [Additional Settings](#additional-settings)
- [Paystack on Checkout](#paystack-on-checkout)


The Events Calendar provides calendars, ticketing, and powerful WordPress tools to manage your events from start to finish, and with this plugin, you can accept payments for your ticket sales in Nigeria, Ghana, South Africa, and Kenya.



## Before you start

The first thing you need to do is install *The Events Calendar* plugin. You can do this from the plugins page of your WordPress installation, or [you can download it manually from this link](https://wordpress.org/plugins/the-events-calendar/) and follow the instructions on the page to install the plugin. 

![Screenshot 2022-12-02 at 10.54.50.png](https://res.cloudinary.com/drps6uoe4/image/upload/v1669979255/Screenshot_2022-12-02_at_10.54.50_rhiz1u.png)

After you have installed and activated the Events Calendar plugin, go to the **Events Add-on** page, then select the Events Tickets add-on that makes it possible to sell tickets and manage attendees for events.

![Screenshot 2022-11-30 at 09.31.29.png](https://res.cloudinary.com/drps6uoe4/image/upload/v1669979255/Screenshot_2022-11-30_at_09.31.29_fega5i.png)

Alternatively, [you can download it manually from this link](https://wordpress.org/plugins/event-tickets/) and follow the instructions on the page to install it. 

## Using Paystack for The Events Calendar

To get started, you can install the Paystack plugin from the plugins page of your WordPress installation by searching for “Paystack Gateway for The Events Calendar”.

 Alternatively, you can [download the plugin from this page](https://wordpress.org/plugins/paystack-gateway-the-events-calendar/) and follow the instructions on the page to install the plugin. Please be sure to activate the plugin when you have installed it.

> ☝🏻 Ensure you have installed the Events tickets add-on on your WordPress site before installing the Paystack for The Events Calendar plugin. The             Paystack Gateway for The Events Calendar plugin cannot work without Events Tickets.



After you have installed and activated the plugin, you should go to the Event Tickets **Plugin Settings** on the menu tab of your WordPress site and click the **Payments** tab. This will show a tab containing all the Payment options on your site. If you have installed the plugin correctly, you should see **Paystack** on that list. This is where you can configure the Paystack Plugin. Click *Connect to Paystack*

![Screenshot 2022-11-30 at 09.49.36.png](https://res.cloudinary.com/drps6uoe4/image/upload/v1669979254/Screenshot_2022-11-30_at_09.49.36_k5ezp3.png)

### Configuring the plugin

Here we will discuss all the settings you'll find in your Paystack for Events Calendar Plugin and how they should be set to function properly for your plugin.

![Screenshot 2022-11-30 at 10.31.10.png](https://res.cloudinary.com/drps6uoe4/image/upload/v1669979255/Screenshot_2022-11-30_at_10.31.10_vywy42.png)

1. **Enable Paystack:** Ensure that the Enable Paystack toggle is on so that Paystack can show up on the list of Payment options during checkout
2. **Test Mode/Live Mode:** Paystack provides test parameters that allow you to simulate a transaction without using real money. If you select “Test Mode”, Paystack will be using your Test API keys to parse the payments, meaning that the orders processed then will be done with test cards, no real money is exchanged therefore no real value should be delivered.

When you are ready to go live, you can select the “Live Mode” option and the corresponding Live API keys.

3. **API keys:** [Kindly follow this link to your Paystack Dashboard Settings](https://dashboard.paystack.com/#/settings/developer) to find your API keys. Copy the right keys and enter them in the right fields in the plugin settings.
4. **Checkout Mode:** You can select the *Popup* or *Redirect* method to specify how you want the Paystack checkout to be displayed to the customer. 
5. **Webhooks:**  Copy the auto-generated URL  and save it as your webhook URL on your [Paystack Dashboard Settings, under the API Keys & Webhook](https://dashboard.paystack.com/#/settings/developer) tab.

![https://support.paystack.com/hc/article_attachments/360025328420/Screenshot_2021-04-19_at_12.55.41.png](https://support.paystack.com/hc/article_attachments/360025328420/Screenshot_2021-04-19_at_12.55.41.png)

### How to get your Test and Live API keys

There are 2 states on your dashboard: **Live Mode** and **Test Mode**. You'll see the **Test Mode/Live Mode toggle** on the **top right corner of your dashboard**.

If there is no toggle and it's just Test Mode, this means that your Paystack account has not been activated.  [Please follow this link to activate your account](https://dashboard.paystack.com/#/get-started/activate)

When you go to the Settings Page to get your API keys, please note the mode that your dashboard is in, as that will determine the keys that will be displayed. So if the dashboard is on **Test Mode,** you can only see the **Test API keys** and vice versa**.** To see the other Keys, switch the toggle from one mode to another.

### Additional Settings

When you are setting up a New Event, you can also specify if you want to enable split payment for the tickets

![Screenshot 2022-11-30 at 11.18.52.png](https://res.cloudinary.com/drps6uoe4/image/upload/v1669981361/Screenshot_2022-12-02_at_12.42.19_zf4hmx.png)

You can either select “One Partner” which implies a single split payment or “Multiple Partners” for multi-split payment, then you enter a valid subaccount code *e.g* *SUB_rtn5vd8e* or split code *e.g* *SPL_9wolt5nM* in the second input field. 

Check out this guide on how to create [subaccounts and split codes from your Paystack dashboard](https://support.paystack.com/hc/en-us/articles/360009881460-How-do-I-split-a-payment-across-multiple-bank-accounts-).

## Paystack on Checkout

To confirm you’ve done all of the above settings correctly, go to Event Tickets **Plugin Settings** on the menu tab of your WordPress site and click on **Payments → Tickets Commerce** 

Here you should find the Paystack gateway with the tag “Enabled for checkout”

![Screenshot 2022-12-02 at 11.18.35.png](https://res.cloudinary.com/drps6uoe4/image/upload/v1669979256/Screenshot_2022-12-02_at_11.18.35_wu1kao.png)

If you find this tag, you’re good to go! and ready to accept payments for your tickets through Paystack 🚀

![ezgif-4-fe90033503.gif](https://res.cloudinary.com/drps6uoe4/image/upload/v1669979254/ezgif-4-fe90033503_dxmrrb.gif)

If you do not find this tag on the Paystack Option, please go through the settings again and make sure that:

- You switched the  "**Enable Paystack"** toggle on
- You entered the API Keys correctly
- If you still have any issues after doing this, please reach out to support@paystack.com and we’d be glad to help!
