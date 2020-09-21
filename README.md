# Seotamic - Statamic SEO Addon

Statmic v3 only. Automatically adds a SEO tab to all your collection entries where you can fine tune SEO for every entry.

```php
{{ seotamic }}
```

Generates the whole array of SEO settings:

```html
<title>My Page Title</title>
<meta name="description" content="SEO friendly description" />
<link rel="canonical" href="https://mysite.com/page" />
<meta property="og:url" content="https://mysite.com/page" />
<meta property="og:site_name" content="Site name" />
<meta property="og:title" content="My Page Title" />
<meta property="og:description" content="SEO friendly description" />
<meta property="og:locale" content="en_US" />
<meta property="og:image" content="https://mysite.com/img/og.jpg" />
...
```

## Version 2 changes

Version 2 has breaking changes. If you update from version 1, your global settings will not be transfered, you need to manually copy the old files to the content directory.

## Installation

Include the package with composer:

```sh
composer require cnj/seotamic
```

The package requires Laravel 7+ and PHP 7.3+. It will auto register.


The SEO section tab will appear on all collection entries automatically.

## Configuration (optional)

You can override the default options by publishing the configuration:

```
php artisan vendor:publish --provider="Cnj\Seotamic\ServiceProvider" --tag=config
```

This will copy the default config file to `config/seotamic.php'.

If you need to change the default assets container, make sure to apply the change in the Blueprints as well.

## Usage

Usage is fairly simple and straight forward. You can visit the global Settings by following the Seotamic link on the navigation in the CP. Make sure to follow the instructions on each field.

After this you can fine tune the output of each collection entry by editing the SEO settings under the entry's SEO tab.

### Antlers

There are several antler tags available, the easiest is to just include the do everything base tag in the head of your layout:

```
{{ seotamic }}
```

If you need more control you can manually get each part of the output by using:

```
{{ seotamic:title }}
{{ seotamic:description }}
{{ seotamic:canonical }}
```

This will return strings, so you need to wrap them in the appropriate tags, ie:

```html
<title>{{ seotamic:title }}</title>
```

Social ones will still return everything with tags

```
{{ seotamic:og }}
{{ seotamic:twitter }}
```

## Credits

This package was built by [CNJ Digital](https://www.cnj.si/).

## License

This project is licensed under the MIT License.
