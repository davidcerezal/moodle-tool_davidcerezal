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
 * @package   tool_davidcerezal
 * @category  admin
 * @copyright 2024, David Cerezal <david.cerezal@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_davidcerezal\form;
defined('MOODLE_INTERNAL') || die();

use moodleform;
use stdClass;
require_once($CFG->libdir.'/formslib.php');

/*
 * This class defines the form for adding a new record to the tool_davidcerezal table
 */
class simpledavidcerezal_form extends moodleform {

    /**
     * This function adds the form elements to the form
     */
    public function definition() {
        $maxfiles = $this->_customdata[0];
        $context = $this->_customdata[1];

        $textfieldoptions = [
            'trusttext' => true,
            'subdirs' => true,
            'maxfiles' => $maxfiles,
            'context' => $context,
        ];

        $mform = $this->_form;
        $mform->addElement('text', 'name', get_string('name', 'tool_davidcerezal'));
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('checkbox', 'completed', get_string('completed', 'tool_davidcerezal'));
        $mform->addElement('editor', 'description_editor', get_string('message', 'tool_davidcerezal'), null, $textfieldoptions);

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('hidden', 'rowid');
        $mform->setType('rowid', PARAM_INT);
        
        $this->add_action_buttons();
    }

    /**
     * This function validates the form data and cheks that the name is unique
     * @param array $data  the data from the form
     * @param array $files the files from the form
     */
    public function validation($data, $files) {
        global $DB;
        $errors = [];

        $existsandisnumeric = !empty($data['courseid']) && !is_numeric($data['courseid']);
        // Validate 'courseid' field.
        if (empty($data['courseid']) || $existsandisnumeric) {
            $errors['courseid'] = get_string('error_courseid_invalid', 'tool_davidcerezal');
        }

        // Validate 'name' field.
        if (empty($data['name'])) {
            $errors['name'] = get_string('error_name_required', 'tool_davidcerezal');
        } else if (strlen($data['name']) > 255) {
            $errors['name'] = get_string('error_name_length', 'tool_davidcerezal');
        } else {
            // Check if the name is already in use.
            $existingrecord = $DB->get_record('tool_davidcerezal', ['name' => $data['name'], 'courseid' => (int)$data['courseid']]);
            if ($existingrecord && $existingrecord->id !== $data['rowid']) {
                $errors['name'] = get_string('error_name_unique', 'tool_davidcerezal');
            }
        }

        return $errors;
    }
}

