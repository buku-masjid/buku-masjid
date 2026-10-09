/*
Import the jQuery
*/

import jQuery from "jquery";

window.$ = window.jQuery = jQuery;

/*
Import the Tabler Js with Demo theme
Ref: (and thanks to) https://github.com/takielias/tablar
*/
import '../../node_modules/@tabler/core/dist/js/tabler';

/*
 * Minimal jQuery -> Bootstrap 5 bridge.
 *
 * Bootstrap 5 dropped the jQuery plugins that Bootstrap 4 provided
 * (jQuery.fn.modal, .tooltip, .popover, .dropdown, .tab, .collapse).
 * Some third-party libraries (e.g. Summernote's BS5 build) and legacy
 * views still call them, so re-expose them on top of Bootstrap 5's
 * native JS API.
 */
(function (bootstrap, $) {
    if (! bootstrap || ! $) {
        return;
    }

    var components = {
        modal: bootstrap.Modal,
        tooltip: bootstrap.Tooltip,
        popover: bootstrap.Popover,
        dropdown: bootstrap.Dropdown,
        tab: bootstrap.Tab,
        collapse: bootstrap.Collapse,
    };

    Object.keys(components).forEach(function (name) {
        var Component = components[name];

        if (typeof Component === 'undefined' || typeof $.fn[name] === 'function') {
            return;
        }

        $.fn[name] = function (option) {
            var args = Array.prototype.slice.call(arguments, 1);
            var options = {};

            if (typeof option === 'object' && option !== null) {
                Object.keys(option).forEach(function (key) {
                    if (option[key] !== null && typeof option[key] !== 'undefined') {
                        options[key] = option[key];
                    }
                });
            }

            return this.each(function () {
                var instance = Component.getOrCreateInstance(this, options);

                if (typeof option === 'string' && typeof instance[option] === 'function') {
                    instance[option].apply(instance, args);
                }
            });
        };
    });
})(window.bootstrap, window.jQuery);
