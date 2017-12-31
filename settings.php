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
 * @created    23/05/17 17:59
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if (!$PAGE->requires->is_head_done()) {
    $PAGE->requires->css('/local/kopere_dashboard/assets/style.css');
}

if ($hassiteconfig) {

    if (!$ADMIN->locate('integracaoroot')) {
        $ADMIN->add('root', new admin_category('integracaoroot', 'Integrações'));
    }

    $ADMIN->add('integracaoroot',
        new admin_externalpage(
            'local_kopere_dashboard',
            get_string('modulename', 'local_kopere_dashboard'),
            $CFG->wwwroot . '/local/kopere_dashboard/open.php'
        )
    );
}