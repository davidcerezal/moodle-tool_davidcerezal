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
 * External functions and callbacks.
 * 
 * @package   tool_davidcerezal
 * @category  admin
 * @copyright 2024, David Cerezal <david.cerezal@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_certificate\permission;

/**
 * Display the Certificate link in the course administration menu.
 *
 * @param settings_navigation $navigation The settings navigation object
 * @param stdClass $course The course
 * @param context $context Course context
 */
function tool_davidcerezal_extend_navigation_course($navigation, $course, $context) {
    global $PAGE, $COURSE;

    $certificatenode = $navigation->add(
        get_string('pluginname', 'tool_davidcerezal'),
        new moodle_url('/admin/tool/davidcerezal/index.php', ['course_id' => $course->id]),
        navigation_node::TYPE_SETTING,
        get_string('pluginname', 'tool_davidcerezal'),
        'tool_davidcerezal',
        new pix_icon('icon', '', 'tool_davidcerezal'));
}

/**
 * Callback function to serve plugin files.
 * Check appropriate access and permissions before serving a file.
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @throws moodle_exception
 */
function tool_davidcerezal_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    global $CFG, $DB;

    // Security checks.
    require_course_login($course, true, $cm);

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'tool_davidcerezal', $filearea, $args[0], '/', $args[1]);
    if (!$file) {
        send_file_not_found();
    }

    // Serve the file.
    send_stored_file($file, null, 0, $forcedownload, $options);
}

