# Heck I Rent A

A minimal and attractive interface for Quikr Rental services. 
Visit at [http://qkr.r93.in/](http://qkr.r93.in/)

## Setting up Instructions
### Dependencies
* Apache2
* PHP5
* CURL
* SlimPHP
* Composer

### Deployment
* Copy over Virtual Host from `config/` to `/etc/apache2/sites-available`. Edit the values and enable it.
* Copy config.sample.php to config.php and fill in the APP ID, SECRET and other required parameters.
* open `http://qkr.local/`

## Features 
* Fetches Ads within 5-10 Km radius of user. 
* Provides direct links to dealer's quikr Ads
* Synonyms of products have been smartly classified to fit in API supported methods and parameters
* Auto realization of location using HTML5 geolocation. Facility to give custom location using google maps autocomplete API.
* Map with direction to nearest dealer from user location.
* Facility to change dealer and get new directions instantly.

## Performace 
* Fast performance owing to the elimination of costly DB queries, since the API is robust enough to give the information.

## Technologies used
* Backend using PHP powered with [slim](http://www.slimframework.com/) framework. 
* Front end: HTML5, CSS, JS and JQuery.
* External API used: Google maps
