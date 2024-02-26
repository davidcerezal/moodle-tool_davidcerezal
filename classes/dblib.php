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
 * Edit tool_davidcerezal record.
 *
 * @package   tool_davidcerezal
 * @category  admin
 * @copyright 2024, David Cerezal <david.cerezal@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 namespace tool_davidcerezal;

// Include Moodle configuration and necessary libraries.
use context_course;
use stdClass;
use tool_davidcerezal\event\entry_added;


/**
 * Db functions to update, insert and get records.
 *
 * @package  tool_davidcerezal
 */
class dblib {

    /**
     * Update a record.
     *
     * @param stdClass $data The data to update.
     *                      - Requires 'id' property to be set.
     * @return bool True if the record was updated successfully, false otherwise.
     */
    public static function update(stdClass $data) {
        global $DB;

        if (!isset($data->id)) {
            return false;
        }

        $data->timeupdated = time();

        return $DB->update_record('tool_davidcerezal', $data);
    }

    /**
     * Update or insert a record.
     *
     * @param stdClass $data The data to update or insert.
     * @return bool|int The ID of the inserted record if successful, false otherwise.
     */
    public static function insert(stdClass $data) {
        global $DB;

        $data->timecreated = time();
        $result = $DB->insert_record('tool_davidcerezal', $data);

        if ($result) {
            $context = context_course::instance($data->courseid);
            $event = entry_added::create(['context' => $context, 'objectid' => $data->courseid]);
            $event->trigger();
        }

        return $result;
    }

}
