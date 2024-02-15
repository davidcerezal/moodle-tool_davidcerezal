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
 * @package   tool_davidcerezal
 * @category  admin
 * @copyright 2024, David Cerezal <david.cerezal@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_davidcerezal\local_tool_davidcerezal_table_sql;
use tool_davidcerezal\output\index_page;

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

// Ensure the user is logged in as an admin.
require_login();
require_capability('moodle/site:config', context_system::instance());

global $DB;

$courseid = required_param('course_id', PARAM_INT);
$coursecontext = context_course::instance($courseid);
$pageurl = new moodle_url('/admin/tool/davidcerezal/index.php', ['course_id' => $courseid]);

// Define the page layout.
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_url($pageurl);
$PAGE->set_title(get_string('pluginname', 'tool_davidcerezal'));
$PAGE->set_heading(get_string('pluginname', 'tool_davidcerezal'));

// Print row tables.
$output = $PAGE->get_renderer('tool_davidcerezal');

echo $output->header();
$outputtable = new local_tool_davidcerezal_table_sql($courseid, $pageurl);
$renderable = new index_page('David Admin plugin\'s ', $outputtable);
echo $output->render($renderable);

// Show initial edit icon.
if (has_capability('tool/davidcerezal:edit', $coursecontext)) {
    $editlink = new moodle_url('/admin/tool/davidcerezal/edit.php', ['course_id' => $courseid]);
    echo html_writer::link($editlink, get_string('editentry', 'tool_davidcerezal'));
}

echo $output->footer();
