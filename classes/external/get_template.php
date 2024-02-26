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
 * @package    tool_davidcerezal
 * @copyright  2024 David Cerezal
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_davidcerezal\external;


defined('MOODLE_INTERNAL') || die();

require_once("$CFG->dirroot/group/lib.php");

use mod_assign\external\external_api;
use core_external\external_function_parameters;
use core_external\external_value;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use tool_davidcerezal\output\index_page;
use tool_davidcerezal\local_tool_davidcerezal_table_sql;
use context_course;
use core_user;
use moodle_url;

/**
 * Class external.
 *
 * The external API for the Data Privacy tool.
 *
 * @package   tool_davidcerezal
 * @copyright 2024, David Cerezal <david.cerezal@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_template extends external_api {


    /**
     * Describes the parameters for delete_row.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'The course ID of the row', VALUE_REQUIRED),
        ]);
    }

    /**
     * External function to delete row form davidcerezal table.
     *
     * @param int $courseid
     * @return array
     */
    public static function execute(int $courseid): array {
        global $PAGE, $DB;

        ['courseid' => $courseid] = self::validate_parameters(self::execute_parameters(), ['courseid' => $courseid]);

        // Validate capabilities of the user.
        $coursecontext = context_course::instance($courseid);
        self::validate_context($coursecontext);
        require_capability('tool/davidcerezal:edit', $coursecontext);

        // Check permissions.
        $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

        // Output the page of the plugin.
        $output = $PAGE->get_renderer('tool_davidcerezal');
        $pageurl = new moodle_url('/admin/tool/davidcerezal/index.php', ['course_id' => $courseid]);
        $outputtable = new local_tool_davidcerezal_table_sql($courseid, $pageurl);
        $renderable = new index_page('David Admin plugin\'s ', $outputtable);

        return ['content' => $renderable->export_for_template($output)];
    }

    /**
     * Describes the data returned from the external function.
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
                'content' => new external_single_structure([
                    'tableheader' => new external_value(PARAM_TEXT, 'The table header'),
                    'outpatabletable' => new external_value(PARAM_RAW, 'The table content'),
                    'userid' => new external_value(PARAM_INT, 'The user ID' ),
                ]),
        ]);
    }
}
