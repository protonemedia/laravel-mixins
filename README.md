# Laravel Mixins

[![Latest Version on Packagist](https://img.shields.io/packagist/v/protonemedia/laravel-mixins.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-mixins)
[![Build Status](https://img.shields.io/travis/pascalbaljetmedia/laravel-mixins/master.svg?style=flat-square)](https://travis-ci.org/pascalbaljetmedia/laravel-mixins)
[![Quality Score](https://img.shields.io/scrutinizer/g/pascalbaljetmedia/laravel-mixins.svg?style=flat-square)](https://scrutinizer-ci.com/g/pascalbaljetmedia/laravel-mixins)
[![Total Downloads](https://img.shields.io/packagist/dt/protonemedia/laravel-mixins.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-mixins)

## Installation

You can install the package via composer:

```bash
composer require protonemedia/laravel-mixins
```

## Usage

### Blade Directives

#### Decimal Money Formatter

```blade
// 0.99
@decimals(99)

// 1.00
@decimals(100)

// 100
@decimals(100, 'XTS')
```

#### Intl Money Formatter

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

### Validation Rules

#### Current password

Passes if the value matches the password of the authenticated user.

```php
$rule = new CurrentPassword;
```

#### Dimensions With Margin

Extension of the [Dimensions rule](https://laravel.com/docs/master/validation#rule-dimensions) with a `margin` option. Handy when you're working with ratios with repeating decimals.

```php
$rule = DimensionsWithMargin::make()->ratio(20 / 9)->margin(1),
```

#### Host

Verifies if the URL matches the given hosts.

```php
$rule = Host::make(['facebook.com', 'fb.me']);
```

#### Max Words

Passes if the values contains no more words than specified.

```php
$rule = MaxWords::make(250);
```

#### URL Without Scheme

Passes if the URL is valid, even without a scheme.

```php
$rule = new UrlWithoutScheme;
```

### String macros

#### Compact

```php
$string = "Hoe simpeler hoe beter. Want hoe minder keuze je een speler laat, hoe groter de kans dat hij het juiste doet.";

// Hoe simpeler hoe beter. Want hoe ... de kans dat hij het juiste doet.
echo Str::compact($string);
```

#### Human Filesize

```php
$size = 3456789;

// '3.3 MB'
Str::humanFilesize($size));
```

#### URL

```php
$url = "protone.media";

// https://protone.media
Str::url($url);
```


### PDF Regeneration

Regenerates the PDF content with Ghostscript.

```php
$ghostscript = new Ghostscript;

$regeneratedPdf = $ghostscript->regeneratePdf(
    file_get_contents('/uploads/invoice.pdf')
);
```

### Convert Base64 input data to files

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

- [Pascal Baljet](https://github.com/pascalbaljetmedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
