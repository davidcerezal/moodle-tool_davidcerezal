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
namespace tool_davidcerezal;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/' . $CFG->admin . '/tool/dataprivacy/lib.php');

use context_system;
use mod_assign\external\external_api;
use core_external\external_function_parameters;
use core_external\external_value;


/**
 * Class external.
 *
 * The external API for the Data Privacy tool.
 *
 * @copyright  2017 Jun Pataleta
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external extends external_api {

    /**
     * Parameter description for cancel_data_request().
     *
     * @since Moodle 3.5
     * @return external_function_parameters
     */
    public static function delete_row_parameters() {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT, 'The request ID', VALUE_REQUIRED),
            'sesskey' => new external_value(PARAM_INT, 'The request ID', VALUE_REQUIRED),
        ]);
    }


    /**
     * Delete a data request
     *
     * @since Moodle 3.5
     * @param int $requestid The request ID.
     * @return array
     */
    public static function delete_row($requestid) {
        global $USER, $DB;

        $params = external_api::validate_parameters(self::delete_row_parameters(), [
            'requestid' => $requestid
        ]);
        $requestid = $params['requestid'];

        require_login();
        confirm_sesskey($params['sesskey']);
        require_capability('moodle/site:config', context_system::instance());

        if ($deleteid = optional_param('rowid', null, PARAM_INT)) {
            $result = $DB->delete_records('tool_davidcerezal', ['id' => $deleteid]);
        }

        return [
            'result' => $result,
            'warnings' => [],
        ];
    }
}
