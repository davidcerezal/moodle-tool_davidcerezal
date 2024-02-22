// This file is part of the tool_certificate plugin for Moodle - http://moodle.org/
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
 * AMD module used when editing davidCerezal table
 *
 * @module     tool_davidcerezal
 * @copyright  2024 David Cerezal
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ModalDeleteCancel from 'core/modal_delete_cancel';
import {get_string as getString} from 'core/str';
import ModalEvents from 'core/modal_events';
import Notification from 'core/notification';
import {prefetchStrings} from 'core/prefetch';
import {call as fetchMany} from 'core/ajax';
import Templates from 'core/templates';

const SELECTORS = {
    DELETEROW: "[data-action='delete']",
    TABLEREGION: ".hero-unit",
};


/**
 * Creates an object representing a row deletion.
 *
 * @param {number} rowId - The ID of the row to delete.
 * @param {number} courseId - The ID of the course.
 * @param {number} userId - The ID of the user.
 * @returns {object} - The delete row element object.
 */
const deleteRowCall = (rowId, courseId, userId) => ({
    methodname: 'tool_davidcerezal_delete',
    args: {
        'rowid' : rowId,
        'courseid' : courseId,
        'userid' : userId,
    },
});

/**
 * Returns the course template object.
 *
 * @param {number} courseId - The ID of the course.
 * @returns {object} The course template object.
 */
const getCourseTemplate = (courseId) => ({
    methodname: 'tool_davidcerezal_get_template',
    args: {
        'courseid' : courseId
    },
});

/**
 * Delete flow handler
 * @param {Element} deleteRowElement
 * @param {number} userId
 */
const deleteRowHandler = async(deleteRowElement, userId) => {
    const modal = await ModalDeleteCancel.create({
        title: getString('deleteentry', 'tool_davidcerezal'),
        body: getString('deletetitle', 'tool_davidcerezal'),
    })
    .then(modal => {
        modal.show();
        return modal;
    });

    modal.getRoot().on(ModalEvents.delete, () => {
        const rowId = deleteRowElement.getAttribute('data-id');
        const courseId = deleteRowElement.getAttribute('data-courseid');
        const checkheader = document.querySelector(SELECTORS.TABLEREGION);
        const responses = fetchMany([
            deleteRowCall(rowId, courseId, userId),
            getCourseTemplate(courseId),
        ]);
        responses[0]
        .fail(() => {
            Notification.addNotification({
                message: 'Error deleting row',
                type: 'error'
            });
            return;
        });

        responses[1]
        .done((data) => {
            Templates.render('tool_davidcerezal/index_page', data.content).then((html, js) => {
                Templates.replaceNodeContents(checkheader, html, js);
                Templates.runTemplateJS(js);
            });
            Notification.addNotification({
                message: "Row deleted successfully",
                type: 'success'
            });
            return;
        })
        .fail(() => {
            Notification.addNotification({
                message: 'Error updating row html',
                type: 'error'
            });
            return;
        });
        modal.hide();
    });

};


const init = (userId) => {
    prefetchStrings('tool_davidcerezal', [
        'deleteconfirm',
        'deleteerror',
        'deletetitle',
        'deleteentry',
    ]);

    document.addEventListener('click', async (event) => {
        const deleteRowElement = event.target.closest(SELECTORS.DELETEROW);
        if (deleteRowElement) {
            event.preventDefault();
            await deleteRowHandler(deleteRowElement, userId);
        }
    });
};

export default {
    init: init
};
