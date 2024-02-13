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
$coursecontext = context_course::instance($courseid);

// Define the page layout
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'tool_davidcerezal'));
$PAGE->set_heading(get_string('pluginname', 'tool_davidcerezal'));
echo $OUTPUT->header();

// Print row tables
print_database_table('tool_davidcerezal', $coursecontext);

if (has_capability('tool/davidcerezal:edit', $coursecontext)) {
    $editlink = new moodle_url('/admin/tool/davidcerezal/edit.php', ['course_id' => $courseid]);
    echo html_writer::link($editlink, get_string('editentry', 'tool_davidcerezal'));
}
echo $OUTPUT->footer();


/**
 * Print a table with all data and headers from a database table.
 *
 * @param string $tablename The name of the database table.
 * @param context $coursecontext The context of the course.
 */
function print_database_table($tablename, $coursecontext) {
    global $DB;

    // Get all data from the database table
    $data = $DB->get_records($tablename);

    // Get column names (headers) of the database table
    $columns = $DB->get_columns($tablename);

    // Create an instance of the html_table class
    $table = new html_table();
    $table->attributes['class'] = 'generaltable'; // Add CSS class for styling (optional)

    // Add table headers
    $table->head = array_keys($columns);
    $table->head[] = 'actions';

    // Add table rows
    foreach ($data as $row) {
        $tablerow = array();
        foreach ($row as $key => $value) {
            if ($key == 'description') {
                $valueformatted = file_rewrite_pluginfile_urls($value, 'pluginfile.php',
                $coursecontext->id, 'tool_davidcerezal', 'description', $row->id);
                $tablerow[] = format_text($valueformatted, FORMAT_HTML, ['noclean' => true]);
            } else {
                $tablerow[] = format_string($value);
            }
        }
        $action = new moodle_url('/admin/tool/davidcerezal/edit.php', ['course_id' => $row->courseid, 'row_id' => $row->id]);
        $tablerow[] = html_writer::link($action, get_string('edit', 'tool_davidcerezal', $row->name));
        $table->data[] = $tablerow;
    }

    // Print the table
    echo html_writer::table($table);
}
