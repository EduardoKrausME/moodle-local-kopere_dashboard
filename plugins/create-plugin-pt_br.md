# Como criar um novo plugin para o Kopere Dashboard

Este documento explica como criar um novo plugin integrado ao **Kopere Dashboard**.

## 1. Escolha o nome do plugin

Use um nome curto, em minúsculas, sem hífen e sem espaço.

Exemplos bons:

```text
certification
costcenter
documentation
profileupdate
syllabi
```

Neste guia, o exemplo será:

```text
example
```

O caminho será:

```text
local/kopere_dashboard/plugins/example
```

O componente Moodle será:

```text
koperedashboard_example
```

O namespace PHP será:

```php
namespace koperedashboard_example;
```

A permissão principal seguirá este padrão:

```text
koperedashboard/example:view
```

## 2. Estrutura mínima

Crie a pasta:

```text
local/kopere_dashboard/plugins/example
```

Com esta estrutura mínima:

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

## 3. Criar o `version.php`

Arquivo:

```text
local/kopere_dashboard/plugins/example/version.php
```

Conteúdo:

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

Sempre altere `$plugin->version` quando adicionar tabelas, permissões ou mudanças que exigem upgrade.

## 4. Criar as strings de idioma

Arquivo em inglês:

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

Arquivo em português:

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

## 5. Declarar permissões no Moodle

Arquivo:

```text
local/kopere_dashboard/plugins/example/db/access.php
```

Conteúdo:

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

Essa permissão passa a existir no Moodle após a instalação ou upgrade do plugin.

## 6. Expor permissões para a tela do Kopere Dashboard

Arquivo:

```text
local/kopere_dashboard/plugins/example/classes/capabilities_provider.php
```

Conteúdo:

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

Essa classe alimenta a página:

```text
/local/kopere_dashboard/permissions.php
```

Sem essa classe, a permissão pode existir no Moodle, mas não aparecerá na tela amigável de permissões do Kopere Dashboard.

## 7. Criar o item de menu

Arquivo:

```text
local/kopere_dashboard/plugins/example/classes/menu.php
```

Conteúdo:

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

Categorias disponíveis:

```php
subplugin_manager::CAT_ACADEMIC
subplugin_manager::CAT_PEDAGOGIC
subplugin_manager::CAT_FINANCIAL
subplugin_manager::CAT_TOOLS
subplugin_manager::CAT_SETTINGS
```

O campo `capability` aceita três formatos.

Permissão única:

```php
"capability" => "koperedashboard/example:view"
```

Lista com lógica OR:

```php
"capability" => [
    "koperedashboard/example:view",
    "koperedashboard/example:manage",
]
```

Mapa com `all` e `any`:

```php
"capability" => [
    "all" => ["koperedashboard/example:view"],
    "any" => [
        "koperedashboard/example:manage",
        "koperedashboard/example:approve",
    ],
]
```

## 8. Criar a página principal

Arquivo:

```text
local/kopere_dashboard/plugins/example/index.php
```

Conteúdo:

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
layout::page_render($context, $content, true, $classname);
```

A chamada `layout::page_render()` é importante porque coloca a página dentro do layout oficial do Kopere Dashboard, com menu lateral, breadcrumb e identidade visual.

## 9. Criar o template Mustache

Arquivo:

```text
local/kopere_dashboard/plugins/example/templates/index.mustache
```

Conteúdo:

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

Use sempre o componente do subplugin ao renderizar o template:

```php
$OUTPUT->render_from_template("koperedashboard_example/index", $templatecontext);
```

## 10. Criar um KPI para a página inicial

O KPI da página inicial é um cartão de resumo exibido na home do **Kopere Dashboard**. Ele serve para mostrar um número importante do plugin logo na primeira tela, por exemplo: usuários pendentes, contratos ativos, pagamentos vencidos, solicitações abertas, últimos backups ou qualquer outro indicador que ajude o gestor a tomar uma decisão rápida.

Este arquivo é opcional. Crie o `home_kpi.php` apenas quando o plugin realmente tiver um indicador útil para a home. Se o número não agrega valor, se depende de uma consulta muito pesada ou se só faz sentido dentro da própria tela do plugin, é melhor não criar KPI.

O dashboard procura automaticamente por uma classe chamada:

```php
\koperedashboard_example\home_kpi
```

Dentro do arquivo:

```text
local/kopere_dashboard/plugins/example/classes/home_kpi.php
```

A classe deve ter o método:

```php
public static function get_metric(context $context): ?array
```

Quando o usuário não tiver permissão para ver aquele indicador, o método deve retornar `null`. Assim o cartão não aparece na home para quem não pode acessar o recurso.

Também é possível definir a prioridade do cartão:

```php
public static $priority = 50;
```

Quanto menor o número, mais cedo o KPI aparece na home. Use prioridades baixas para indicadores mais importantes.

Exemplo de arquivo:

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

O array retornado por `get_metric()` aceita estes campos:

```php
[
    "title" => "Título do KPI",
    "value" => "123",
    "subtitle" => "Texto complementar",
    "url" => "/local/kopere_dashboard/plugins/example/",
    "style" => "kopere_dashboard-kpi-blue",
]
```

Campos:

- `title`: título principal do cartão.
- `value`: número ou texto curto em destaque. Se estiver vazio, o KPI não será exibido.
- `subtitle`: explicação curta abaixo do valor.
- `url`: link aberto ao clicar no cartão. Pode ficar vazio, mas normalmente deve apontar para a tela principal do plugin.
- `style`: classe visual usada para definir a cor do cartão.

Adicione as strings no arquivo de idioma do plugin:

```php
$string['home_kpi_title'] = 'Indicador de exemplo';
$string['home_kpi_subtitle'] = 'Resumo exibido na home do dashboard';
```

O dashboard aceita, entre outras, estas classes visuais:

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

Evite consultas pesadas dentro do KPI, porque a home do dashboard pode ser acessada com frequência. Prefira contagens simples, dados já agregados ou consultas rápidas com índices adequados. Caso o plugin dependa de tabelas próprias, também é recomendável validar se a tabela existe antes de consultar, principalmente durante desenvolvimento, instalação ou atualização.

## 11. Adicionar filhos no menu

Quando a página principal possui subtelas, use `children`.

Exemplo:

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

Quando várias URLs pertencem ao mesmo item de menu, use uma lista em `url`. Isso ajuda o layout a marcar o menu como ativo mesmo quando o usuário está em uma página secundária.

```php
"url" => [
    "/local/kopere_dashboard/plugins/example/",
    "/local/kopere_dashboard/plugins/example/edit.php",
    "/local/kopere_dashboard/plugins/example/delete.php",
]
```

## 12. Registrar auditoria

Use a auditoria central para ações importantes, como criação, edição, exclusão, aprovação, envio ou alteração de status.

Exemplo:

```php
use local_kopere_dashboard\audit\manager as audit_manager;

audit_manager::log(
    "koperedashboard_example",
    "example_viewed",
    null,
    null,
    "Usuário acessou a página de exemplo.",
    $context->id,
    []
);
```

Para registros vinculados a uma tabela:

```php
audit_manager::log(
    "koperedashboard_example",
    "record_updated",
    "koperedashboard_example_table",
    $recordid,
    "Registro atualizado.",
    $context->id,
    [
        "status" => "active",
    ]
);
```

## 13. Criar tabelas próprias

Se o plugin precisar de tabelas próprias, crie:

```text
local/kopere_dashboard/plugins/example/db/install.xml
```

Use nomes de tabela com prefixo claro, por exemplo:

```text
koperedashboard_example_item
koperedashboard_example_log
```

Depois aumente `$plugin->version` no `version.php` e execute o upgrade do Moodle.

Para mudanças futuras de banco, use:

```text
local/kopere_dashboard/plugins/example/db/upgrade.php
```

## 14. Adicionar JavaScript AMD

Quando precisar de JavaScript, use AMD no subplugin.

Estrutura:

```text
local/kopere_dashboard/plugins/example/amd/src/example.js
local/kopere_dashboard/plugins/example/amd/build/example.min.js
```

Exemplo de chamada na página:

```php
$PAGE->requires->strings_for_js(["js_loading"], "koperedashboard_example");
$PAGE->requires->js_call_amd("koperedashboard_example/example", "init", []);
```

Exemplo de AMD:

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

## 15. Instalar e testar

Depois de criar a pasta do novo plugin:

1. Acesse a administração do Moodle ou execute `php admin/cli/upgrade.php`.
2. Limpe os caches.
3. Acesse `/local/kopere_dashboard/admin_plugins.php`.
4. Confirme se o plugin aparece na categoria escolhida.
5. Ative o plugin, se necessário.
6. Acesse `/local/kopere_dashboard/permissions.php`.
7. Atribua a permissão do plugin a um usuário.
8. Entre com esse usuário e valide se o menu aparece.

Comandos úteis:

```bash
php admin/cli/upgrade.php
php admin/cli/purge_caches.php
```

## 16. Erros comuns

Se o plugin não aparece no menu:

- Verifique se a classe `classes/menu.php` existe.
- Verifique se o namespace está correto.
- Verifique se o componente do `version.php` está correto.
- Verifique se o usuário possui a capability exigida no item de menu.
- Verifique se o plugin está ativo em `admin_plugins.php`.
- Limpe os caches do Moodle.

Se a permissão não aparece na tela de permissões:

- Verifique `classes/capabilities_provider.php`.
- Verifique se `get_capabilities()` retorna a capability correta.
- Verifique se a capability também existe em `db/access.php`.
- Execute o upgrade do Moodle após criar ou alterar permissões.

Se o template não renderiza:

- Verifique se o arquivo está em `templates/`.
- Verifique se o nome usado em `render_from_template()` está correto.
- Use o formato `koperedashboard_example/index`.
- Limpe os caches.

Se a página abre fora do layout do dashboard:

- Verifique se a página chamou `layout::page_render($context, $content, true, $classname, $afterheader)`.
