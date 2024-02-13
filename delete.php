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
 * @copyright 2024, David Cerezal <david.cerezal@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

require_login();
require_sesskey();
require_capability('moodle/site:config', context_system::instance());

if ($deleteid = optional_param('delete', null, PARAM_INT)) {
    $DB->delete_records('tool_davidcerezal', ['id' => $deleteid]);
    redirect(new moodle_url('/admin/tool/davidcerezal/index.php', ['courseid' => $courseid]));
}
