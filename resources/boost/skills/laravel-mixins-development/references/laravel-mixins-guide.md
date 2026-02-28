# laravel-mixins development guide

For full documentation, see the README: https://github.com/protonemedia/laravel-mixins#readme

## At a glance
Collection of opt-in Laravel **mixins/macros** (Blade directives, validation rules, string macros, commands, etc.).

## Local setup
- Install dependencies: `composer install`
- Keep the dev loop package-focused (avoid adding app-only scaffolding).

## Testing
- Run: `composer test` (preferred) or the repository’s configured test runner.
- Add regression tests for bug fixes.

## Notes & conventions
- Everything is opt-in: don't introduce side effects via auto-registration.
- Keep macro/directive names and signatures stable.
- Document new mixins in the README and cover with tests.
