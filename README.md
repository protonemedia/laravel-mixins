# [WIP] Laravel Mixins
## Do not use in production yet!

[![Latest Version on Packagist](https://img.shields.io/packagist/v/protonemedia/laravel-mixins.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-mixins)
[![Build Status](https://img.shields.io/travis/protonemedia/laravel-mixins/master.svg?style=flat-square)](https://travis-ci.org/protonemedia/laravel-mixins)
[![Quality Score](https://img.shields.io/scrutinizer/g/protonemedia/laravel-mixins.svg?style=flat-square)](https://scrutinizer-ci.com/g/protonemedia/laravel-mixins)
[![Total Downloads](https://img.shields.io/packagist/dt/protonemedia/laravel-mixins.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-mixins)

## Installation

You can install the package via composer:

```bash
composer require protonemedia/laravel-mixins
```

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

### Decimal Money Formatter

This directive requires the `moneyphp/money` package.

Add to your `AppSerivceProvider`:

```php
DecimalMoneyFormatter::directive();
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

This directive requires the `moneyphp/money` package.

Add to your `AppSerivceProvider`:

```php
IntlMoneyFormatter::directive();
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

This command requires the `spatie/laravel-sitemap` package.

Generates a sitemap of your entire site and stores in in the `public` folder as `sitemap.xml`.

```bash
php artisan sitemap:generate
```

## Validation Rules

### Current password

Passes if the value matches the password of the authenticated user.

```php
$rule = new CurrentPassword;
```

### Dimensions With Margin

Extension of the [Dimensions rule](https://laravel.com/docs/master/validation#rule-dimensions) with a `margin` option. Handy when you're working with ratios with repeating decimals.

```php
$rule = DimensionsWithMargin::make()->ratio(20 / 9)->margin(1),
```

### Host

Verifies if the URL matches the given hosts.

```php
$rule = Host::make(['facebook.com', 'fb.me']);
```

### Max Words

Passes if the values contains no more words than specified.

```php
$rule = MaxWords::make(250);
```

### URL Without Scheme

Passes if the URL is valid, even without a scheme.

```php
$rule = new UrlWithoutScheme;
```

## String macros

You can add new method by using the mixins.

### Compact

```php
Str::mixin(new Compact);

$string = "Hoe simpeler hoe beter. Want hoe minder keuze je een speler laat, hoe groter de kans dat hij het juiste doet.";

// Hoe simpeler hoe beter. Want hoe ... de kans dat hij het juiste doet.
echo Str::compact($string);
```

### Human Filesize

```php
Str::mixin(new HumanFilesize);

$size = 3456789;

// '3.3 MB'
Str::humanFilesize($size));
```

### Text

This macro requires the `html2text/html2text` package.

Converts HTML to plain text.

```php
Str::mixin(new Text);

$html = "<h1>Protone Media</h1>";

// Protone Media
Str::text($html);
```

### URL

```php
Str::mixin(new Url);

$url = "protone.media";

// https://protone.media
Str::url($url);
```

## PDF Regeneration

Requires the `symfony/process` package.

Regenerates the PDF content with Ghostscript.

```php
$ghostscript = new Ghostscript;

$regeneratedPdf = $ghostscript->regeneratePdf(
    file_get_contents('/uploads/invoice.pdf')
);
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
