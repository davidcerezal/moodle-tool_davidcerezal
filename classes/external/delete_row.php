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
 * Class containing the external API functions functions for the Data Privacy tool.
 *
 * @package   tool_davidcerezal
 * @copyright 2024, David Cerezal <david.cerezal@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_davidcerezal\external;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->dirroot/group/lib.php");

use mod_assign\external\external_api;
use core_external\external_function_parameters;
use core_external\external_value;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_user;
use context_course;

/**
 * Class external.
 *
 * The external API for the Data Privacy tool.
 *
 * @copyright  2017 Jun Pataleta
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class delete_row extends external_api {


    /**
     * Describes the parameters for delete_row.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'rowid' => new external_value(PARAM_INT, 'The request ID', VALUE_REQUIRED),
            'courseid' => new external_value(PARAM_INT, 'The course ID of the row', VALUE_REQUIRED),
            'userid' => new external_value(PARAM_INT, 'The user ID that perform the request', VALUE_REQUIRED),
        ]);
    }

    /**
     * External function to delete row form davidcerezal table.
     *
     * @param int $rowid
     * @param int $courseid
     * @param int $userid
     * @return array
     */
    public static function execute(int $rowid, int $courseid, int $userid): array {
        global $USER, $DB;

        self::validate_parameters(self::execute_parameters(), [
            'rowid' => $rowid,
            'courseid' => $courseid,
            'userid' => $userid,
        ]);

        // Default userid to current userid if is not set.
        $userid = !empty($userid) ? $userid : (int) $USER->id;

        // Validate capabilities of the user.
        $coursecontext = context_course::instance($courseid);
        self::validate_context($coursecontext);
        require_capability('moodle/course:update', $coursecontext);

        // Check permissions.
        $user = core_user::get_user($userid, '*', MUST_EXIST);
        $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
        $row = $DB->get_record('tool_davidcerezal', ['id' => $rowid], '*', MUST_EXIST);

        if ($row) {
            $result = $DB->delete_records('tool_davidcerezal', ['id' => $rowid]);
        }

        return ['result' => (int)$result];
    }

    /**
     * Describes the data returned from the external function.
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
            'result' => new external_value(PARAM_INT, 'id of course'),
        ]);
    }
}
