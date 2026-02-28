# Laravel Mixins Reference

Complete reference for `protonemedia/laravel-mixins.`. Full documentation: https://github.com/protonemedia/laravel-mixins#readme

Complete reference for `protonemedia/laravel-mixins`.

Primary docs: https://github.com/protonemedia/laravel-mixins#readme

## Philosophy

Everything in this package is **opt-in**.

- There is **no** service provider that auto-registers macros/directives.
- You must register the parts you want in your application (typically in `AppServiceProvider` or your Console Kernel).

## Installation

```bash
composer require protonemedia/laravel-mixins
```

## Blade directives

Register directives by calling `::directive()` on the directive class (usually in `AppServiceProvider::boot()`).

### Decimal Money Formatter

Requires: `moneyphp/money`.

Register:

```php
use ProtoneMedia\LaravelMixins\Blade\DecimalMoneyFormatter;

DecimalMoneyFormatter::directive();
```

Customize directive name and default currency:

```php
DecimalMoneyFormatter::directive('decimals', 'EUR');
```

Usage:

```blade
@decimals(99)        {{-- 0.99 --}}
@decimals(100)       {{-- 1.00 --}}
@decimals(100, 'XTS')
```

### Intl Money Formatter

Requires: `moneyphp/money`.

Register:

```php
use ProtoneMedia\LaravelMixins\Blade\IntlMoneyFormatter;

IntlMoneyFormatter::directive();
```

Customize directive name, currency, locale:

```php
IntlMoneyFormatter::directive('money', 'EUR', 'nl_NL');
```

Usage:

```blade
@money(99)              {{-- € 0,99 --}}
@money(100)             {{-- € 1,00 --}}
@money(100, 'USD')
@money(100, 'USD', 'fr')
```

## Console commands

### Generate Sitemap

Requires: `spatie/laravel-sitemap`.

Register (Kernel or anywhere appropriate):

```php
use ProtoneMedia\LaravelMixins\Commands\GenerateSitemap;

GenerateSitemap::register();
```

Customize signature:

```php
GenerateSitemap::register('generate-sitemap');
```

Default signature from README:

```bash
php artisan sitemap:generate
```

It generates a sitemap and stores `sitemap.xml` in `public/`.

## Validation rules

### CurrentPassword

```php
$rule = new ProtoneMedia\LaravelMixins\Rules\CurrentPassword;
```

Note: as of Laravel 9, this rule is built-in.

### DimensionsWithMargin

Extends Laravel’s `dimensions` rule with a margin (handy for repeating-decimal ratios):

```php
use ProtoneMedia\LaravelMixins\Rules\DimensionsWithMargin;

$rule = DimensionsWithMargin::make()->ratio(20 / 9)->margin(1);
```

### Host

Validate URL host against a whitelist:

```php
use ProtoneMedia\LaravelMixins\Rules\Host;

$rule = Host::make(['facebook.com', 'fb.me']);
```

### InKeys

Validate that a value exists as a key/index in a set.

```php
use ProtoneMedia\LaravelMixins\Rules\InKeys;

$rule = new InKeys([
    'laravel' => 'Laravel Framework',
    'tailwindcss' => 'Tailwind CSS framework',
]);
```

(Comparable to `Illuminate\Validation\Rules\In` but keyed.)

### MaxWords

```php
use ProtoneMedia\LaravelMixins\Rules\MaxWords;

$rule = MaxWords::make(250);
```

### UrlWithoutScheme

Accept URLs that omit a scheme:

```php
$rule = new ProtoneMedia\LaravelMixins\Rules\UrlWithoutScheme;
```

## String macros (Str::mixin)

Register macros with `Str::mixin(new ...)`.

### Compact

```php
use Illuminate\Support\Str;
use ProtoneMedia\LaravelMixins\String\Compact;

Str::mixin(new Compact);

echo Str::compact($string);
echo Str::compact($string, 16, ' - ');
```

### HumanFilesize

```php
use ProtoneMedia\LaravelMixins\String\HumanFilesize;

Str::mixin(new HumanFilesize);

Str::humanFilesize(3456789); // '3.3 MB'
```

### Text (HTML → plain text)

Requires: `html2text/html2text`.

```php
use ProtoneMedia\LaravelMixins\String\Text;

Str::mixin(new Text);

Str::text('<h1>Protone Media</h1>');
```

### Url (ensure scheme)

```php
use ProtoneMedia\LaravelMixins\String\Url;

Str::mixin(new Url);

Str::url('protone.media'); // https://protone.media
```

### SecondsToTime

```php
use ProtoneMedia\LaravelMixins\String\SecondsToTime;

Str::mixin(new SecondsToTime);

Str::secondsToTime(10);     // 00:10
Str::secondsToTime(580);    // 09:40
Str::secondsToTime(3610);   // 01:00:10
Str::secondsToTime(580, false); // 00:09:40
```

## PDF: regeneration via Ghostscript

Requires: `symfony/process`.

```php
use ProtoneMedia\LaravelMixins\Pdf\Ghostscript;

$ghostscript = new Ghostscript;

$regenerated = $ghostscript->regeneratePdf(
    file_get_contents('/uploads/invoice.pdf')
);
```

Custom binary:

```php
$ghostscript = new Ghostscript('gs-binary');
```

## Request: convert base64 input data to files

Add `ConvertsBase64ToFiles` to a FormRequest and define `base64FileKeys()`.

```php
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class ImageRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    protected function base64FileKeys(): array
    {
        return ['jpg_image' => 'Logo.jpg'];
    }
}
```

Then access decoded files via `$request->file(...)`:

```php
$file = $request->file('jpg_image');
$file->getClientOriginalName();
```

Supports nested keys (array or dot notation):

```php
protected function base64FileKeys(): array
{
    return [
        'company.logo' => 'Logo.jpg',
        'user' => ['avatar' => 'Avatar.jpg'],
    ];
}
```

## Common pitfalls / gotchas

- **Optional dependencies:** several features require extra packages (`moneyphp/money`, `spatie/laravel-sitemap`, `html2text/html2text`, `symfony/process`). Don’t assume they exist.
- **Registration is explicit:** forgetting to call `::directive()` or `Str::mixin()` is the #1 cause of “method not found / directive not defined”.
- **Keep things opt-in:** avoid changes that globally register behavior automatically.
