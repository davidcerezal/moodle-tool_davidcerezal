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
 * @package   tool_davicerezal
 * @copyright 2024, David Cerezal <david.cerezal@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Include Moodle configuration and necessary libraries
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

// Ensure user is logged in and has necessary capability
require_login();
require_capability('moodle/site:config', context_system::instance());

// Initialize global $DB variable
global $DB;

// Retrieve parameters
$courseid = required_param('course_id', PARAM_INT);
$rowid = optional_param('row_id', null, PARAM_INT);
$context = context_course::instance($courseid);

// Fetch data for editing if row ID is provided
if ($rowid) {
    $recordrow = $DB->get_record('tool_davidcerezal', array('id' => $rowid));
}

// Create the form - Prepare form action URL
$actionurl = new moodle_url('/admin/tool/davidcerezal/edit.php', ['course_id' => $courseid, 'row_id' => $rowid]);
$form = new \tool_davidcerezal\form\simpledavidcerezal_form($actionurl, [1, $context]);

// Initialize form data
$data = (object)['courseid' => $courseid, 'rowid' => $rowid];

// Populate form data if row ID is provided
if ($rowid && $recordrow) {
    $data->name = $recordrow->name;
    $data->completed = $recordrow->completed;
    $data->description = $recordrow->description;
}
// Call file_prepare_standard_editor() before setting the data to the form
if ($rowid && $recordrow) {
    $data = file_prepare_standard_editor($data, 'description', [
        'trusttext' => true,
        'subdirs' => true,
        'maxfiles' => 1,
        'context' => $context,
        'noclean' => true,
        ], $context, 'tool_davidcerezal', 'description', $rowid);
}

// Set form data
$form->set_data($data);

// Process form submission
if ($data = $form->get_data()) {
    $record = new stdClass();
    $record->name = $data->name;
    $record->courseid = $data->courseid;
    $record->completed = isset($data->completed) ? 1 : 0;
    $record->timemodified = time();

    if (!$rowid && !$recordrow) {
        $record->timecreated = time();
        $rowid = $DB->insert_record('tool_davidcerezal', $record);
        $recordrow = $DB->get_record('tool_davidcerezal', array('id' => $rowid));
    }

    if ($rowid && $recordrow) {
        // Call file_postupdate_standard_editor() after calling get_data()
        $data = file_postupdate_standard_editor($data, 'description', [
            'trusttext' => true,
            'subdirs' => true,
            'maxfiles' => 1,
            'context' => $context,
        ], $context, 'tool_davidcerezal', 'description', $rowid);

        // Update the existing record
        $record->id = $rowid;
        $record->description = $data->description;
        $record->descriptionformat = $data->descriptionformat;
        $DB->update_record('tool_davidcerezal', $record);
    }

    // Redirect after successful operation
    redirect(new moodle_url('/admin/tool/davidcerezal/index.php', ['course_id' => $courseid]));
    exit;
} else {
    // Form was not submitted or validation failed, display the form
    echo $OUTPUT->header();
    $form->display();
    echo $OUTPUT->footer();
}
