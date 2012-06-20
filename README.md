thesocialdigits-magento
=======================

=== About
The Recommendations module provides a linkage to The Social Digits
recommendation service API, specifikation available at
http://developers.thesocialdigits.com. The feed for the API is available
at http://yourdomain.com/recommendations/feed.

=== Installation
1. Unzip tar.gz file (or copy contents of the
github repository) into magento root folder. 
2. Go to System > Configuration
> The Social Digits on the administration page to insert API key and configure module.
3. Profit

=== Configuration
==== API key 
The API key is needed in order for the recommendation service to run.
If you do not have an API key, please email us on
hello@thesocialdigits.com.

==== Language
The magento API currently does not support multilanguage setup. This
settings is used to define the language of products etc. that will be
send to the service.

==== Template
The template is used for displaying each product recommendation. It is
possible to define exactly how you wish to display the items in the
recommendations block. The template uses variables available in the json
object readable at
http://yourdomain.com/recommendations/list?products[]=1 (where 1 is a
product id) enclosed in curly brackets ({attribue})
The inputted text will be formatted as html in the recommendations
block. e.g. in order to display an image with the name below for up to
three products use the following test

<div></div>
  <a href="/index.php/{product_url}">
    <img src="{thumbnail_url}" /><br/>
    {name}
  </a>
</div>

==== Method (Not yet implemented)
Supplies which API method will be invoked. See
http://developers.thesocialdigits.com for details.

==== API options (Not yet implemented)
The API options are used to supply the jQuery plugin with
additional options to the API. The value is a json-string. Accepted
values depends on the choice of method. See
http://developers.thesocialdigits.com for acceptable values and details.

=== Miscellaneous
==== Data feed
The data feed is retrieved daily by the Social Digits API. It is
available at http://yourdomain.com/recommendations/feed (you might need
to insert /index.php after the tld).

=== List feed
The product data is retrieved through a local data feed. The url is
http://yourdomain.com/recommendations/list?products[]=n (you might need
to insert /index.php after the tld) where n is a product id. Multiple
product id's can be retrieved by supplying additional &products[]=n
arguments to the url.
