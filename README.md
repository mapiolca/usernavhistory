# UserNavHistory for Dolibarr ERP/CRM

UserNavHistory displays the last business objects viewed by the current user in a compact navigation bar.

## Features

- configurable number of retained and displayed objects;
- native Dolibarr links, pictograms and tooltips;
- responsive single-line layout that hides the oldest entries first;
- labels limited to 15 characters followed by `...` when truncated;
- separate history for each user and Dolibarr entity;
- automatic restoration of hidden entries when the window becomes wider.

## Compatibility

- historical support: Dolibarr 16 or later and PHP 7.0 or later;
- recommended environment: Dolibarr 20 or later and PHP 8.0 or later;
- MySQL/MariaDB through the Dolibarr database abstraction.

Compatibility details for the current instance are available from **Module settings > Compatibility**.

## Installation

Copy the `usernavhistory` directory directly into the Dolibarr custom modules directory, then enable **User browsing history** from the native module administration page.

The maximum number of items is configured from the module settings. Disabling and re-enabling the module preserves this value.

## Maintainers

ATM Consulting x Les Métiers du Bâtiment.

Technical ATM service names, URLs and module ID `104555` are intentionally retained for compatibility with existing installations.

## License

GPLv3 or, at your option, any later version. See `COPYING`.
