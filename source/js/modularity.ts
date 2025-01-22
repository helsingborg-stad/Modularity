import Autosave from "./editor/autosave"
import DragAndDrop from "./editor/dragAndDrop"
import Module from "./editor/module"
import Thickbox from "./editor/thickbox"
import Validate from "./editor/validate"

import Helpers from "./helpers/helpers"
import Widget from "./helpers/widget"

import Modal from "./prompt/modal"

import { ModulesRestAPI, ModulesRestAPIEndpoints } from './helpers/ModulesRestAPI';
import { ModuleRefresher, ensureWPApiSettings } from './helpers/ModuleRefresher';

const parentWindow: CustomWindow = parent as CustomWindow;

if (!parentWindow.Modularity) {
    parentWindow.Modularity = {};

    const Modularity = parentWindow.Modularity;
    Modularity.Editor = Modularity.Editor || {};
    Modularity.Editor.Autosave = new Autosave(Modularity);
    Modularity.Editor.DragAndDrop = new DragAndDrop(Modularity);
    Modularity.Editor.Module = new Module(Modularity);
    Modularity.Editor.Thickbox = new Thickbox(Modularity);
    Modularity.Editor.Validate = new Validate(Modularity);

    Modularity.Helpers = Modularity.Helpers || {};
    Modularity.Helpers.Helpers = new Helpers(Modularity);
    Modularity.Helpers.Widget = new Widget(Modularity);

    Modularity.Prompt = Modularity.Prompt || {};
    Modularity.Prompt.Modal = new Modal();

    parentWindow.Modularity = Modularity;


    document.addEventListener('DOMContentLoaded', () => {

        document.querySelectorAll('input[type="checkbox"].sidebar-area-activator').forEach((checkboxElement) => {
            const checkbox = checkboxElement as HTMLInputElement;
            checkbox.addEventListener('click', (e) => {
                const target = e.target as HTMLInputElement;
                const isChecked = target.checked;
                const value = target.getAttribute('value');

                if ((e as MouseEvent).shiftKey) {
                    document.querySelectorAll(`input.sidebar-area-activator[type="checkbox"][value="${value}"]`).forEach((checkboxElement) => {
                        const relatedCheckbox = checkboxElement as HTMLInputElement;
                        relatedCheckbox.checked = isChecked;
                    });
                }
            });
        });

        // Show spinner when clicking save on Modularity options page
        const publishButton = document.querySelector<HTMLButtonElement>('#modularity-options #publish');
        if (publishButton) {
            publishButton.addEventListener('click', function (this: HTMLButtonElement) {
                const spinnerContainer = this.nextElementSibling as HTMLElement;
                const spinner = spinnerContainer?.querySelector('.spinner') as HTMLElement | null;
                if (spinner) {
                    spinner.style.visibility = 'visible';
                }
            });
        }

        // Remove the first menu item in the Modularity submenu (admin menu)
        const firstMenuItem = document.querySelector('a.wp-first-item[href="admin.php?page=modularity"]');
        if (firstMenuItem) {
            const parentListItem = firstMenuItem.parentNode as HTMLElement;
            if (parentListItem) {
                parentListItem.remove();
            }
        }

        // Trigger autosave when switching tabs
        document.querySelectorAll('#modularity-tabs a').forEach(tab => {
            tab.addEventListener('click', () => {
                if (wp?.autosave) {
                    window.removeEventListener('beforeunload', wp.autosave.server.handleUnload);
                    wp.autosave.server.triggerSave();
                }
            });
        });

        /* Auto scrolling content */
        const modularityMbModules = document.getElementById('modularity-mb-modules');
        if (modularityMbModules) {
            const offset = modularityMbModules.offsetTop;
            window.addEventListener('scroll', () => {
                const scrollTop = window.scrollY || document.documentElement.scrollTop;
                const isFixed = modularityMbModules.classList.contains('is-fixed');
                if (scrollTop + 50 > offset && !isFixed) {
                    modularityMbModules.classList.add('is-fixed');
                } else if (scrollTop + 50 < offset && isFixed) {
                    modularityMbModules.classList.remove('is-fixed');
                }
            });
        }

        document.querySelectorAll('.modularity-edit-module a').forEach((linkElement) => {
            const link = linkElement as HTMLElement;
            link.addEventListener('click', (e) => {
                e.preventDefault();
                if (Modularity?.Editor?.Thickbox) {
                    Modularity.Editor.Thickbox.postAction = 'edit-inline-not-saved';

                    if (Modularity.Editor.Thickbox.postAction) {
                        const href = (e.target as HTMLElement)?.closest('a')?.getAttribute('href');
                        if (href) {
                            Modularity.Prompt?.Modal?.open(href);
                        }
                    }
                }
            });
        });
    });
}

(function () {
    try {
        ensureWPApiSettings();

        const { root, nonce } = window.wpApiSettings;
        const fetch = window.fetch.bind(window);
        const endpoints = ModulesRestAPIEndpoints(root);
        const restAPI = new ModulesRestAPI(fetch, endpoints, nonce);

        new ModuleRefresher(restAPI).refreshModules();
    } catch (error) {
        console.warn(error);
    }
})();
