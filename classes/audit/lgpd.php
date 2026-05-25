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
 * lgpd.php
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\audit;

/**
 * Class lgpd
 */
class lgpd {
    /**
     * Function mask_cpf
     *
     * @param string|null $cpf
     * @return string
     */
    public static function mask_cpf(?string $cpf): string {
        $digits = preg_replace('/\D+/', '', (string) $cpf);

        if (strlen($digits) !== 11) {
            return '***.***.***-**';
        }

        return '***.' . substr($digits, 3, 3) . '.' . substr($digits, 6, 3) . '-**';
    }

    /**
     * Function mask_email
     *
     * @param string $email
     * @return string
     */
    public static function mask_email(string $email): string {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return '***';
        }

        [$local, $domain] = explode('@', $email, 2);

        if (strlen($local) <= 2) {
            $maskedlocal = substr($local, 0, 1) . '*';
        } else {
            $maskedlocal = substr($local, 0, 1) .
                str_repeat('*', max(strlen($local) - 2, 1)) .
                substr($local, -1);
        }

        $domainparts = explode('.', $domain);
        $domainname = array_shift($domainparts);
        $domaintld = implode('.', $domainparts);

        if (strlen($domainname) <= 2) {
            $maskeddomain = substr($domainname, 0, 1) . '*';
        } else {
            $maskeddomain = substr($domainname, 0, 1) .
                str_repeat('*', max(strlen($domainname) - 2, 1)) .
                substr($domainname, -1);
        }

        return $maskedlocal . '@' . $maskeddomain . ($domaintld ? '.' . $domaintld : '');
    }
}
