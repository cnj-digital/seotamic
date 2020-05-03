# Seomatic - Statamic SEO Addon

Statmic v3 only. Due to "missing" Statamic features to make this completely automatic, there's some manual work to be done in order to use this.

```php
{{ seomatic }}
```

Generates the whole array of SEO settings:

```html
<title>My Page Title</title>
<meta name="description" content="SEO friendly description">
<link rel="canonical" href="https://mysite.com/page">
<meta name="og:url" content="https://mysite.com/page">
<meta name="og:site_name" content="Site name">
<meta name="og:title" content="My Page Title">
<meta name="og:description" content="SEO friendly description">
<meta name="og:locale" content="en_US">
<meta name="og:image" content="https://mysite.com/img/og.jpg">
...
```


## Installation
Include the package with composer:
```sh
composer require ...
```

The package requires Laravel 7+ and PHP 7.3+. It will auto register.

Copy the fieldset seomatic.yaml into your projects fieldset folder:
```sh
cp vendor/cnj.../fieldsets/seomatic.yaml resources/fieldsets/
```

Include this fieldset to all collections where you want to control SEO (those with public pages):

![How to add SEOmatic fieldset to entry](https://media.giphy.com/media/SAUAWHkR34qX105xnS/giphy.gif)

## Configuration (optional)

You can override the default options by publishing the configuration:
```
php artisan vendor:publish --provider"..." --tag=config
```

This will copy the default config file to `config/seomatic.php'.

## Usage

Usage is fairly simple and straight forward. You can visit the global Settings by following the SEOmatic link on the navigation in the CP. Make sure to follow the instructions on each field.

After this you can fine tune the output of each collection entry by editing the SEO settings under the entry's SEO tab.

### Antlers

There are several antler tags available, the easiest is to just include the do everything base tag in the head of your layout:
```
{{ seomatic }}
```

If you need more control you can manually get each part of the output by using:

```
{{ seomatic:title }}
{{ seomatic:description }}
{{ seomatic:canonical }}
```
This will return strings, so you need to wrap them in the appropriate tags, ie:
```html
<title>{{ seomatic:title }}</title>
```

Social ones will still return everything with tags

```
{{ seomatic:og }}
{{ seomatic:twitter }}
```

## Credits
This package was built by [CNJ Digital](https://www.cnj.si/).

## License
This project is licensed under the MIT License.
