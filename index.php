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
 * @copyright  2014 onwards Ankit Agarwal <ankit.agrr@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

// Ensure the user is logged in as an admin
require_login();
require_capability('moodle/site:config', context_system::instance());

global $DB;

$course_id = required_param('course_id', PARAM_INT);

// Define the page layout
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'tool_davidcerezal'));
$PAGE->set_heading(get_string('pluginname', 'tool_davidcerezal'));
echo $OUTPUT->header();

// Your page content goes here
//$output = $PAGE->get_renderer('tool_demo');
$userinput = get_string('helloworld', 'tool_davidcerezal', $course_id);
$course = $DB->get_record('course', ['id' => $course_id]);

if ($course) {
    $course_fullname = $course->fullname;
} else {
    $course_fullname = "course not found"; 
}    

$sql = "
    SELECT COUNT(DISTINCT ue.userid) AS enrolled_users_count
    FROM {user_enrolments} ue
    JOIN {enrol} e ON ue.enrolid = e.id
    WHERE e.courseid = :courseid
";

$enrolled_users_count = $DB->count_records_sql($sql, ['courseid' => $course_id]);

echo html_writer::div($userinput);
echo html_writer::div(s($userinput)); // Used when you want to escape the value.
echo html_writer::div($course_fullname); 
echo html_writer::div($enrolled_users_count); 
echo $OUTPUT->footer();