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
 * Test Dblib class.
 *
 * @package   tool_davidcerezal
 * @category  admin
 * @copyright 2024, David Cerezal <david.cerezal@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_davidcerezal;

use tool_davidcerezal\dblib;
use advanced_testcase;

class db_test extends advanced_testcase {

    public function test_insert() {
        global $DB;
        $this->resetAfterTest(true);

        //Data to be stored.
        $course = self::getDataGenerator()->create_course();
        $data = (object)[
                'name' => 'new name',
                'completed' => 0,
                'courseid' => $course->id,
                'description' => 'desc...',
        ];

        $id = dblib::insert($data);

        //Find the record without dblib to avoid possible lib fails.
        $record = $DB->get_record('tool_davidcerezal', ['id' => $id]);

        $this->assertEquals('new name', $record->name);
        $this->assertEquals('0', $record->completed);
        $this->assertEquals($course->id, $record->courseid);
        $this->assertEquals('desc...', $record->description);
    }

    public function test_update_fails() {
        global $DB;
        $this->resetAfterTest(true);

        //Data to be stored.
        $course = self::getDataGenerator()->create_course();
        $data = (object)[
                'name' => 'new name',
                'completed' => 0,
                'courseid' => $course->id,
                'description_editor' => [
                        'text' => 'desc...',
                        'format' => FORMAT_HTML
                ]
        ];

        $id = dblib::insert($data);

        $data->name = "Newst name v2";
        $result = dblib::update($data);

        $this->assertEquals(False, $result);
    }

    public function test_update_ok() {
        global $DB;
        $this->resetAfterTest(true);

        //Data to be stored.
        $course = self::getDataGenerator()->create_course();
        $data = (object)[
                'name' => 'new name',
                'completed' => 0,
                'courseid' => $course->id,
                'description_editor' => [
                        'text' => 'desc...',
                        'format' => FORMAT_HTML
                ]
        ];

        $id = dblib::insert($data);

        $data->id = $id;
        $data->name = "Newst name v2";
        $result = dblib::update($data);

        //Find the record without dblib to avoid possible lib fails.
        $record = $DB->get_record('tool_davidcerezal', ['id' => $id]);

        $this->assertEquals('Newst name v2', $record->name);
    }
}