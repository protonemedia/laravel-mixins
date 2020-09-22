# Laravel Mixins

[![Latest Version on Packagist](https://img.shields.io/packagist/v/protonemedia/laravel-mixins.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-mixins)
[![Build Status](https://img.shields.io/travis/protonemedia/laravel-mixins/master.svg?style=flat-square)](https://travis-ci.org/protonemedia/laravel-mixins)
[![Quality Score](https://img.shields.io/scrutinizer/g/protonemedia/laravel-mixins.svg?style=flat-square)](https://scrutinizer-ci.com/g/protonemedia/laravel-mixins)
[![Total Downloads](https://img.shields.io/packagist/dt/protonemedia/laravel-mixins.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-mixins)

## Installation

This package requires PHP 7.4 and Laravel 6.0 or higher. You can install the package via composer:

```bash
composer require protonemedia/laravel-mixins
```

To keep the overhead of this package to a minimum, every feature is opt-in. There's no Service Provider or automatic discovery/registration of anything.

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
* [Max Words](#max-words)
* [URL Without Scheme](#url-without-scheme)

#### String Macros
* [Compact](#compact)
* [Human Filesize](#human-filesize)
* [Text](#text)
* [URL](#url)

#### PDF
* [PDF Regeneration](#pdf-regeneration)

#### Request
* [Convert Base64 input data to files](#convert-base64-input-data-to-files)

## Blade Directives

Blade Directives can be registered by calling the `directive` method on the class. You can change the name of a directive with the optional first argument.

### Decimal Money Formatter

*Note: This directive requires the `moneyphp/money` package.*

Register the directive, for example by adding it to your `AppSerivceProvider`:

```php
ProtoneMedia\LaravelMixins\Blade\DecimalMoneyFormatter::directive();
```

You can customize the name of the directive and the default currency code:

```php
DecimalMoneyFormatter::directive('decimals', 'EUR');
```

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
IntlMoneyFormatter::directive('money', 'EUR', 'nl_NL');
```

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
use ProtoneMedia\LaravelMixins\Commands\GenerateSitemap;

GenerateSitemap::register();
```

You can also set a custom signature:

```php
GenerateSitemap::register('generate-sitemap');
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

It has an optional second argument to specify the length on each side.

```php
// Hoe simpeler hoe ... het juiste doet.
echo Str::compact($string, 16);
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

Prepends `https://` is the scheme is missing from the given URL.

```php
Str::mixin(new ProtoneMedia\LaravelMixins\String\Url);

$url = "protone.media";

// https://protone.media
Str::url($url);
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

Add the `ConvertsBase64ToFiles` trait and `base64ImageKeys` method to your form request.

```php
use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class ImageRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    protected function base64ImageKeys(): array
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

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email pascal@protone.media instead of using the issue tracker.

## Credits

- [Pascal Baljet](https://github.com/protonemedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
