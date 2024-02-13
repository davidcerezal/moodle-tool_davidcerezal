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
 * Links and settings
 *
 * This file contains links and settings used by tool_davidcerezal
 *
 * @package    tool_davidcerezal
 * @copyright  2024, David Cerezal <david.cerezal@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_davidcerezal;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/tablelib.php');

use table_sql;

/**
 * Table class for the davidcerezal table.
 */
class local_tool_davidcerezal_table_sql extends table_sql {

    /**
     * Sets up the table.
     */
    public function __construct() {
        parent::__construct('davidcerezal');

        $context = \context_system::instance();

        $columnheaders = [
            'courseid' => get_string('courseid', 'tool_davidcerezal'),
            'timecreated' => get_string('timecreated', 'tool_davidcerezal'),
            'timemodified' => get_string('timemodified', 'tool_davidcerezal'),
            'completed' => get_string('completed', 'tool_davidcerezal'),
            'priority' => get_string('priority', 'tool_davidcerezal'),
            'name' => get_string('message', 'tool_davidcerezal'),
        ];

        $this->define_columns(array_keys($columnheaders));
        $this->define_headers(array_values($columnheaders));
    }

    /**
     * Formats the columns.
     * @param mixed $value the value to format
     * @return string the formatted value
     */
    public function col_name($value) {
        return format_string($value);
    }

    /**
     * Formats the columns.
     * @param mixed $value the value to format
     * @return string the formatted value
     */
    public function col_completed($value) {
        return $value ? get_string('yes') : get_string('no');
    }

    /**
     * Formats the columns.
     * @param mixed $value the value to format
     * @return string the formatted value
     */
    public function col_timecreated($value) {
        return userdate($value);
    }

    /**
     * Formats the columns.
     * @param mixed $value the value to format
     * @return string the formatted value
     */
    public function col_timemodified($value) {
        return userdate($value);
    }
}
