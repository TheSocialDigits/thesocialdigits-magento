thesocialdigits-magento
=======================

## About
The Social Digits Recommendations module provides alternatives to built in
magento blocks that provides product relations by using The Social Digits API.
The API specifikation is available at http://developers.thesocialdigits.com.

The 
## Installation
1. Unzip tar.gz file (or copy contents of the
github repository) into magento root folder. 
2. Go to System > Configuration
> The Social Digits on the administration page to insert API key and configure module.
3. Profit

## Configuration
### Enabling and disabling blocks
The module provides alternatives to built in magento blocks and by default
removes or replaces them. Block override and removal is done through the layout
xml file, per default located under
app/design/frontend/default/default/layout/recommendations.xml. 

#### Crosssell block
Cross sells are displayed on the checkout cart page below the shopping cart and
replaces the default crosssell block. To disable the override comment out the
checkout_cart_index handle. e.g.
<!-- <checkout_cart_index... </checkout_cart_index> -->

#### Moresell block
The moresell block is a landing page that displays related products when adding
an item to a cart. To disable the landing page simply choose disable on the
admin configuration form.

#### Related block
The related block removes the default catalog.product.related block and adds a
block named catalog.product.recommendations. To reenabled the related block
simply remove the line  <remove name="catalog.product.related" />. To restore to
magento defaults entirely comment out the default handle. Also comment out the
<reference name="right"> block under the checkout_cart_moresell handle.

#### Search block
The search block replaces the product listing when using the search
functionality. It can be configured to use grid og list layout in the admin
configuration form.
To disable the override comment out the catalogsearch_result_index handle.

#### Upsell block
The upsell block is displayed beneath the product on product view pages. To
disable the block, comment out the catalog_product_view handle in the layout xml
file.

### Admin configuration
Under the magento system configuration (System > Configuration) a tab is located
under advanced named "The Social Digits". 6 sections are available.

The General configuration section contains options that is global for the entire setup.
One section affects each of the available blocks named previously. Settings for
the blocks can also be made directly in the recommendations.xml layout file.

### Configuring blocks from recommendations.xml
Some options are not available in the adminstration page but can be set only in
the layout xml file. Refer to the default recommendations.xml file to see what.
To set the argument options use 

    <action method="methodname">
      <argument>argumentname</argument>
      <value>argumentvalue</value>
    </action>

inside the block description where methodname is either 'setApiArgument',
'setCarouselArgument' og 'setUiArgument'. *argumentname* is an argument (see
config.xml file for argument names) and *argumentvalue* is the value you wish to
set. setApiArgument is available for alle blocks. setCarouselArgument is
available for Related, Crosssell and Upsell blocks and setUiArgument is
available for the Search and Moresell blocks.

### Miscellaneous
#### Data feed
The data feed is retrieved daily by the Social Digits API. It is
available at http://yourdomain.com/recommendations/feed (you might need
to insert /index.php after the tld).

### List feed
The product data is retrieved through a local data feed. The url is
http://yourdomain.com/recommendations/list?products[]=n (you might need
to insert /index.php after the tld) where n is a product id. Multiple
product id's can be retrieved by supplying additional &products[]=n
arguments to the url.
