# Changelog

This file documents only the two most recent published GitHub releases, based on the changes between the published tags. Changes made after tag `3.1.1` are not included.

## 3.1.1 - 2026-10-22

### Added

- Added a security advisory on the Kopere Dashboard home page for site administrators running PHP versions affected by the October 2026 PHP security issues (CVE-2026-109618 and CVE-2026-109619).
- The advisory checks the PHP version serving the web request, identifies the fixed upstream release for supported PHP 8.2–8.5 branches, and distinguishes unsupported older versions. Linux distribution security backports may require separate verification.
- Added a per-administrator option to dismiss the advisory for the currently installed PHP version. The dismissal uses a session-key-protected POST request and administrator permission checks; it does not suppress a notice after the runtime version changes.
- Added a dedicated Mustache notice template, styling, and English and Brazilian Portuguese strings.

### Tests

- Added regression tests for affected and fixed PHP version thresholds, administrator-only visibility, and version-specific dismissal preferences.
- Updated PHPUnit coverage annotations in the security notice tests.

[Compare changes: 3.1.0...3.1.1](https://github.com/EduardoKrausME/moodle-local-kopere_dashboard/compare/3.1.0...3.1.1)

## 3.1.0 - 2026-10-19

### Added

- Added convention-based discovery of compatible external Moodle plugins, in addition to Kopere Dashboard's native `koperedashboard_*` subplugins. External integrations expose a `classes/menu.php` class with a public static `menu::get_definition()` method.
- Extended the plugin management interface to display and manage enabled integrations using their full Moodle component identifiers, including menu ordering and activation state.
- Documented the external integration contract in the README and English/Brazilian Portuguese plugin-creation guides.
- Added tests for native menu contracts and automatic discovery of compatible external plugins.

### Changed

- Isolated native subplugin enumeration from external plugin discovery to avoid recursive Moodle plugin manager initialization.
- Required callable public menu definitions for compatible integrations and skipped disabled external plugins.
- Updated the bundled Dashboard subplugins' dependency requirements to `local_kopere_dashboard` build `2026101900`.

[Compare changes: 3.0.28...3.1.0](https://github.com/EduardoKrausME/moodle-local-kopere_dashboard/compare/3.0.28...3.1.0)
