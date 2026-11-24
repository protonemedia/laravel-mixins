{{-- Laravel Mixins Guidelines for AI Code Assistants --}}
{{-- Source: https://github.com/protonemedia/laravel-mixins --}}
{{-- License: MIT | (c) Protone Media --}}

## Laravel Mixins

- `protonemedia/laravel-mixins` provides a collection of opt-in Blade directives, validation rules, string macros, request utilities, and PDF tools for Laravel applications.
- Always activate the `laravel-mixins-development` skill when working with any class under the `ProtoneMedia\LaravelMixins` namespace, including validation rules (`Host`, `MaxWords`, `InKeys`, etc.), string macros (`Compact`, `HumanFilesize`, `SecondsToTime`, etc.), Blade money formatters, the `ConvertsBase64ToFiles` trait, or the `GenerateSitemap` command.
