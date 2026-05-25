<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * categorycourse_select_element
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\form;

use context_course;
use context_coursecat;
use core\output\renderer_base;
use core\output\templatable;
use dml_exception;
use HTML_QuickForm_element;
use MoodleQuickForm_Renderer;

// phpcs:disable moodle.Files.MoodleInternal.MoodleInternalGlobalState
// phpcs:disable Squiz.Scope.MethodScope.Missing
// phpcs:disable moodle.NamingConventions.ValidFunctionName.LowercaseMethod
global $CFG;
require_once("{$CFG->libdir}/pear/HTML/QuickForm/element.php");

/**
 * Course selector rendered as nested category checkboxes.
 */
class categorycourse_select_element extends HTML_QuickForm_element implements templatable {
    /** @var array[] Category tree used to render the checkbox UI. */
    protected $categorytree = [];

    /**
     * Class constructor
     *
     * @param string $elementname Input field name attribute
     * @param mixed $elementlabel Label(s) for the input field
     * @param mixed $optgrps Category tree (nested) or legacy optgroups array
     * @param mixed $attributes Either a typical HTML attribute string or an associative array
     * @return    void
     */
    public function __construct($elementname = null, $elementlabel = null, $optgrps = null, $attributes = null) {
        parent::__construct($elementname, $elementlabel, $attributes);

        $this->_type = 'static';

        // Ensure an id exists for disabledIf() rules (Moodle uses id_{$name}).
        $id = (string) $this->getAttribute("id");
        if ($id === "") {
            $id = "id_{$this->normalise_id($this->getName())}";
            $this->updateAttributes(["id" => $id]);
        }

        if ($optgrps !== null) {
            $this->categorytree = $this->normalise_category_tree($optgrps);
        }
    }

    /**
     * Old syntax of class constructor. Deprecated in PHP7.
     *
     * @deprecated since Moodle 3.1
     */
    public function categorycourse_select_element($elementname = null, $elementlabel = null, $optgrps = null, $attributes = null) {
        debugging("Use of class name as constructor is deprecated", DEBUG_DEVELOPER);
        self::__construct($elementname, $elementlabel, $optgrps, $attributes);
    }

    /**
     * Sets the element type
     *
     * @param string $type Element type
     * @return    void
     */
    function setType($type) {
        $this->_type = $type;
        $this->updateAttributes(["type" => $type]);
    }

    /**
     * Sets the input field name
     *
     * @param string $name Input field name attribute
     * @return    void
     */
    function setName($name) {
        $this->updateAttributes(["name" => $name]);
    }

    /**
     * Returns the element name
     *
     * @return    string
     */
    function getName() {
        return $this->getAttribute("name");
    }

    /**
     * Sets the value of the form element
     *
     * @param mixed $value Default value of the form element
     * @return void
     */
    function setValue($value) {
        $this->updateAttributes(["value" => $this->normalise_selected_values($value)]);
    }

    /**
     * Returns the value of the form element
     *
     * @return    mixed
     */
    function getValue() {
        return $this->getAttribute("value");
    }

    /**
     * Accepts a renderer.
     *
     * @param object $renderer Renderer instance (MoodleQuickForm_Renderer / HTML_QuickForm_Renderer_*)
     * @param bool $required Whether element is required
     * @param string $error Error message
     * @return void
     */
    function accept(&$renderer, $required = false, $error = null) {
        // If this element is hidden, let renderer handle it as hidden (keeps layout clean).
        $type = $this->getType();
        if ($type === "hidden" && method_exists($renderer, "renderHidden")) {
            $renderer->renderHidden($this);
            return;
        }

        // Standard path: let Moodle/QuickForm renderer wrap label, required marker, errors, etc.
        if (method_exists($renderer, "renderElement")) {
            $renderer->renderElement($this, $required, $error);
            return;
        }

        // Fallback: behave like base element.
        if (is_callable(["HTML_QuickForm_element", "accept"])) {
            parent::accept($renderer, $required, $error);
        }
    }

    /**
     * Returns the input field in HTML
     *
     * @return    string
     */
    function toHtml() {
        if ($this->_flagFrozen) {
            return $this->getFrozenHtml();
        }

        $name = $this->getName();

        // Control id used by Moodle labels and disabledIf().
        $id = (string) $this->getAttribute("id");
        if ($id === "") {
            $id = "id_{$this->normalise_id($name)}";
            $this->updateAttributes(["id" => $id]);
        }

        $containerid = "{$id}_container";
        $selectedvalues = $this->normalise_selected_values($this->getValue());
        $disabledattr = !empty($this->getAttribute("disabled")) ? " disabled=\"disabled\"" : "";

        // Optional: mimic select height when form passes ["size" => 10].
        $maxheight = "";
        $size = $this->getAttribute("size");
        if ($size > 0) {
            $maxheight = "max-height:" . ($size * 26) . "px;overflow:auto;";
        }

        $html = [];
        $html[] = $this->_getTabs() .
            "<div class=\"kopere_dashboard-categorycourse-select\"
                  style=\"border:1px solid #ced4da;border-radius:4px;padding:0 10px 10px;{$maxheight}\">";

        // Dummy control used only for label targeting + disabledIf() state (does not submit).
        $html[] = "<input type=\"hidden\" id=\"" . htmlspecialchars($id, ENT_QUOTES) . "\" name=\"" .
            htmlspecialchars("{$name}__control", ENT_QUOTES) . "\" value=\"1\"{$disabledattr} />";

        $html[] = "<div id=\"" . htmlspecialchars($containerid, ENT_QUOTES) . "\" class=\"kopere_dashboard-categorycourse-tree\">";
        $html[] = $this->render_category_tree($this->categorytree, $selectedvalues, 0, $name, $disabledattr);
        $html[] = "</div>";
        $html[] = "</div>";
        $html[] = $this->get_disabled_sync_javascript($id, $containerid);

        return implode("\n", $html);
    }

    /**
     * Called by HTML_QuickForm whenever form event is made on this element
     *
     * @param string $event Name of event
     * @param mixed $arg event arguments
     * @param object $caller calling object
     * @return void
     */
    function onQuickFormEvent($event, $arg, &$caller) {
        // Do not use submit values for button-type elements.
        $type = $this->getType();
        if (("updateValue" != $event) ||
            ("submit" != $type && "reset" != $type && "image" != $type && "button" != $type)) {
            parent::onQuickFormEvent($event, $arg, $caller);
        } else {
            $value = $this->_findValue($caller->_constantValues);
            if (null === $value) {
                $value = $this->_findValue($caller->_defaultValues);
            }
            if (null !== $value) {
                $this->setValue($value);
            }
        }
        return true;
    }

    /**
     * We don't need values from button-type elements (except submit) and files
     */
    function exportValue(&$submitvalues, $assoc = false) {
        $type = $this->getType();
        if ("reset" == $type || "image" == $type || "button" == $type || "file" == $type) {
            return null;
        }

        $value = $this->_findValue($submitvalues);
        if ($value === null) {
            return null;
        }

        // Guarantee array of ints.
        $value = array_map("intval", $this->normalise_selected_values($value));

        if ($assoc) {
            return [$this->getName() => $value];
        }

        return $value;
    }

    /**
     * Returns course options grouped by nested categories.
     *
     * @return array
     * @throws dml_exception
     */
    public static function get_course_options() {
        global $DB;

        $categoriesrecords = $DB->get_records_sql(
            "
            SELECT id, name, parent, sortorder
              FROM {course_categories}
             WHERE visible = 1
          ORDER BY sortorder ASC, name ASC
        "
        );

        if (!$categoriesrecords) {
            return [];
        }

        $categories = [];
        foreach ($categoriesrecords as $category) {
            $categories[$category->id] = [
                "id" => $category->id,
                "name" => format_string($category->name, true, [
                    "context" => context_coursecat::instance($category->id),
                ]),
                "parent" => $category->parent,
                "sortorder" => $category->sortorder,
                "courses" => [],
                "children" => [],
            ];
        }

        $courserecords = $DB->get_records_sql(
            "
            SELECT c.id,
                   c.fullname,
                   c.category,
                   c.sortorder
              FROM {course} c
              JOIN {course_categories} cc ON cc.id = c.category
             WHERE c.visible = 1
               AND cc.visible = 1
          ORDER BY cc.sortorder ASC, c.sortorder ASC, c.fullname ASC
        "
        );

        foreach ($courserecords as $course) {
            $categoryid = $course->category;
            if (!isset($categories[$categoryid])) {
                continue;
            }

            $categories[$categoryid]["courses"][] = [
                "id" => $course->id,
                "name" => format_string($course->fullname, true, [
                    "context" => context_course::instance($course->id),
                ]),
                "sortorder" => $course->sortorder,
            ];
        }

        $roots = [];
        foreach ($categories as $categoryid => $category) {
            $parentid = $category["parent"];
            if ($parentid > 0 && isset($categories[$parentid])) {
                $categories[$parentid]["children"][] = &$categories[$categoryid];
            } else {
                $roots[] = &$categories[$categoryid];
            }
        }

        $tree = [];
        foreach ($roots as $root) {
            $prepared = self::prune_empty_categories($root);
            if ($prepared !== null) {
                $tree[] = $prepared;
            }
        }

        return array_values($tree);
    }

    /**
     * Remove categories that have neither direct courses nor descendant courses.
     *
     * @param array $category
     * @return array|null
     */
    protected static function prune_empty_categories(array $category): ?array {
        $children = [];
        foreach ($category["children"] as $child) {
            $preparedchild = self::prune_empty_categories($child);
            if ($preparedchild !== null) {
                $children[] = $preparedchild;
            }
        }

        usort($children, function(array $a, array $b): int {
            return $a["sortorder"] <=> $b["sortorder"];
        });

        usort($category["courses"], function(array $a, array $b): int {
            if ($a["sortorder"] === $b["sortorder"]) {
                return strnatcasecmp(strip_tags($a["name"]), strip_tags($b["name"]));
            }
            return $a["sortorder"] <=> $b["sortorder"];
        });

        $category["children"] = $children;

        if (empty($category["courses"]) && empty($category["children"])) {
            return null;
        }

        return $category;
    }

    /**
     * Keep backwards compatibility if legacy grouped arrays are ever passed in.
     *
     * @param mixed $data
     * @return array
     */
    protected function normalise_category_tree($data): array {
        if (!is_array($data)) {
            return [];
        }

        $first = reset($data);
        if (is_array($first) && array_key_exists("children", $first) && array_key_exists("courses", $first)) {
            return array_values($data);
        }

        // Legacy optgroups array: ["Category" => [courseid => name]].
        $tree = [];
        foreach ($data as $categorylabel => $courses) {
            if (!is_array($courses)) {
                continue;
            }

            $category = [
                "id" => 0,
                "name" => (string) $categorylabel,
                "parent" => 0,
                "sortorder" => 0,
                "courses" => [],
                "children" => [],
            ];

            foreach ($courses as $courseid => $coursename) {
                $category["courses"][] = [
                    "id" => $courseid,
                    "name" => (string) $coursename,
                    "sortorder" => 0,
                ];
            }

            $tree[] = $category;
        }

        return $tree;
    }

    /**
     * Render nested categories and courses.
     *
     * @param array $categories
     * @param array $selectedvalues
     * @param int $depth
     * @param string $name
     * @param string $disabledattr
     * @return string
     */
    protected function render_category_tree(array $categories, array $selectedvalues, int $depth, string $name, string $disabledattr
    ): string {
        if (empty($categories)) {
            return "";
        }

        $left = 30;

        $html = [];
        foreach ($categories as $category) {
            $margin = $depth * $left;

            $html[] = "<div class=\"kopere_dashboard-categorycourse-node\" style=\"margin-left:{$margin}px\">";
            $html[] = "<div class=\"kopere_dashboard-categorycourse-label\"
                            style=\"font-weight:600;margin:10px 0 6px 0\">{$category["name"]}</div>";

            foreach ($category["courses"] as $course) {
                $courseid = $course["id"];
                $checkboxid = $this->get_checkbox_id($courseid);
                $checked = in_array((string) $courseid, $selectedvalues, true) ? " checked=\"checked\"" : "";

                $html[] = "<div class=\"kopere_dashboard-categorycourse-item\" style=\"margin:4px 0 4px {$left}px\">";
                $html[] = "<label for=\"" . htmlspecialchars($checkboxid, ENT_QUOTES) . "\"
                                  style=\"display:flex;align-items:center;gap:8px;cursor:pointer\">";
                $html[] = "<input type=\"checkbox\" id=\"" . htmlspecialchars($checkboxid, ENT_QUOTES) . "\"
                                  class=\"kopere_dashboard-course-checkbox\" name=\"" . htmlspecialchars($name, ENT_QUOTES) . "[]\"
                                  value=\"{$courseid}\"{$checked}{$disabledattr} />";
                $html[] = "<span>{$course["name"]}</span>";
                $html[] = "</label>";
                $html[] = "</div>";
            }

            if (!empty($category["children"])) {
                $html[] = $this->render_category_tree($category["children"], $selectedvalues, $depth + 1, $name, $disabledattr);
            }

            $html[] = "</div>";
        }

        return implode("\n", $html);
    }

    /**
     * Normalize selected values.
     *
     * @param mixed $value
     * @return array
     */
    protected function normalise_selected_values($value): array {
        if ($value === null || $value === "") {
            return [];
        }

        $values = [];
        if (is_array($value)) {
            array_walk_recursive($value, function($item) use (&$values): void {
                if ($item === null || $item === "") {
                    return;
                }
                $values[] = (string) $item;
            });
        } else {
            $values[] = (string) $value;
        }

        $values = array_values(array_unique(array_filter($values, function($item): bool {
            return $item !== "" && $item !== null;
        })));

        return $values;
    }

    /**
     * Stable DOM id for each checkbox.
     *
     * @param int $courseid
     * @return string
     */
    protected function get_checkbox_id(int $courseid): string {
        $baseid = (string) $this->getAttribute("id");
        if ($baseid === "") {
            $baseid = "id_{$this->normalise_id($this->getName())}";
        }
        return "{$baseid}_course_{$courseid}";
    }

    /**
     * JavaScript to keep disabledIf() state in sync with all checkboxes.
     *
     * @param string $controlid
     * @param string $containerid
     * @return string
     */
    protected function get_disabled_sync_javascript(string $controlid, string $containerid): string {
        $escapedcontrolid = json_encode($controlid);
        $escapedcontainerid = json_encode($containerid);

        return "<script>
(function() {
    var control = document.getElementById({$escapedcontrolid});
    var container = document.getElementById({$escapedcontainerid});
    if (!control || !container) {
        return;
    }

    var checkboxes = Array.prototype.slice.call(container.querySelectorAll('input.kopere_dashboard-course-checkbox'));

    var syncDisabledState = function() {
        var disabled = !!control.disabled;
        checkboxes.forEach(function(checkbox) {
            checkbox.disabled = disabled;
        });
        container.classList.toggle('disabled', disabled);
        if (disabled) {
            container.style.opacity = '0.6';
            container.style.pointerEvents = 'none';
        } else {
            container.style.opacity = '';
            container.style.pointerEvents = '';
        }
    };

    if (typeof MutationObserver !== 'undefined') {
        var observer = new MutationObserver(syncDisabledState);
        observer.observe(control, {attributes: true, attributeFilter: ['disabled']});
    }

    syncDisabledState();
})();
</script>";
    }

    /**
     * Normalise a value to be safe for HTML ids.
     *
     * @param string $name
     * @return string
     */
    protected function normalise_id($name): string {
        if ($name) {
            $name = preg_replace("/\\[\\]$/", "", $name);
            $name = preg_replace("/[^A-Za-z0-9\\-_:.]/", "_", $name);
        }
        return $name ?? "";
    }

    /**
     * Export data so Moodle can render this element via Mustache-based forms.
     *
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output) {
        // Ensure ids/attributes are final and JS is included.
        $html = $this->toHtml();

        $name = $this->getName();
        $id = (string) $this->getAttribute("id");
        if ($id === "") {
            $id = "id_{$this->normalise_id($name)}";
            $this->updateAttributes(["id" => $id]);
        }

        $value = $this->normalise_selected_values($this->getValue());

        // Provide both "element" and flat keys to be resilient across templates.
        $context = [
            "name" => $name,
            "id" => $id,
            "type" => $this->getType(),
            "frozen" => $this->_flagFrozen,
            "disabled" => !empty($this->getAttribute("disabled")),
            "value" => $value,
            "html" => $html,
            "element" => [
                "name" => $name,
                "id" => $id,
                "type" => $this->getType(),
                "frozen" => $this->_flagFrozen,
                "disabled" => !empty($this->getAttribute("disabled")),
                "value" => $value,
                "html" => $html,
            ],
        ];

        return $context;
    }
}
