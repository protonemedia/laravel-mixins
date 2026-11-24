---
name: laravel-mixins-development
description: Build and work with protonemedia/laravel-mixins features including Blade money directives, validation rules, string macros, base64 file conversion, PDF regeneration, and sitemap generation.
license: MIT
metadata:
  author: Protone Media
---

# Laravel Mixins Development

## Overview
Use protonemedia/laravel-mixins to add opt-in Blade directives, validation rules, string macros, request utilities, and PDF tools to a Laravel application. Every feature must be explicitly registered — nothing auto-discovers.

## When to Activate
- Activate when code references any class under the `ProtoneMedia\LaravelMixins` namespace.
- Activate when working with validation rules like `Host`, `MaxWords`, `InKeys`, `DimensionsWithMargin`, `HostOrSubdomain`, or `UrlWithoutScheme`.
- Activate when registering string macros (`Compact`, `HumanFilesize`, `SecondsToTime`, `Text`, `Url`) via `Str::mixin()`.
- Activate when using Blade money formatters (`DecimalMoneyFormatter`, `IntlMoneyFormatter`), the `ConvertsBase64ToFiles` trait, or the `GenerateSitemap` command.

## Scope
- In scope: Blade directives, validation rules, string macros, base64-to-file request conversion, PDF regeneration, sitemap generation, Blade component testing.
- Out of scope: general Laravel validation unrelated to these rules, file uploads without base64 conversion, non-Laravel frameworks.

## Workflow
1. Identify the task (registering a directive, adding a validation rule, converting base64 files, etc.).
2. Read `references/laravel-mixins-guide.md` and focus on the relevant section.
3. Apply the patterns from the reference, keeping code minimal and Laravel-native.

## Core Concepts

### Validation Rules
All rules implement `Illuminate\Contracts\Validation\Rule` and most provide a static `make()` factory:

```php
use ProtoneMedia\LaravelMixins\Rules\MaxWords;
use ProtoneMedia\LaravelMixins\Rules\Host;
use ProtoneMedia\LaravelMixins\Rules\InKeys;

$rules = [
    'bio'     => [new MaxWords(250)],
    'website' => [Host::make(['example.com', 'example.org'])],
    'type'    => [InKeys::make(['draft' => 'Draft', 'published' => 'Published'])],
];
```

### String Macros
Register in a service provider with `Str::mixin()`:

```php
use Illuminate\Support\Str;
use ProtoneMedia\LaravelMixins\String\HumanFilesize;
use ProtoneMedia\LaravelMixins\String\SecondsToTime;

Str::mixin(new HumanFilesize);
Str::mixin(new SecondsToTime);

Str::humanFilesize(3456789);  // '3.3 MB'
Str::secondsToTime(3610);    // '01:00:10'
```

### Blade Money Directives
Register in a service provider:

```php
use ProtoneMedia\LaravelMixins\Blade\DecimalMoneyFormatter;
use ProtoneMedia\LaravelMixins\Blade\IntlMoneyFormatter;

DecimalMoneyFormatter::directive();  // @decimals(100) → 1.00
IntlMoneyFormatter::directive();     // @money(100) → € 1,00
```

### Base64 File Conversion
Use the `ConvertsBase64ToFiles` trait on a Form Request:

```php
use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class AvatarRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    protected function base64FileKeys(): array
    {
        return ['avatar' => 'avatar.jpg'];
    }
}
```

## Do and Don't

Do:
- Always register string macros via `Str::mixin(new ClassName)` in a service provider before using them.
- Always register Blade directives via the static `directive()` method in a service provider.
- Use static `make()` factory methods on validation rules when available.
- Define `base64FileKeys()` when using the `ConvertsBase64ToFiles` trait — it maps request fields to filenames.
- Register the `GenerateSitemap` command with `GenerateSitemap::register()` before running it.

Don't:
- Don't assume features are auto-registered — every mixin, directive, and command must be explicitly registered.
- Don't forget to install optional dependencies (`moneyphp/money` for Blade money directives, `html2text/html2text` for `Text` macro, `spatie/laravel-sitemap` for `GenerateSitemap`).
- Don't use `InKeys` when you need to validate against array values — it validates against array keys.
- Don't omit `toMediaCollection()` when adding media — the file won't be persisted.

## References
- `references/laravel-mixins-guide.md`
