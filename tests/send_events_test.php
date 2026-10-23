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
 * Tests for event notification placeholder replacement.
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use basic_testcase;
use local_kopere_dashboard\output\events\send_events;

/**
 * Notification templates should tolerate optional empty input on supported PHP versions.
 *
 * @covers \local_kopere_dashboard\output\events\send_events
 */
final class send_events_test extends basic_testcase {
    /**
     * Null inputs must not trigger PHP 8.1+ deprecation notices.
     */
    public function test_nullable_template_text(): void {
        $sender = new class extends send_events {
            /**
             * Expose the protected date substitution.
             *
             * @param string|null $text
             * @return string
             */
            public function date_text($text): string {
                return $this->replace_date($text);
            }

            /**
             * Expose the protected object substitution.
             *
             * @param string|null $text
             * @param object $data
             * @param string $keyname
             * @return string
             */
            public function object_text($text, $data, $keyname): string {
                return $this->replace_tag($text, $data, $keyname);
            }
        };

        $this->assertSame("", $sender->date_text(null));
        $this->assertSame("", $sender->object_text(null, (object)["title" => "Ticket"], "event"));
        $this->assertSame("", send_events::replace_tag_user(null, (object)["firstname" => "Mary"], "to"));

        $this->assertSame(
            "Ticket: Created",
            $sender->object_text("Ticket: {[event.title]}", (object)["title" => "Created"], "event")
        );
        $this->assertSame(
            "Hello Mary",
            send_events::replace_tag_user("Hello {[to.firstname]}", (object)["firstname" => "Mary"], "to")
        );
        $this->assertSame("Year: " . date("Y"), $sender->date_text("Year: {[date.year]}"));
    }
}
