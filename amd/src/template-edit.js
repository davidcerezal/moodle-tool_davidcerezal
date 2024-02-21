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

// import modal from 'core/modal_delete_cancel';
// import {get_string as getString} from 'core/str';
import Modal from 'core/modal';
import ModalEvents from 'core/modal_events';
import {prefetchStrings} from 'core/prefetch';
// import Ajax from 'core/ajax';

const SELECTORS = {
    DELETEROW: "[data-action='delete']",
};


const showdelete = async () => {
    const modal = await Modal.create({
        title: 'Test title',
        body: 'Comoo',
        footer: 'An example footer content',
    }).then(modal => {
        modal.show();
        modal.registerCloseOnCancel();
        return modal;
    });
    modal.getRoot().on(ModalEvents.hidden, () => this.destroy());
};

/**
 * Delete flow handler
 */
/* const deleteRowHandler = async() => {
    const modal = await await modalBodyRenderedPromise(ModalDeleteCancel, {
        title: getString('deleteentry', 'tool_davidcerezal'),
        body: getString('deletetitle', 'tool_davidcerezal'),
    });
    modal.getRoot().on(ModalEvents.delete, (e) => {
        e.preventDefault();
        modal.destroy();
        Ajax.call([{
            methodname: 'tool_davidcerezal_delete',
            args: {
                'id': element.dataset.id,
                'sesskey': M.cfg.sesskey,
            },
            done: function (response) {
                if (response.result) {
                    window.console.log(getString('deleteconfirm', 'tool_davidcerezal'));
                } else {
                    window.console.log(getString('deleteerror', 'tool_davidcerezal'));
                }
            },
            fail: function () {
                window.console.error(getString('deleteerror', 'tool_davidcerezal'));
            }
        }]).always(() => {
            modal.hide();
        });
    });
}; */

/**
 * Render a modal and return a body ready promise.
 *
 * @param {Modal} ModalClass the modal class
 * @param {object} modalParams the modal params
 * @return {Promise} the modal body ready promise
 */
/* const modalBodyRenderedPromise = function(ModalClass, modalParams) {
    return new Promise((resolve, reject) => {
        ModalClass.create(modalParams).then((modal) => {
            modal.setRemoveOnClose(true);
            // Handle body loading event.
            modal.getRoot().on(ModalEvents.bodyRendered, () => {
                resolve(modal);
            });
            // Configure some extra modal params.
            if (modalParams.saveButtonText !== undefined) {
                modal.setSaveButtonText(modalParams.saveButtonText);
            }
            if (modalParams.deleteButtonText !== undefined) {
                modal.setDeleteButtonText(modalParams.saveButtonText);
            }
            modal.show();
            return;
        }).catch(() => {
            reject(`Cannot load modal content`);
        });
    });
};
 */
const init = () => {
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
            await showdelete();
        }
    });
};

export default {
    init: init
};
