---
name: laravel-mixins-development
description: Build and work with protonemedia/laravel-mixins features including Blade directives, validation rules, string macros, PDF regeneration, and base64 file conversion.
license: MIT
metadata:
  author: ProtoneMedia
---

# Laravel Mixins Development

## Overview
Use `protonemedia/laravel-mixins` to add opt-in Blade directives, validation rules, string macros, console commands, and request utilities to a Laravel application. Everything must be registered explicitly — there is no auto-discovery.

## When to Activate
- Activate when adding, configuring, or using this package in application code (controllers, jobs, commands, tests, config, routes, Blade, etc.).
- Activate when code references `ProtoneMedia\LaravelMixins` classes or registered directives/macros.
- Activate when the user wants to use Blade money formatters, custom validation rules, string macros, PDF regeneration, or base64 file conversion.

## Scope
- In scope: documented public API usage, registration patterns, and common integration recipes.
- Out of scope: modifying this package’s internal source code unless the user explicitly says they are contributing to the package.

## Workflow
1. Identify the task (install/setup, directive registration, rule usage, macro registration, etc.).
2. Read `references/laravel-mixins-guide.md` and focus on the relevant section.
3. Apply the documented patterns and keep examples minimal and Laravel-native.

## Core Concepts

### Registration (opt-in)
All features must be explicitly registered, typically in `AppServiceProvider::boot()`:

```php
use ProtoneMedia\LaravelMixins\Blade\DecimalMoneyFormatter;
use ProtoneMedia\LaravelMixins\String\HumanFilesize;
use Illuminate\Support\Str;

// Blade directive
DecimalMoneyFormatter::directive();

// String macro
Str::mixin(new HumanFilesize);
```

### Validation Rules
```php
use ProtoneMedia\LaravelMixins\Rules\MaxWords;
use ProtoneMedia\LaravelMixins\Rules\Host;

$rules = [
    ‘bio’ => [new MaxWords(250)],
    ‘website’ => [Host::make([‘example.com’])],
];
```

### Base64 File Conversion
```php
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class ImageRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    protected function base64FileKeys(): array
    {
        return [‘jpg_image’ => ‘Logo.jpg’];
    }
}
```

## Do and Don’t

Do:
- Always call `::directive()` or `Str::mixin()` to register features before using them.
- Check that optional dependencies are installed before using features that require them (e.g., `moneyphp/money`, `spatie/laravel-sitemap`).
- Provide examples that compile in a typical Laravel project.

Don’t:
- Don’t assume directives or macros are auto-registered — registration is always explicit.
- Don’t invent undocumented methods/options; stick to the docs and reference.
- Don’t suggest changing package internals unless the user explicitly wants to contribute upstream.

## References
- `references/laravel-mixins-guide.md`
