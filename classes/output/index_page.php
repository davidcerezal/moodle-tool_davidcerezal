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
 * Index page for the tool_davidcerezal plugin.
 *
 * @package   tool_davidcerezal
 * @category  admin
 * @copyright 2024, David Cerezal <david.cerezal@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_davidcerezal\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;
use table_sql;
use core\context;

/**
 * Class index_page
 *
 * @package tool_davidcerezal
 */
class index_page implements renderable, templatable {

    /** @var string $sometext Some text to show how to pass data to a template. */
    private $tableheader = null;

    /** @var table_sql $outpatabletable table with db info to be displayed. */
    private $outpatabletable = null;

    /** @var int $courseid id of the course. */
    private $courseid = null;

    /**
     * Constructor.
     *
     * @param string $tableheader
     * @param table_sql $outpatabletable
     * @param int $courseid
     */
    public function __construct(
        string $tableheader, 
        table_sql $outpatabletable,
        int $courseid
    ) {
        $this->tableheader = $tableheader;
        $this->outpatabletable = $outpatabletable;
        $this->courseid = $courseid;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output The renderer that is being used to display the data.
     * @return stdClass An object containing the data to be used in the template.
     */
    public function export_for_template(renderer_base $output): stdClass {
        $coursecontext = \context_course::instance($this->courseid);

        $data = new stdClass();
        $data->tableheader = $this->tableheader;
        $pagedefaultperpage = $this->outpatabletable->get_default_per_page();

        ob_start(); // Start output buffering.
        $this->outpatabletable->out($pagedefaultperpage, true); // Output captured here.
        $htmloutput = ob_get_clean();
        $data->outpatabletable = $htmloutput;

        $data->editlink = false;
        if (has_capability('tool/davidcerezal:edit', $coursecontext)) {
            $editlink = new \moodle_url('/admin/tool/davidcerezal/edit.php', ['course_id' => $this->courseid]);
            $data->editlink = \html_writer::link($editlink, get_string('editentry', 'tool_davidcerezal'));
        }


        return $data;
    }
}
