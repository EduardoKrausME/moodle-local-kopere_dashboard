# How to create a new plugin for Kopere Dashboard

This document explains how to create a new plugin integrated with **Kopere Dashboard**.

## 1. Choose the plugin name

Use a short name, in lowercase, without hyphens or spaces.

Good examples:

```text
certification
costcenter
documentation
profileupdate
syllabi
```

In this guide, the example will be:

```text
example
```

The path will be:

```text
local/kopere_dashboard/plugins/example
```

The Moodle component will be:

```text
koperedashboard_example
```

The PHP namespace will be:

```php
namespace koperedashboard_example;
```

The main permission will follow this pattern:

```text
koperedashboard/example:view
```

## 2. Minimum structure

Create the folder:

```text
local/kopere_dashboard/plugins/example
```

With this minimum structure:

```text
example/
├── classes/
│   ├── capabilities_provider.php
│   └── menu.php
├── db/
│   └── access.php
├── lang/
│   ├── en/
│   │   └── koperedashboard_example.php
│   └── pt_br/
│       └── koperedashboard_example.php
├── templates/
│   └── index.mustache
├── index.php
└── version.php
```

## 3. Create `version.php`

File:

```text
local/kopere_dashboard/plugins/example/version.php
```

Content:

```php
<?php
// This file is part of Moodle - http://moodle.org/

/**
 * version.php
 *
 * @package   koperedashboard_example
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$plugin->version = 2026052500;
$plugin->release = "1.0.0";
$plugin->component = "koperedashboard_example";
$plugin->requires = 2024042200;
$plugin->maturity = MATURITY_STABLE;
```

Always update `$plugin->version` when adding tables, permissions, or changes that require an upgrade.

## 4. Create the language strings

English file:

```text
local/kopere_dashboard/plugins/example/lang/en/koperedashboard_example.php
```

```php
<?php
// This file is part of Moodle - http://moodle.org/

/**
 * koperedashboard_example.php
 *
 * @package   koperedashboard_example
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['pluginname'] = 'Example';
$string['menu_title'] = 'Example';
$string['menu_desc'] = 'Example area inside Kopere Dashboard.';
$string['page_title'] = 'Example';
$string['cap_view'] = 'View example';
$string['cap_view_desc'] = 'Allows access to the example area.';
$string['welcome'] = 'This is the example plugin.';
$string['kpi_subtitle'] = 'Example records';
```

Portuguese file:

```text
local/kopere_dashboard/plugins/example/lang/pt_br/koperedashboard_example.php
```

```php
<?php
// This file is part of Moodle - http://moodle.org/

/**
 * koperedashboard_example.php
 *
 * @package   koperedashboard_example
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['pluginname'] = 'Exemplo';
$string['menu_title'] = 'Exemplo';
$string['menu_desc'] = 'Área de exemplo dentro do Kopere Dashboard.';
$string['page_title'] = 'Exemplo';
$string['cap_view'] = 'Visualizar exemplo';
$string['cap_view_desc'] = 'Permite acessar a área de exemplo.';
$string['welcome'] = 'Este é o plugin de exemplo.';
$string['kpi_subtitle'] = 'Registros de exemplo';
```

## 5. Declare permissions in Moodle

File:

```text
local/kopere_dashboard/plugins/example/db/access.php
```

Content:

```php
<?php
// This file is part of Moodle - http://moodle.org/

/**
 * access.php
 *
 * @package   koperedashboard_example
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$capabilities = [
    "koperedashboard/example:view" => [
        "captype" => "read",
        "contextlevel" => CONTEXT_SYSTEM,
        "archetypes" => [],
    ],
];
```

This permission becomes available in Moodle after the plugin is installed or upgraded.

## 6. Expose permissions to the Kopere Dashboard screen

File:

```text
local/kopere_dashboard/plugins/example/classes/capabilities_provider.php
```

Content:

```php
<?php
// This file is part of Moodle - http://moodle.org/

/**
 * capabilities_provider.php
 *
 * @package   koperedashboard_example
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_example;

/**
 * Class capabilities_provider
 */
class capabilities_provider {
    /**
     * Return capabilities shown in Kopere Dashboard permissions screen.
     *
     * @return array[]
     */
    public static function get_capabilities(): array {
        return [
            "koperedashboard/example:view" => [
                "name" => get_string("cap_view", "koperedashboard_example"),
                "description" => get_string("cap_view_desc", "koperedashboard_example"),
            ],
        ];
    }
}
```

This class feeds the page:

```text
/local/kopere_dashboard/permissions.php
```

Without this class, the permission may exist in Moodle, but it will not appear in the friendly permissions screen of Kopere Dashboard.

## 7. Create the menu item

File:

```text
local/kopere_dashboard/plugins/example/classes/menu.php
```

Content:

```php
<?php
// This file is part of Moodle - http://moodle.org/

/**
 * menu.php
 *
 * @package   koperedashboard_example
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_example;

use context;
use local_kopere_dashboard\api\subplugin_manager;

/**
 * Class menu
 */
class menu {
    /**
     * Return menu definition for Kopere Dashboard.
     *
     * @param context $context
     * @return array
     * @throws \coding_exception
     */
    public static function get_definition(context $context): array {
        return [
            "category" => subplugin_manager::CAT_TOOLS,
            "items" => [
                [
                    "title" => get_string("menu_title", "koperedashboard_example"),
                    "description" => get_string("menu_desc", "koperedashboard_example"),
                    "url" => "/local/kopere_dashboard/plugins/example/",
                    "icon" => "extension",
                    "capability" => "koperedashboard/example:view",
                    "children" => [],
                ],
            ],
        ];
    }
}
```

Available categories:

```php
subplugin_manager::CAT_ACADEMIC
subplugin_manager::CAT_PEDAGOGIC
subplugin_manager::CAT_FINANCIAL
subplugin_manager::CAT_TOOLS
subplugin_manager::CAT_SETTINGS
```

The `capability` field accepts three formats.

Single permission:

```php
"capability" => "koperedashboard/example:view"
```

List with OR logic:

```php
"capability" => [
    "koperedashboard/example:view",
    "koperedashboard/example:manage",
]
```

Map with `all` and `any`:

```php
"capability" => [
    "all" => ["koperedashboard/example:view"],
    "any" => [
        "koperedashboard/example:manage",
        "koperedashboard/example:approve",
    ],
]
```

## 8. Create the main page

File:

```text
local/kopere_dashboard/plugins/example/index.php
```

Content:

```php
<?php
// This file is part of Moodle - http://moodle.org/

/**
 * index.php
 *
 * @package   koperedashboard_example
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_kopere_dashboard\output\layout;

require_once(__DIR__ . "/../../../../config.php");

$context = context_system::instance();
require_login();
require_capability("koperedashboard/example:view", $context);

$PAGE->set_url(new moodle_url("/local/kopere_dashboard/plugins/example/"));
$PAGE->add_body_class("local-kopere_dashboard");
$PAGE->set_context($context);
$PAGE->set_title(get_string("page_title", "koperedashboard_example"));

$templatecontext = [
    "welcome" => get_string("welcome", "koperedashboard_example"),
];

$content = $OUTPUT->render_from_template("koperedashboard_example/index", $templatecontext);
layout::page_render($context, $content, true);
```

The `layout::page_render()` call is important because it places the page inside the official Kopere Dashboard layout, with sidebar menu, breadcrumb, and visual identity.

## 9. Create the Mustache template

File:

```text
local/kopere_dashboard/plugins/example/templates/index.mustache
```

Content:

```mustache
{{!
    This file is part of Moodle - https://moodle.org/
}}
{{!
    @template koperedashboard_example/index

    Example context (json):
    {
        "welcome": "This is the example plugin."
    }
}}

<div class="kopere_dashboard-card">
    <div class="kopere_dashboard-card-title">
        {{#str}}page_title, koperedashboard_example{{/str}}
    </div>

    <p>{{{welcome}}}</p>
</div>
```

Always use the subplugin component when rendering the template:

```php
$OUTPUT->render_from_template("koperedashboard_example/index", $templatecontext);
```

## 10. Create a KPI for the home page

The home page KPI is a summary card displayed on the **Kopere Dashboard** home page. It is used to show an important plugin number directly on the first screen, for example: pending users, active contracts, overdue payments, open requests, latest backups, or any other indicator that helps the manager make a quick decision.

This file is optional. Create `home_kpi.php` only when the plugin really has a useful indicator for the home page. If the number does not add value, depends on a very heavy query, or only makes sense inside the plugin’s own screen, it is better not to create a KPI.

The dashboard automatically looks for a class named:

```php
\koperedashboard_example\home_kpi
```

Inside the file:

```text
local/kopere_dashboard/plugins/example/classes/home_kpi.php
```

The class must have the method:

```php
public static function get_metric(context $context): ?array
```

When the user does not have permission to see that indicator, the method must return `null`. This prevents the card from appearing on the home page for users who cannot access the resource.

It is also possible to define the card priority:

```php
public static $priority = 50;
```

The lower the number, the earlier the KPI appears on the home page. Use lower priorities for more important indicators.

Example file:

```php
<?php
// This file is part of Moodle - http://moodle.org/

/**
 * home_kpi.php
 *
 * @package   koperedashboard_example
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_example;

use context;
use moodle_url;

/**
 * Class home_kpi
 */
class home_kpi {
    /** @var int Fixed dashboard priority. Lower numbers are displayed first. */
    public static $priority = 50;

    /**
     * Return the KPI data for the Kopere Dashboard home.
     *
     * Return null when the current user should not see this KPI.
     *
     * @param context $context
     * @return array|null
     */
    public static function get_metric(context $context): ?array {
        if (!has_capability("koperedashboard/example:view", $context)) {
            return null;
        }

        return [
            "title" => get_string("home_kpi_title", "koperedashboard_example"),
            "value" => self::format_number(0),
            "subtitle" => get_string("home_kpi_subtitle", "koperedashboard_example"),
            "url" => (new moodle_url("/local/kopere_dashboard/plugins/example/"))->out(false),
            "style" => "kopere_dashboard-kpi-blue",
        ];
    }

    /**
     * Format a KPI number.
     *
     * @param int $value
     * @return string
     */
    private static function format_number(int $value): string {
        return number_format($value, 0, ",", ".");
    }
}
```

The array returned by `get_metric()` accepts these fields:

```php
[
    "title" => "KPI title",
    "value" => "123",
    "subtitle" => "Supplementary text",
    "url" => "/local/kopere_dashboard/plugins/example/",
    "style" => "kopere_dashboard-kpi-blue",
]
```

Fields:

- `title`: main card title.
- `value`: highlighted number or short text. If it is empty, the KPI will not be displayed.
- `subtitle`: short explanation below the value.
- `url`: link opened when clicking the card. It can be empty, but it should usually point to the plugin’s main screen.
- `style`: visual class used to define the card color.

Add the strings to the plugin language file:

```php
$string['home_kpi_title'] = 'Example indicator';
$string['home_kpi_subtitle'] = 'Summary displayed on the dashboard home page';
```

The dashboard accepts, among others, these visual classes:

```text
kopere_dashboard-kpi-blue
kopere_dashboard-kpi-green
kopere_dashboard-kpi-red
kopere_dashboard-kpi-yellow
kopere_dashboard-kpi-indigo
kopere_dashboard-kpi-violet
kopere_dashboard-kpi-cyan
kopere_dashboard-kpi-slate
kopere_dashboard-kpi-white
```

Avoid heavy queries inside the KPI, because the dashboard home page may be accessed frequently. Prefer simple counts, already aggregated data, or fast queries with proper indexes. If the plugin depends on its own tables, it is also recommended to check whether the table exists before querying it, especially during development, installation, or upgrade.

## 11. Add children to the menu

When the main page has subpages, use `children`.

Example:

```php
"children" => [
    [
        "title" => get_string("settings", "moodle"),
        "url" => "/local/kopere_dashboard/plugins/example/settings.php",
        "icon" => "settings",
        "capability" => "koperedashboard/example:view",
    ],
]
```

When multiple URLs belong to the same menu item, use a list in `url`. This helps the layout mark the menu as active even when the user is on a secondary page.

```php
"url" => [
    "/local/kopere_dashboard/plugins/example/",
    "/local/kopere_dashboard/plugins/example/edit.php",
    "/local/kopere_dashboard/plugins/example/delete.php",
]
```

## 12. Register audit records

Use the central audit system for important actions, such as creation, editing, deletion, approval, sending, or status changes.

Example:

```php
use local_kopere_dashboard\audit\manager as audit_manager;

audit_manager::log(
    "koperedashboard_example",
    "example_viewed",
    null,
    null,
    "User accessed the example page.",
    $context->id,
    []
);
```

For records linked to a table:

```php
audit_manager::log(
    "koperedashboard_example",
    "record_updated",
    "koperedashboard_example_table",
    $recordid,
    "Record updated.",
    $context->id,
    [
        "status" => "active",
    ]
);
```

## 13. Create plugin tables

If the plugin needs its own tables, create:

```text
local/kopere_dashboard/plugins/example/db/install.xml
```

Use table names with a clear prefix, for example:

```text
koperedashboard_example_item
koperedashboard_example_log
```

Then increase `$plugin->version` in `version.php` and run the Moodle upgrade.

For future database changes, use:

```text
local/kopere_dashboard/plugins/example/db/upgrade.php
```

## 14. Add AMD JavaScript

When JavaScript is needed, use AMD in the subplugin.

Structure:

```text
local/kopere_dashboard/plugins/example/amd/src/example.js
local/kopere_dashboard/plugins/example/amd/build/example.min.js
```

Example call on the page:

```php
$PAGE->requires->strings_for_js(["js_loading"], "koperedashboard_example");
$PAGE->requires->js_call_amd("koperedashboard_example/example", "init", []);
```

AMD example:

```javascript
define(["jquery"], function($) {
    return {
        init: function() {
            $(document).on("click", "[data-example-action]", function() {
                // Handle dashboard action.
            });
        }
    };
});
```

## 15. Install and test

After creating the new plugin folder:

1. Access Moodle administration or run `php admin/cli/upgrade.php`.
2. Purge caches.
3. Access `/local/kopere_dashboard/admin_plugins.php`.
4. Confirm that the plugin appears in the chosen category.
5. Enable the plugin, if necessary.
6. Access `/local/kopere_dashboard/permissions.php`.
7. Assign the plugin permission to a user.
8. Log in as that user and confirm that the menu appears.

Useful commands:

```bash
php admin/cli/upgrade.php
php admin/cli/purge_caches.php
```

## 16. Common errors

If the plugin does not appear in the menu:

- Check whether the `classes/menu.php` class exists.
- Check whether the namespace is correct.
- Check whether the `version.php` component is correct.
- Check whether the user has the capability required by the menu item.
- Check whether the plugin is enabled in `admin_plugins.php`.
- Purge Moodle caches.

If the permission does not appear on the permissions screen:

- Check `classes/capabilities_provider.php`.
- Check whether `get_capabilities()` returns the correct capability.
- Check whether the capability also exists in `db/access.php`.
- Run the Moodle upgrade after creating or changing permissions.

If the template does not render:

- Check whether the file is inside `templates/`.
- Check whether the name used in `render_from_template()` is correct.
- Use the format `koperedashboard_example/index`.
- Purge caches.

If the page opens outside the dashboard layout:

- Check whether the page called `layout::page_render($context, $content, true, $classname, $afterheader)`.
