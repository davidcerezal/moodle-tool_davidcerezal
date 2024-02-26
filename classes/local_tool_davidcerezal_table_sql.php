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

namespace tool_davidcerezal;
use context_course;
use html_writer;
use moodle_url;
use stdClass;
use table_sql;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/tablelib.php');

/**
 * Table class for the davidcerezal table.
 */
class local_tool_davidcerezal_table_sql extends table_sql {

    /** @var int The course id records will be displayed for. */
    protected $courseid;

    /**
     * Sets up the table.
     * @param int $courseid The course id records will be displayed for.
     * @param string $url The url to use for the table.
     */
    public function __construct(int $courseid, string $url) {
        parent::__construct('davidcerezal');
        $this->courseid = $courseid;

        $context = \context_system::instance();

        $columnheaders = [
            'courseid' => get_string('courseid', 'tool_davidcerezal'),
            'completed' => get_string('completed', 'tool_davidcerezal'),
            'priority' => get_string('priority', 'tool_davidcerezal'),
            'name' => get_string('message', 'tool_davidcerezal'),
            'description' => get_string('message', 'tool_davidcerezal'),
            'timecreated' => get_string('timecreated', 'tool_davidcerezal'),
            'timemodified' => get_string('timemodified', 'tool_davidcerezal'),
            'editlinks' => get_string('editentry', 'tool_davidcerezal'),
            'deletelinks' => get_string('deleteentry', 'tool_davidcerezal'),
        ];

        $this->set_attribute('id', 'tool_davidcerezal_table');
        $this->set_attribute('class', 'tool_davidcerezal_table');
        $this->define_columns(array_keys($columnheaders));
        $this->define_headers(array_values($columnheaders));
        $this->define_table_configs();
        $this->define_baseurl($url);
    }

    /**
     * Define table configs.
     */
    protected function define_table_configs() {
        $this->collapsible(false);
        $this->sortable(false);
        $this->pageable(true);
        $this->set_default_per_page(25);
    }

    /**
     * Builds the SQL query.
     *
     * @param bool $count When true, return the count SQL.
     * @return array containing sql to use and an array of params.
     */
    protected function get_sql_and_params($count = false) {
        $sql = "SELECT *
                  FROM {tool_davidcerezal} td
                 WHERE td.courseid = :courseid
                 ORDER BY td.timemodified DESC";

        $params = ['courseid' => $this->courseid];

        return [$sql, $params];
    }

    /**
     * Query the DB.
     *
     * @param int $pagesize size of page for paginated displayed table.
     * @param bool $useinitialsbar do you want to use the initials bar.
     */
    public function query_db($pagesize, $useinitialsbar = true) {
        global $DB;

        list($sql, $params) = $this->get_sql_and_params();
        $this->rawdata = $DB->get_records_sql($sql, $params, $this->get_page_start(), $this->get_page_size());
    }

    /**
     * Formats the columns.
     * @param stdClass $row the value to format
     * @return string the formatted value
     */
    public function col_name(stdClass $row) {
        return format_string($row->name);
    }

    /**
     * Formats the columns.
     * @param stdClass $row the value to format
     * @return string the formatted value
     */
    public function col_completed(stdClass $row) {
        return $row->completed ? get_string('yes') : get_string('no');
    }

    /**
     * Formats the columns.
     * @param stdClass $row the value to format
     * @return string the formatted value
     */
    public function col_timecreated(stdClass $row) {
        return userdate($row->timecreated);
    }

    /**
     * Formats the columns.
     * @param stdClass $row the value to format
     * @return string the formatted value
     */
    public function col_timemodified(stdClass $row) {
        return userdate($row->timemodified);
    }

    /**
     * Formats the columns.
     * @param stdClass $row the value to format
     * @return string the formatted value
     */
    public function col_editlinks(stdClass $row) {
        $editlink = new moodle_url('/admin/tool/davidcerezal/edit.php', ['course_id' => $this->courseid, 'row_id' => $row->id]);
        return html_writer::link($editlink, get_string('editentry', 'tool_davidcerezal'));
    }

    /**
     * Formats the columns.
     * @param stdClass $row the value to format
     * @return string the formatted value
     */
    public function col_deletelinks(stdClass $row) {
        $editlink = new moodle_url('/admin/tool/davidcerezal/delete.php',
        [
            'rowid' => $row->id,
            'sesskey' => sesskey(),
             'course_id' => $this->courseid,
        ]);

        return html_writer::link($editlink, get_string('deleteentry', 'tool_davidcerezal'),
        [
            'data-action' => 'delete',
            'data-id' => $row->id,
            'data-courseid' => $this->courseid,
        ]);
    }

    /**
     * Formats the columns.
     * @param stdClass $row the value to format
     * @return string the formatted value
     */
    public function col_description(stdClass $row) {
        $context = context_course::instance($this->courseid);
        $valueformatted = file_rewrite_pluginfile_urls($row->description, 'pluginfile.php',
        $context->id, 'tool_davidcerezal', 'description', $row->id);
        return format_text($valueformatted, FORMAT_HTML, ['noclean' => true]);
    }


    /**
     * Notification to display when there are no results.
     */
    public function print_nothing_to_display() {
        global $OUTPUT;
        echo $OUTPUT->notification(get_string('nothing', 'tool_davidcerezal'), 'info');
    }
}
