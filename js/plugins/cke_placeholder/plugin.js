/* global Drupal:true */
/**
 * @file
 * Apply filters from CKE placeholders plugins.
 * This step has been injected to only call setData once when loading the editor.
 */

(function ($, Drupal, CKEDITOR) {

    "use strict";
    /**
     * Add methods for converting placeholders on instanceready and destroy.
     */
    CKEDITOR.plugins.add('cke_placeholder', {
        init: function (editor) {}
    });
    /*
            CKEDITOR.config.extraAllowedContent += 'div(cke-placeholder-hidden,cke-placeholder)';
            CKEDITOR.dialog.add('cke_placeholder', this.path + 'dialogs/cke_placeholder.js');
            Drupal.settings.cke_placeholder.editors.push(editor.name);
            var path = this.path;

            // Fire the placeholder replacement and attach the stylesheet when ready.
            editor.on('instanceReady', function (evt) {
                evt.editor.addContentsCss(path + '../css/cke_placeholder.editor.css');
                ckePlaceholder.readPlaceholders(editor);
            });

            // Fire the placeholder replacement and attach the stylesheet when ready.
            editor.on('paste', function (evt) {
                evt.data.dataValue = ckePlaceholder.replacePlaceholderInText(evt.data.dataValue);
            });

            // Reapply the stylesheet when shifting back to wysiwyg mode.
            editor.on('mode', function (evt) {
                var editor = evt.editor;
                if (editor.mode == 'wysiwyg') {
                    editor.addContentsCss(path + '../css/cke_placeholder.editor.css');
                    ckePlaceholder.readPlaceholders(editor);
                }
            });
            // Command to clear the widget wrapper divs.
            editor.addCommand('cke_placeholder_clear_wrapper', {
                exec: function (currentEditor) {
                    var newText = ckePlaceholder.removeWrapper(currentEditor.getData());
                    currentEditor.setData(newText);
                    jQuery('#' + currentEditor.name).val(newText);
                }
            });

            // Fire the clear-command upon destroy.
            editor.on('destroy', function (ev) {
                ev.editor.execCommand('cke_placeholder_clear_wrapper');
            });

            /**
             * Composes a widget settings object.
             *
             * @param {string} name
             *   Machine name for the plugin.
             * @param {object} customPlugin
             *   Registered settings from Drupal.settings.cke_placeholder.filter
             *
             * @returns {object}
             *   CKEditor widget settings object.
             */
    /*
            var widget = function (name, customPlugin) {
                var settings = {
                    upcast: function (element) {
                        var className = name.replace(/_/g, '-');

                        if (element.hasClass(name) || element.hasClass(className)) {
                            var plugin = element.attributes['data-cke_plugin'];
                            if (plugin != name) {
                                return '';
                            }

                            var data;
                            try {
                                data = ckePlaceholder.getJsonFromPlaceholder(element.getHtml());
                            } catch (err) {
                                alert('The data for the widget could not be interpreted.');
                                data = null;
                            }

                            if (data === null) {
                                return '';
                            }

                            var preview = new CKEDITOR.htmlParser.element('div', {class: ['cke-placeholder-preview']});

                            // If a custom render function has been created. If not, use the
                            // standard AJAX/localstorage solution.
                            if (customPlugin.render) {
                                var content = customPlugin.render(data);
                                preview.setHtml(content);
                                element.add(preview);
                            }
                            else {
                                element.add(preview);
                                ckePlaceholder.getContent(name, data, editor.name, element.attributes['data-cke_placeholder_id']);
                            }

                            // Enable editables.
                            if (customPlugin.editables) {
                                var editables = Drupal.settings.cke_placeholder.filter[plugin].editables;
                                for (var className in editables) {
                                    var editable = new CKEDITOR.htmlParser.element('div', {
                                        class: className + ' cke-placeholder-editable-' + plugin,
                                        "data-cke_editable": className
                                    });
                                    editable.setHtml(decodeURI(data[className]));
                                    element.add(editable);
                                }
                            }
                            return true;
                        }
                    },
                    downcast: function (element) {
                        var plugin = element.attributes['data-cke_plugin'];
                        var data = ckePlaceholder.getJsonFromPlaceholder(element.getHtml());

                        if (Drupal.settings.cke_placeholder.filter[plugin].editables && element.children) {
                            var key, value;

                            // @todo Only clean certain tags or allow options.
                            for (var i in element.children) {
                                if (element.children[i].attributes && element.children[i].hasClass('cke-placeholder-editable-' + plugin)) {
                                    key = element.children[i].attributes['data-cke_editable'];
                                    value = encodeURI(element.children[i].getHtml().replace(/(<(\/)?p>)/ig, ""));
                                    data[key] = value;
                                }
                            }
                        }

                        if (Drupal.settings.cke_placeholder.filter[plugin].downcast) {
                            data = Drupal.settings.cke_placeholder.filter[plugin].downcast(element.children, data);
                            if (typeof data !== 'object') {
                                data = {};
                            }
                        }
                        var content = '<div class="cke-placeholder-hidden">' +
                            ckePlaceholder.getPlaceholder(plugin, data) +
                            '</div>';
                        element.setHtml(content);

                    },
                    allowedContent: '*'
                };
                if (customPlugin.editables) {
                    settings.editables = ckePlaceholder.editables(name, customPlugin.editables);
                }

                if (customPlugin.extra_settings) {
                    for (var key in customPlugin.extra_settings) {
                        settings[key] = customPlugin.extra_settings[key];
                    }
                }
                if (!settings.dialog && (customPlugin.set_alignment || customPlugin.set_width)) {
                    settings.dialog = 'cke_placeholder';
                }

                return settings;
            };

            for (var name in Drupal.settings.cke_placeholder.filter) {
                editor.widgets.add('cke_placeholder_' + name, widget(name, Drupal.settings.cke_placeholder.filter[name]));
            }
        }
    });
*/
});