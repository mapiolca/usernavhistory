# UserNavHistory maintenance decisions

This file supplements the shared Dolibarr module development instructions for this module.

## Supported environments

- Historical compatibility is retained from Dolibarr 16 and PHP 7.0 while the code and bundled backports remain compatible.
- Dolibarr 20 or later with PHP 8.0 or later is the recommended and primary validation environment.
- Features unavailable in older Dolibarr versions must use the bundled compatibility layer or be reported on the Compatibility page.

## Approved identity exceptions

- The approved family and editor label is `ATM Consulting x Les Métiers du Bâtiment`.
- Module ID `104555` is retained because it belongs to the historical reserved range of the original publisher and is already deployed under this identity.
- Technical identifiers and services such as `TechATM`, `ATM_TECH_URL`, ATM domains, cache paths and logo names remain stable.
- These decisions are explicit exceptions to the usual `Les Métiers du Bâtiment` family and `450000-450999` ID rules.

## Module-specific invariants

- Navigation history is private to the current user and current entity; it is never shared through Multicompany.
- The newest history entry must remain visible when the responsive bar hides entries.
- Native `getNomUrl()` output, rights and tooltips must be preserved.
- `USERNAVHISTORY_MAX_ELEMENT_NUMBER` must survive module disable/enable cycles.
- No module code or generated user file may be written outside this module or the native Dolibarr document directories.
