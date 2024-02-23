
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

require_once($CFG->dirroot . '/backup/moodle2/backup_tool_plugin.class.php');

class backup_davidcerezal_plugin extends backup_tool_plugin {

    protected function define_course_plugin_structure() {
        global $DB;

        $plugin = $this->get_plugin_element();

        // Define the structure for tool_davidcerezal table.
        $tool_davidcerezal = new backup_nested_element('tool_davidcerezal', ['id'], [
            'courseid', 'data', 'name', 'completed', 'priority', 'timecreated', 
            'timemodified', 'description', 'descriptionformat', 
        ]);
        $tool_davidcerezal->set_source_table('tool_davidcerezal', ['courseid' => backup::VAR_COURSEID]);
        $plugin->add_child($tool_davidcerezal);

        return $plugin;
    }
}
