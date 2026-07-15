# UserNavHistory targeted compliance audit

## Decisions applied in 1.5.0

- The module identity is now `ATM Consulting x Les Métiers du Bâtiment`.
- The historical module ID and technical ATM integration remain stable.
- The navigation bar is isolated to one entity and constrained to one responsive line.
- The native hook parent is used when available; the existing compatibility parent remains for older Dolibarr versions.
- Compatibility and About information use native Dolibarr administration pages.

## Remaining work

### Priority 1 - compatibility evidence

- Add automated installation and browser coverage for Dolibarr 16, 19, 20 and the current supported release.
- Add a PHP 7.0 syntax job alongside the primary PHP 8.x jobs so historical support is continuously proven.
- Add a Dolibarr-aware PHPStan configuration and stubs without weakening the selected analysis level.

### Priority 2 - legacy code reduction

- Review the remaining ModuleBuilder boilerplate in `UserNavHistory` and remove unused object-card, document and import/export patterns.
- Replace historical object-resolution special cases with native core capabilities only when the minimum supported Dolibarr version makes this possible.
- Add focused automated tests for URL-to-main-menu resolution and object type compatibility.

### Priority 3 - release tooling

- Add repeatable package validation for root layout, `ChangeLog.md` casing, translations and forbidden files.
- Add browser-level assertions for responsive restoration, Unicode truncation and Multicompany isolation.
