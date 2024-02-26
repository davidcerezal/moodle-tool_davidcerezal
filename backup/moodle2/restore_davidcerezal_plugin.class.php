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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/backup/moodle2/restore_tool_foobar_task.class.php');

/**
 * Class restore_tool_foobar_plugin.
 *
 * The restore script to get back plugins data.
 *
 * @package   tool_davidcerezal
 * @copyright 2024, David Cerezal <david.cerezal@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_tool_foobar_plugin extends restore_tool_plugin {

    /**
     * Summary of define_structure
     * @return restore_path_element[]
     */
    protected function define_structure() {
        $paths = [];
        $paths[] = new restore_path_element('tool_davidcerezal', '/course/tool_davidcerezal');
        return $paths;
    }

    /**
     * Summary of process_tool_davidcerezal
     * @param mixed $data
     * @return void
     */
    protected function process_tool_davidcerezal($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->courseid = $this->get_courseid();

        // Inserta el registro de tool_davidcerezal en la base de datos.
        $newitemid = $DB->insert_record('tool_davidcerezal', $data);
        $this->set_mapping('tool_davidcerezal', $oldid, $newitemid, true);
    }

    /**
     * Summary of get_courseid
     * @throws \base_step_exception
     * @return bool|int
     */
    protected function get_courseid() {
        if (is_null($this->task)) {
            throw new base_step_exception('not_specified_base_task');
        }
        return $this->task->get_courseid();
    }
}
