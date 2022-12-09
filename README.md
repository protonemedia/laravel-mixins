# Laravel Mixins

[![Latest Version on Packagist](https://img.shields.io/packagist/v/protonemedia/laravel-mixins.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-mixins)
[![Build Status](https://img.shields.io/travis/protonemedia/laravel-mixins/master.svg?style=flat-square)](https://travis-ci.org/protonemedia/laravel-mixins)
[![Quality Score](https://img.shields.io/scrutinizer/g/protonemedia/laravel-mixins.svg?style=flat-square)](https://scrutinizer-ci.com/g/protonemedia/laravel-mixins)
[![Total Downloads](https://img.shields.io/packagist/dt/protonemedia/laravel-mixins.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-mixins)
[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen)](https://plant.treeware.earth/protonemedia/laravel-mixins)

## Sponsor this package!

❤️ We proudly support the community by developing Laravel packages and giving them away for free. If this package saves you time or if you're relying on it professionally, please consider [sponsoring the maintenance and development](https://github.com/sponsors/pascalbaljet). Keeping track of issues and pull requests takes time, but we're happy to help!

## Laravel Splade

**Did you hear about Laravel Splade? 🤩**

It's the *magic* of Inertia.js with the *simplicity* of Blade. [Splade](https://github.com/protonemedia/laravel-splade) provides a super easy way to build Single Page Applications using Blade templates. Besides that magic SPA-feeling, it comes with more than ten components to sparkle your app and make it interactive, all without ever leaving Blade.

## Requirements

* PHP 8.0+
* Laravel 9.0

## Installation

You can install the package via composer:

```bash
composer require protonemedia/laravel-mixins
```

There's no Service Provider or automatic discovery/registration of anything. All features are opt-in.

## Contents

#### Blade Directives
* [Decimal Money Formatter](#decimal-money-formatter)
* [Intl Money Formatter](#intl-money-formatter)

#### Console Commands
* [Generate Sitemap](#generate-sitemap)

#### Validation Rules
* [Current password](#current-password)
* [Dimensions With Margin](#dimensions-with-margin)
* [Host](#host)
* [In Keys](#in-keys)
* [Max Words](#max-words)
* [URL Without Scheme](#url-without-scheme)

#### String Macros
* [Compact](#compact)
* [Human Filesize](#human-filesize)
* [Text](#text)
* [URL](#url)
* [Seconds to time](#seconds-to-time)

#### PDF
* [PDF Regeneration](#pdf-regeneration)

#### Request
* [Convert Base64 input data to files](#convert-base64-input-data-to-files)

## Blade Directives

You can register Blade Directives by calling the `directive` method on the class. You can change the name of a directive with the optional first argument.

### Decimal Money Formatter

*Note: This directive requires the `moneyphp/money` package.*

Register the directive, for example by adding it to your `AppSerivceProvider`:

```php
ProtoneMedia\LaravelMixins\Blade\DecimalMoneyFormatter::directive();
```

You can customize the name of the directive and the default currency code:

```php
ProtoneMedia\LaravelMixins\Blade\DecimalMoneyFormatter::directive('decimals', 'EUR');
```

The first argument of the directive is the amount in cents. The second optional parameter is the currency.

```blade
// 0.99
@decimals(99)

// 1.00
@decimals(100)

// 100
@decimals(100, 'XTS')
```

### Intl Money Formatter

*Note: This directive requires the `moneyphp/money` package.*

Register the directive, for example by adding it to your `AppSerivceProvider`:

```php
ProtoneMedia\LaravelMixins\Blade\IntlMoneyFormatter::directive();
```

You can customize the name of the directive, the default currency code and the locale:

```php
ProtoneMedia\LaravelMixins\Blade\IntlMoneyFormatter::directive('money', 'EUR', 'nl_NL');
```

The first argument of the directive is the amount in cents. The optional second parameter is the currency. The optional third parameter is the locale.

```blade
// € 0,99
@money(99)

// € 1,00
@money(100)

// US$ 1,00
@money(100, 'USD')

// 1 000,00 $US
@money(100, 'USD', 'fr')
```

## Commands

### Generate Sitemap

*Note: This command requires the `spatie/laravel-sitemap` package.*

You can register the command by adding it to your `App\Console\Kernel` file, or by calling the `register` method on the class.

```php
ProtoneMedia\LaravelMixins\Commands\GenerateSitemap::register();
```

You can also set a custom signature:

```php
ProtoneMedia\LaravelMixins\Commands\GenerateSitemap::register('generate-sitemap');
```

It generates a sitemap of your entire site and stores in in the `public` folder as `sitemap.xml`.

```bash
php artisan sitemap:generate
```

## Validation Rules

### Current password

Passes if the value matches the password of the authenticated user.

```php
$rule = new ProtoneMedia\LaravelMixins\Rules\CurrentPassword;
```

As of Laravel 9, this validation rule is [built-in](https://laravel.com/docs/9.x/validation#rule-current-password).

### Dimensions With Margin

Extension of the [Dimensions rule](https://laravel.com/docs/master/validation#rule-dimensions) with a `margin` option. Handy when you're working with ratios with repeating decimals.

```php
use ProtoneMedia\LaravelMixins\Rules\DimensionsWithMargin;

$rule = DimensionsWithMargin::make()->ratio(20 / 9)->margin(1),
```

### Host

Verifies if the URL matches the given hosts.

```php
use ProtoneMedia\LaravelMixins\Rules\Host;

$rule = Host::make(['facebook.com', 'fb.me']);
```

### In Keys

Verifies if the given key or index exists in the array.

```php
use ProtoneMedia\LaravelMixins\Rules\InKeys;

$rule = new InKeys(['laravel' => 'Laravel Framework', 'tailwindcss' => 'Tailwind CSS framework']);

// same as

use Illuminate\Validation\Rules\In;

$rule = new In(['laravel', 'tailwindcss']);
```

### Max Words

Passes if the values contains no more words than specified.

```php
use ProtoneMedia\LaravelMixins\Rules\MaxWords;

$rule = MaxWords::make(250);
```

### URL Without Scheme

Passes if the URL is valid, even without a scheme.

```php
$rule = new ProtoneMedia\LaravelMixins\Rules\UrlWithoutScheme;
```

## String macros

You can add new method by using the mixins.

### Compact

```php
Str::mixin(new ProtoneMedia\LaravelMixins\String\Compact);

$string = "Hoe simpeler hoe beter. Want hoe minder keuze je een speler laat, hoe groter de kans dat hij het juiste doet.";

// Hoe simpeler hoe beter. Want hoe ... de kans dat hij het juiste doet.
echo Str::compact($string);
```

It has an optional second argument to specify the length on each side. With the optional third argument, you can specify the sepeator.

```php
// Hoe simpeler hoe - het juiste doet.
echo Str::compact($string, 16, ' - ');
```

### Human Filesize

Converts a filesize into a human-readable version of the string.

```php
Str::mixin(new ProtoneMedia\LaravelMixins\String\HumanFilesize);

$size = 3456789;

// '3.3 MB'
Str::humanFilesize($size));
```

### Text

*Note: This macro requires the `html2text/html2text` package.*

Converts HTML to plain text.

```php
Str::mixin(new ProtoneMedia\LaravelMixins\String\Text);

$html = "<h1>Protone Media</h1>";

// Protone Media
Str::text($html);
```

### URL

Prepends `https://` if the scheme is missing from the given URL.

```php
Str::mixin(new ProtoneMedia\LaravelMixins\String\Url);

$url = "protone.media";

// https://protone.media
Str::url($url);
```

### Seconds to time

Converts seconds to a 'mm:ss' / 'hh:mm:ss' format.

```php
Str::mixin(new ProtoneMedia\LaravelMixins\String\SecondsToTime);

Str::secondsToTime(10); // 00:10
Str::secondsToTime(580); // 09:40
Str::secondsToTime(3610); // 01:00:10

// force 'hh:mm:ss' format, even under an hour:
Str::secondsToTime(580, false); // 00:09:40
```

## PDF Regeneration

*Note: Requires the `symfony/process` package.*

Regenerates the PDF content with Ghostscript.

```php
$ghostscript = new ProtoneMedia\LaravelMixins\Pdf\Ghostscript;

$regeneratedPdf = $ghostscript->regeneratePdf(
    file_get_contents('/uploads/invoice.pdf')
);
```

You can specify the path of the `ghostscript` binary as well:

```php
$ghostscript = new Ghostscript('gs-binary');
```

## Convert Base64 input data to files

Add the `ConvertsBase64ToFiles` trait and `base64FileKeys` method to your form request.

```php
use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class ImageRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    protected function base64FileKeys(): array
    {
        return [
            'jpg_image' => 'Logo.jpg',
        ];
    }

    public function rules()
    {
        return [
            'jpg_image' => ['required', 'file', 'image'],
        ];
    }
}
```

Now you can get the files like regular uploaded files:

```php
$jpgFile = $request->file('jpg_image');

// Logo.jpg
$jpgFile->getClientOriginalName();
```

This trait supports nested data as well. You can either reference the keys by a nested array, or with a [dotted notation](https://laravel.com/docs/master/helpers#method-array-dot):

```php
class ImageRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    protected function base64FileKeys(): array
    {
        return [
            'company.logo' => 'Logo.jpg',
            'user' => [
                'avatar' => 'Avatar.jpg',
            ],
        ];
    }
}
```

Want to know more about this trait? Check out the [blog post](https://protone.media/blog/convert-and-store-base64-encoded-files-in-laravel-use-validation-rules-and-access-the-decoded-files-from-the-request-instance).

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Other Laravel packages

* [`Laravel Analytics Event Tracking`](https://github.com/protonemedia/laravel-analytics-event-tracking): Laravel package to easily send events to Google Analytics.
* [`Laravel Blade On Demand`](https://github.com/protonemedia/laravel-blade-on-demand): Laravel package to compile Blade templates in memory.
* [`Laravel Cross Eloquent Search`](https://github.com/protonemedia/laravel-cross-eloquent-search): Laravel package to search through multiple Eloquent models.
* [`Laravel Eloquent Scope as Select`](https://github.com/protonemedia/laravel-eloquent-scope-as-select): Stop duplicating your Eloquent query scopes and constraints in PHP. This package lets you re-use your query scopes and constraints by adding them as a subquery.
* [`Laravel FFMpeg`](https://github.com/protonemedia/laravel-ffmpeg): This package provides an integration with FFmpeg for Laravel. The storage of the files is handled by Laravel's Filesystem.
* [`Laravel Form Components`](https://github.com/protonemedia/laravel-form-components): Blade components to rapidly build forms with Tailwind CSS Custom Forms and Bootstrap 4. Supports validation, model binding, default values, translations, includes default vendor styling and fully customizable!
* [`Laravel Paddle`](https://github.com/protonemedia/laravel-paddle): Paddle.com API integration for Laravel with support for webhooks/events.
* [`Laravel Verify New Email`](https://github.com/protonemedia/laravel-verify-new-email): This package adds support for verifying new email addresses: when a user updates its email address, it won't replace the old one until the new one is verified.
* [`Laravel WebDAV`](https://github.com/protonemedia/laravel-webdav): WebDAV driver for Laravel's Filesystem.
* [`Laravel Eloquent Where Not`](https://github.com/protonemedia/laravel-eloquent-where-not): This Laravel package allows you to flip/invert an Eloquent scope, or really any query constraint.

### Security

If you discover any security related issues, please email pascal@protone.media instead of using the issue tracker.

## Credits

- [Pascal Baljet](https://github.com/protonemedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Treeware

This package is [Treeware](https://treeware.earth). If you use it in production, then we ask that you [**buy the world a tree**](https://plant.treeware.earth/protonemedia/laravel-mixins) to thank us for our work. By contributing to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.
