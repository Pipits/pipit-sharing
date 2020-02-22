# Pipit Sharing
The Sharing app enables you to quickly and easily add sharing links to your Perch site pages without JavaScript.

## Installation
* Download the latest version of the Sharing App.
* Unzip the download
* Place the `pipit_sharing` folder in `perch/addons/apps`
* Add `pipit_sharing` to your `perch/config/apps.php`

## Requirements
* Perch or Perch Runway 3.0 or higher


## Configuration
### Website URL
By default Pipit Sharing uses `$_SERVER['HTTP_HOST']` to get the website's domain `https://example.com`.

You can tell it to get it from somewhere else. Under the Sharing app settings, there's a select field "Get website URL from". It has options:

| Option                | What it means                                                                |
|-----------------------|------------------------------------------------------------------------------|
| Settings              | From the Website URL field in the Settings.                                  |
| $_SERVER['HTTP_HOST'] | A less secure alternative, but useful if sub-domains are used.               |
| Perch configuration   | From Perch configuration file. Use the constant `SITE_URL`                   |


Defining the constant `SITE_URL`:

```php
define('SITE_URL', 'https://example.com');
```


### SSL 
If you are using the Website URL in the Settings option, whatever protocol (`http` or `https`) you set there will be used. 

If you fail to add the protocol to Website URL or use the `$_SERVER['HTTP_HOST']` option, the app will check whether you have enabled SSL in your Perch config file `perch/config.php`. If you do not have SSL enabled in your config, the app will check the page's URL at runtime and will use whatever protocol is being used.

To enable SSL in your `perch/config.php`:

```php
define('PERCH_SSL', true);
```


You can find out more about Perch SSL configuration in Perch's [documentation](https://docs.grabaperch.com/perch/configuration/ssl/).



## Usage

### In a Perch template

```html
<perch:sharing id="twitter" desc="Read: {postTitle}">
```

### In PHP

```php
pipit_sharing_link('twitter', ['desc' => 'Some text']);
```

For usage details and examples, visit the [documentation](https://grabapipit.com/pipits/apps/sharing/docs).



## Available sharing links for
* Twitter
* Facebook
* Tumblr
* Google+
* LinkedIn
* Reddit
* Pinterest
* Email
* WhatsApp



## Calendar links [experimental]
You can now generate "add to" calendar links for:

* Google
* Yahoo
* Outlook (web)
* ICS - data URI (for iCal & Outlook)



## Credits
Pipit Sharing uses the [spatie/calendar-links](https://github.com/spatie/calendar-links) package to generate calendar links.