<?php
// Standard GPL and phpdocs

namespace tool_davidcerezal;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/tablelib.php');

use table_sql;

class local_tool_davidcerezal_table_sql extends table_sql {

      /**
     * Sets up the table.
     */
    public function __construct() {
        parent::__construct('davidcerezal');

        $context = \context_system::instance();

        $columnheaders = [
            'courseid' => get_string('courseid', 'tool_davidcerezal'),
            'timecreated' => get_string('timecreated', 'tool_davidcerezal'),
            'timemodified' => get_string('timemodified', 'tool_davidcerezal'),
            'completed' => get_string('completed', 'tool_davidcerezal'),
            'priority' => get_string('priority', 'tool_davidcerezal'),
            'name' => get_string('message', 'tool_davidcerezal'),
        ];

        $this->define_columns(array_keys($columnheaders));
        $this->define_headers(array_values($columnheaders));
    }

    public function col_name($value) {
        return format_string($value);
    }

    public function col_completed($value) {
        return $value ? get_string('yes') : get_string('no');
    }

    public function col_timecreated($value) {
        return userdate($value);
    }

    public function col_timemodified($value) {
        return userdate($value);
    }
}