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

    /** @var stdClass $course course cmd. */
    private $course = null;

    /** @var array $coursemodules course cmd used modules. */
    private $coursemodules = null;

    /**
     * Constructor.
     *
     * @param string    $tableheader
     * @param table_sql $outpatabletable
     * @param stdClass  $course
     * @param array     $coursemodules
     */
    public function __construct(
        string $tableheader, 
        table_sql $outpatabletable,
        stdClass $course = null, 
        array $coursemodules = null
    ) {
        $this->tableheader = $tableheader;
        $this->outpatabletable = $outpatabletable;
        $this->course = $course;
        $this->coursemodules = $coursemodules;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output The renderer that is being used to display the data.
     * @return stdClass An object containing the data to be used in the template.
     */
    public function export_for_template(renderer_base $output): stdClass {
        global $USER;

        $data = new stdClass();
        $data->tableheader = $this->tableheader;
        $pagedefaultperpage = $this->outpatabletable->get_default_per_page();

        ob_start(); // Start output buffering.
        $this->outpatabletable->out($pagedefaultperpage, true); // Output captured here.
        $htmloutput = ob_get_clean();
        $data->outpatabletable = $htmloutput;
        $data->userid = $USER->id;
        $data->coursename = null;
        if ($this->course) {
            $data->courseid = $this->course->shortname;
        } 
        $data->coursename = $this->course?->shortname;
        $data->coursemodules = $this->coursemodules;

        return $data;
    }
}
