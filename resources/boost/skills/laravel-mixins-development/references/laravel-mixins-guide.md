# Laravel Mixins Reference

Complete reference for `protonemedia/laravel-mixins`. Repository: https://github.com/protonemedia/laravel-mixins

## Validation Rules

All rules implement `Illuminate\Contracts\Validation\Rule`.

### Host

Validates that a URL's host matches one of the allowed hosts. Strips `www.` prefix during comparison and adds `https://` if no scheme is present.

```php
use ProtoneMedia\LaravelMixins\Rules\Host;

$rules = [
    'website' => [Host::make(['facebook.com', 'fb.me'])],
];

// Also accepts a single host
$rules = [
    'website' => [new Host('example.com')],
];
```

### HostOrSubdomain

Like `Host`, but also allows subdomains. Uses the public suffix list to resolve registrable domains. Requires `jeremykendall/php-domain-parser`.

```php
use ProtoneMedia\LaravelMixins\Rules\HostOrSubdomain;

$rules = [
    'website' => [HostOrSubdomain::make(['example.com'])],
];

// Passes: example.com, sub.example.com, deep.sub.example.com
// Fails: other-domain.com
```

### MaxWords

Validates that a text value does not exceed a maximum word count. Words are split on spaces with empty strings filtered out.

```php
use ProtoneMedia\LaravelMixins\Rules\MaxWords;

$rules = [
    'bio' => [MaxWords::make(250)],
];
```

### InKeys

Extends Laravel's `In` rule to validate against array keys instead of values.

```php
use ProtoneMedia\LaravelMixins\Rules\InKeys;

$options = ['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'];

$rules = [
    'status' => [InKeys::make($options)],
];

// Passes: 'draft', 'published', 'archived'
// Fails: 'Draft', 'Published', 'Archived'
```

### DimensionsWithMargin

Extends Laravel's `Dimensions` rule with margin tolerance for ratio validation. Useful for ratios with repeating decimals where exact matching fails.

```php
use ProtoneMedia\LaravelMixins\Rules\DimensionsWithMargin;

$rules = [
    'image' => [DimensionsWithMargin::make()->ratio(20 / 9)->margin(1)],
];
```

### UrlWithoutScheme

Validates that a value is a valid URL, even without the `http://` or `https://` scheme. Prepends `https://` if no scheme is found before validating.

```php
use ProtoneMedia\LaravelMixins\Rules\UrlWithoutScheme;

$rules = [
    'website' => [new UrlWithoutScheme],
];

// Passes: 'example.com', 'https://example.com', 'http://example.com/page'
```

### CurrentPassword

Validates the current authenticated user's password with brute-force throttling. Note: Laravel 9+ includes a built-in `current_password` rule.

```php
use ProtoneMedia\LaravelMixins\Rules\CurrentPassword;

$rules = [
    'current_password' => [new CurrentPassword],
];
```

## String Macros

Register each macro via `Str::mixin()` in a service provider. After registration the macro is available as a static method on `Illuminate\Support\Str`.

### Compact

Truncates a long string by showing the first and last portions with a separator in the middle.

```php
use Illuminate\Support\Str;
use ProtoneMedia\LaravelMixins\String\Compact;

Str::mixin(new Compact);

Str::compact('A very long string...', 16, ' ... ');
// First 16 chars + ' ... ' + last 16 chars
```

**Signature**: `Str::compact(string $value, int $eachSide = 32, string $separator = ' ... '): string`

### HumanFilesize

Converts byte values to human-readable file sizes. Handles negative numbers and customizable precision.

```php
use Illuminate\Support\Str;
use ProtoneMedia\LaravelMixins\String\HumanFilesize;

Str::mixin(new HumanFilesize);

Str::humanFilesize(1024);         // '1.0 KB'
Str::humanFilesize(3456789);      // '3.3 MB'
Str::humanFilesize(-1024, 2);     // '-1.00 KB'
```

**Signature**: `Str::humanFilesize(int $bytes, int $precision = 1): string`

### SecondsToTime

Converts seconds to `mm:ss` or `hh:mm:ss` format. Hours are included automatically when the value is 3600 or more, or when `$omitHours` is `false`.

```php
use Illuminate\Support\Str;
use ProtoneMedia\LaravelMixins\String\SecondsToTime;

Str::mixin(new SecondsToTime);

Str::secondsToTime(10);            // '00:10'
Str::secondsToTime(580);           // '09:40'
Str::secondsToTime(3610);          // '01:00:10'
Str::secondsToTime(580, false);    // '00:09:40'
```

**Signature**: `Str::secondsToTime(int $seconds, bool $omitHours = true): string`

### Text (HTML to Plain Text)

Converts HTML to plain text. Requires `html2text/html2text`.

```php
use Illuminate\Support\Str;
use ProtoneMedia\LaravelMixins\String\Text;

Str::mixin(new Text);

Str::text('<h1>Hello</h1><p>World</p>');  // 'Hello World'
```

**Signature**: `Str::text(string $html): string`

### Url

Prepends `https://` to a URL if no scheme is present. Returns `null` for `null` input.

```php
use Illuminate\Support\Str;
use ProtoneMedia\LaravelMixins\String\Url;

Str::mixin(new Url);

Str::url('example.com');           // 'https://example.com'
Str::url('https://example.com');   // 'https://example.com'
Str::url(null);                    // null
```

**Signature**: `Str::url(?string $value): ?string`

## Blade Directives

### DecimalMoneyFormatter

Formats cents as a decimal money value. Requires `moneyphp/money`.

```php
use ProtoneMedia\LaravelMixins\Blade\DecimalMoneyFormatter;

// In a service provider
DecimalMoneyFormatter::directive();                    // Registers @decimals
DecimalMoneyFormatter::directive('decimals', 'EUR');   // Custom name and currency
```

```blade
@decimals(99)          {{-- 0.99 --}}
@decimals(100)         {{-- 1.00 --}}
@decimals(100, 'XTS') {{-- 100 (zero-decimal currency) --}}
```

**Static handler**: `DecimalMoneyFormatter::handler(int $cents, string $code = 'EUR'): string`

### IntlMoneyFormatter

Formats cents using international number formatting and locales. Requires `moneyphp/money`.

```php
use ProtoneMedia\LaravelMixins\Blade\IntlMoneyFormatter;

// In a service provider
IntlMoneyFormatter::directive();                           // @money, EUR, nl_NL
IntlMoneyFormatter::directive('money', 'USD', 'en_US');    // Custom
```

```blade
@money(99)                    {{-- € 0,99 --}}
@money(100, 'USD')            {{-- US$ 1,00 --}}
@money(100, 'USD', 'en_US')  {{-- $1.00 --}}
```

**Static handler**: `IntlMoneyFormatter::handler(int $cents, string $code = 'EUR', string $locale = 'nl_NL'): string`

## Request Utilities

### ConvertsBase64ToFiles

A trait for Form Requests that converts base64-encoded fields into `UploadedFile` instances before validation. Supports dot-notation and nested array keys.

```php
use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class StorePhotoRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    protected function base64FileKeys(): array
    {
        return [
            'photo'        => 'photo.jpg',
            'company.logo' => 'logo.png',
            'user'         => ['avatar' => 'avatar.jpg'],
        ];
    }

    public function rules(): array
    {
        return [
            'photo'        => ['required', 'image'],
            'company.logo' => ['nullable', 'image'],
            'user.avatar'  => ['nullable', 'image'],
        ];
    }
}
```

The trait hooks into `prepareForValidation()` to replace base64 strings with file instances. Both raw base64 and `data:` URI formats are supported.

## Console Commands

### GenerateSitemap

Generates `public/sitemap.xml` using `spatie/laravel-sitemap`. Requires `spatie/laravel-sitemap`.

```php
use ProtoneMedia\LaravelMixins\Commands\GenerateSitemap;

// In a service provider
GenerateSitemap::register();                      // Default: sitemap:generate
GenerateSitemap::register('custom:sitemap');       // Custom signature
```

```bash
php artisan sitemap:generate
```

## PDF Utilities

### Ghostscript

Regenerates PDF content using Ghostscript. Requires the `ghostscript` binary and `symfony/process`.

```php
use ProtoneMedia\LaravelMixins\Pdf\Ghostscript;

$gs = new Ghostscript;                  // Uses 'ghostscript' binary
$gs = new Ghostscript('/usr/bin/gs');   // Custom binary path

$cleanPdf = $gs->regeneratePdf(file_get_contents('invoice.pdf'));
file_put_contents('clean-invoice.pdf', $cleanPdf);
```

Implements `ProtoneMedia\LaravelMixins\Pdf\CanRegeneratePDF` for dependency injection.

## Blade Testing Utilities

### TestsBladeComponents

A trait for PHPUnit test cases that simplifies rendering Blade views in tests.

```php
use ProtoneMedia\LaravelMixins\Blade\TestsBladeComponents;

class BladeTest extends TestCase
{
    use TestsBladeComponents;

    public function test_component_renders()
    {
        $this->setViewPath(__DIR__ . '/views');

        $html = $this->renderView('my-component', ['name' => 'World']);

        $this->assertStringContainsString('Hello World', $html);
    }
}
```

## Database Utilities

### CountriesInDutch

Provides a list of 249 world countries with Dutch names and ISO codes.

```php
use ProtoneMedia\LaravelMixins\Database\CountriesInDutch;

$countries = (new CountriesInDutch)->all();

// Each entry: ['name' => 'Nederland', 'alpha2' => 'nl', 'alpha3' => 'nld']
```

## Registration Pattern

All features follow the same opt-in pattern — register explicitly in a service provider:

```php
// app/Providers/AppServiceProvider.php
use Illuminate\Support\Str;
use ProtoneMedia\LaravelMixins\Blade\DecimalMoneyFormatter;
use ProtoneMedia\LaravelMixins\Blade\IntlMoneyFormatter;
use ProtoneMedia\LaravelMixins\Commands\GenerateSitemap;
use ProtoneMedia\LaravelMixins\String\Compact;
use ProtoneMedia\LaravelMixins\String\HumanFilesize;
use ProtoneMedia\LaravelMixins\String\SecondsToTime;
use ProtoneMedia\LaravelMixins\String\Text;
use ProtoneMedia\LaravelMixins\String\Url;

public function boot(): void
{
    // Blade directives
    DecimalMoneyFormatter::directive();
    IntlMoneyFormatter::directive();

    // String macros
    Str::mixin(new Compact);
    Str::mixin(new HumanFilesize);
    Str::mixin(new SecondsToTime);
    Str::mixin(new Text);
    Str::mixin(new Url);

    // Commands
    GenerateSitemap::register();
}
```

Validation rules and the `ConvertsBase64ToFiles` trait do not require registration — use them directly in Form Requests or validation calls.
