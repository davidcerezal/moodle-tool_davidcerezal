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

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

// Ensure the user is logged in as an admin
require_login();
require_capability('moodle/site:config', context_system::instance());

global $DB;

$courseid = required_param('course_id', PARAM_INT);

// Define the page layout
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'tool_davidcerezal'));
$PAGE->set_heading(get_string('pluginname', 'tool_davidcerezal'));
echo $OUTPUT->header();

//Print row tables
print_database_table('tool_davidcerezal');

$course_context = context_course::instance($courseid);
if (has_capability('tool/davidcerezal:edit', $course_context)) {
    $edit_link = new moodle_url('/admin/tool/davidcerezal/edit.php', ['course_id' => $course_id]);
    echo html_writer::link($edit_link, get_string('editentry', 'tool_davidcerezal'));
}
echo $OUTPUT->footer();


/**
 * Print a table with all data and headers from a database table.
 *
 * @param string $table_name The name of the database table.
 */
function print_database_table($table_name) {
    global $DB;

    // Get all data from the database table
    $data = $DB->get_records($table_name);

    // Get column names (headers) of the database table
    $columns = $DB->get_columns($table_name);

    // Create an instance of the html_table class
    $table = new html_table();
    $table->attributes['class'] = 'generaltable'; // Add CSS class for styling (optional)

    // Add table headers
    $table->head = array_keys($columns);
    $table->head[] = 'actions';

    // Add table rows
    foreach ($data as $row) {
        $table_row = array();
        foreach ($row as $key => $value) {
            $table_row[] = format_string($value);
        }
        $action = new moodle_url('/admin/tool/davidcerezal/edit.php', ['course_id' => $row->courseid, 'row_id' => $row->id]);
        $table_row[] = html_writer::link($action, get_string('edit', 'tool_davidcerezal', $row->name));
        $table->data[] = $table_row;
    }

    // Print the table
    echo html_writer::table($table);
}