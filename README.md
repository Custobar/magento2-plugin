Custobar_CustoConnector
=========

CustoConnector is used to send usage statistics from the Magento installation to the Custobar API

## Release Information

*CustoConnector for M2*

## System Requirements

* PHP 7
* Magento 2

NOTE: Module requires that the Magento [cron](http://devdocs.magento.com/guides/v2.0/config-guide/cli/config-cli-subcommands-cron.html) is correctly configured and running 

## Installation

Add a `repositories` object to the `composer.json`
 
         "repositories": [
            {
                "type": "git",
                "url": "https://github.com/Custobar/magento2-plugin"
            }
         ]


Add requirement `custobar/custobar_m2_custoconnector`

        {
            "require": {
                "custobar/custobar_m2_custoconnector": "dev-master"
            }
        }
        
Run `php bin/magento module:enable Custobar_CustoConnector` and `php bin/magento setup:upgrade`            

### After installing 

Go to `Stores / Settings / Configuration / Custobar / CustoConnector / Configuration` to setup the module.

Input the supplied `Client identifier` and `Api key`. 

At least one `Allowed websites to send data from` needs to be selected. 

You can also add the `Custobar tracking script` what can be generated at `https://clientidentifier.custobar.com/tracking-script/`.

Copy and paste the code *without* the following lines:

```js
// remove these
cstbrConfig.productId = 'place_product_id_here';
cstbrConfig.customerId = 'place_customer_id_here';
```
Remember to flush Magento's caches after changing the values. 

Go to `System / Custobar / Custobar Status` to view the status or start an initial scheduling.

Magento crons run above the /pub folder so If you are running Magento frontend from the pub folder and get media urls with "pub" and they can't be accessed then update settings:
 
- Under Stores / Settings / Configuration / General / Web / Base Urls and Base Urls (Secure):

  Change the `Base URL for User Media Files` to: 
 
  `{{unsecure_base_url}}media/`
  
  Change the `Secure Base URL for User Media Files` to: 
  
  `{{secure_base_url}}media/`


## Update notices

### 2.0.0:
- Module refactored to better follow Magento standards and improve general code quality
- Admin view for logs added
- Tracked models admin config removed and replaced with a more straightforward solution

### 1.1.1:

- fixes null product issue
- remove urls for product that aren't visible by them self as magento gives a non nice url for them

### 1.1.0: 

- Maps select/dropdown attribute labels to Custobar correctly

  Example `manufacturer>brand` 
  
- New fields for Magento\Catalog\Model\Product>products 

  custobar_child_ids>mage_child_ids and custobar_parent_ids>mage_parent_ids
  
  Update tracked models with (remember to keep your own modifications)
  
  ```
  Magento\Catalog\Model\Product>products:
    name>title;
    sku>external_id;
    custobar_minimal_price>minimal_price;
    custobar_price>price;
    type_id>mage_type;
    configurable_min_price>my_configurable_min_price;
    custobar_attribute_set_name>type;
    custobar_category>category;
    custobar_category_id>category_id;
    custobar_image>image;
    custobar_product_url>url;
    custobar_special_price>sale_price;
    description>description;
    custobar_language>language;
    custobar_store_id>store_id;
    custobar_child_ids>mage_child_ids;
    custobar_parent_ids>mage_parent_ids,
   Magento\Customer\Model\Customer>customers:
    firstname>first_name;
    lastname>last_name;
    id>external_id;
    email>email;
    custobar_telephone>phone_number;
    custobar_street>street_address;
    custobar_city>city;
    custobar_postcode>zip_code;
    custobar_country_id>country;
    custobar_created_at>date_joined;
    store_id>store_id,
   Magento\Customer\Model\Address>*Magento\Customer\Model\Customer:
    customer_id>id,
   Magento\Sales\Model\Order>sales:
    custobar_state>sale_state;
    increment_id>sale_external_id;
    customer_id>sale_customer_id;
    custobar_created_at>sale_date;
    customer_email>sale_email;
    store_id>sale_shop_id;
    custobar_discount>sale_discount;
    custobar_grand_total>sale_total;
    custobar_payment_method>sale_payment_method;
    custobar_order_items>magento__items,
   Magento\Newsletter\Model\Subscriber>events:
    subscriber_email>email;
    customer_id>customer_id;
    custobar_status>type;
    custobar_date>date;
    store_id>store_id
   ```
