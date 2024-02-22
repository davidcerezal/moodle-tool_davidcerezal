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
 * Tool davidcerezal delete row
 *
 * @package    tool_davidcerezal
 * @copyright  2024 David Cerezal 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use tool_davidcerezal\external\delete_row;
use tool_davidcerezal\external\get_template;

$functions = [
    'tool_davidcerezal_delete' => [
        'classname'     =>  delete_row::class,
        'description'   => 'Delete a row from the table',
        'type'          => 'write',
        'ajax'          => true,
        'loginrequired' => true,
    ],
    'tool_davidcerezal_get_template' => [
        'classname'     =>  get_template::class,
        'description'   => 'Get template of david cerezal tool acording a course',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true,
    ],
];
