# CHANGELOG USERNAVHISTORY FOR [DOLIBARR ERP CRM](https://www.dolibarr.org)

## [Unreleased]

## 1.5.0 - 2026-07-15

- NEW: responsive single-line navigation history that hides the oldest entries when space is limited.
- NEW: recalculate the responsive history after mobile portrait/landscape orientation changes.
- NEW: truncate labels longer than 15 characters while preserving native links, pictograms and tooltips.
- NEW: add native Compatibility and About administration tabs.
- CHANGED: identify the module as ATM Consulting x Les Métiers du Bâtiment while retaining module ID 104555 and ATM technical services.
- FIX: isolate history cleanup and display by entity in Multicompany environments.
- FIX: escape navigation lookup criteria and preserve the configured history limit when the module is disabled.
- COMPAT: retain Dolibarr 16+ and PHP 7.0+ support through the bundled compatibility layer; recommend Dolibarr 20+ and PHP 8.0+.

## 1.4
- FIX : css margin-right in 20+ Dolibarr versions - 16/12/2025 - 1.4.1
- FIX : css margin changes in 20+ Dolibarr versions + background color - 09/12/2025 - 1.4.0

## 1.3
- FIX : COMPAT V23 - *02/12/2025* - 1.3.5
- FIX : DA026830 : (regression) link not displayed for some objects (like users) because of failure to load the class - *28/08/2025* - 1.3.4
- FIX : DA026531 view last visited link - *11/08/2025* - 1.3.3  
- FIX : COMPAT V22 - *02/07/2025* - 1.3.2
- FIX : FATAL PHP compatibility 7.0  - *24/07/2024* - 1.3.1
- FIX : Compat v20 
  Changed Dolibarr compatibility range to 16 min - 20 max
  Changed PHP compatibility range to 7.0 min - *24/07/2024* - 1.3.0

## 1.2

- FIX : Les produits étaient manquants dans la barre de navigation - *18/07/2024* - 1.2.1
- NEW : ajout des mainmenu dans les liens générés par le module - *17/05/2024* - 1.2.0

## 1.1

- FIX : Compat V19 et php8.2 *24/11/2023* - 1.1.0

## 1.0

- FIX : Removed display of usernavhistory in popin *25/08/2023* - 1.0.10
- FIX : ProductLot elements not supported *08/02/2023* - 1.0.9
- FIX : Element type compatibility for module created in V16  *05/08/2022* - 1.0.8
- FIX : Ajout hook pour compat agefodd - *02/08/2022* - 1.0.7
- FIX : Compatibility V16 - Family - *24/06/2022* - 1.0.6
- FIX : object category was not compatible *17/07/2022* - 1.0.5
- FIX : object facturerec was not compatible *23/06/2022* - 1.0.4
- FIX : do not display usernavhistory in print mode *23/06/2022* - 1.0.3
- FIX : object picto max size *14/06/2022* - 1.0.2
- FIX : Do not display deleted elements *05/05/2022* - 1.0.1
Initial version
