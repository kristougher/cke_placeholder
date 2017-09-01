/**
 * Wrapper for general functions regarding CKE Placeholder.
 * <!--[test:{"gnu":12}]-->
 */
var ckePlaceholder = {
  placeholderId: 0,
  regex: /((<!--)|(&lt;!--))\[(\w[^:]*)?:(([^\]\\]|\\.)+)]((-->)|(--&gt;))/g,
  upcastRegex: /\[(\w[^:]*)?:(([^\]\\]|\\.)+)]/i,
  wrapperRegex: /<div class="[^"]*cke-placeholder.*" data-cke_plugin="(\w*)">\s*<div class="cke-placeholder-hidden">\s*(\[\w*:([^\]\\]|\\.)+])\s*<\/div>\s*<\/div>/g,
  checkForContent: [],
  dragElementId: 0,

  /**
   * Close jQueryUI dialog.
   */
  dialogDestroy: function () {
    var dialog = jQuery('.cke-placeholder-dialog');
    dialog.dialog('destroy');
    dialog.remove();
  },

  /**
   * Event callback when dragging from media library.
   *
   * @todo move to separate function.
   *
   * @param ev
   *   The event object.
   */
  dragStart: function(ev, cke_placeholder_tag) {
    // Using currentTarget, since it will always get the droptarget element in
    // all browsers, where target will only get the clicked element (img) in for
    // example IE 9.
    var item = jQuery(ev.currentTarget);
    var placeholder_values = {};
    for (var i in ev.currentTarget.attributes) {
      var attr = ev.currentTarget.attributes[i];
      if (attr.name && attr.name.indexOf('data-') === 0) {
        var key = attr.name.replace('data-', '');
        placeholder_values[key] = attr.value;
      }
    }

    var data = '<!--[' + cke_placeholder_tag + ':' + JSON.stringify(placeholder_values) + ']-->';

    // This works better on non-IE browsers with 'text/html'. But lowest
    // common  denominator and all that.
    var  dataContentType = 'text';
    ev.dataTransfer.setData(dataContentType, data);
    this.dragElementId = item.data('id');
  },
  /**
   * Compose an object for the widget´s editable property.
   *
   * @param plugin
   * @param pluginEditables
   * @returns {object}
   */
  editables: function(plugin, pluginEditables) {
    var editables = {};
    var registeredEditables = {};
    for (var className in pluginEditables) {
      editables[className] = {
        selector: '.' + className,
        allowedContent: pluginEditables[className].allowed_content
      }
    }

    return editables;
  },
  /**
   * Retrieve the content for a widget in the specified editor.
   *
   * Looks for cached markup in sessionStorage. If none is found, load it via
   * AJAX.
   *
   * @param {string} plugin
   * @param {object} data
   * @param {string} editorName
   * @param {string} storageKey
   * @param {bool} reset
   *   Set to true to skip the cache.
   *
   * @returns {boolean}
   *   The markup is populated inside the function.
   */
  getContent: function(plugin, data, editorName, storageKey, reset) {
    var path = 'cke-placeholder/widget-preview/' + plugin;
    var self = this;

    if (!storageKey) {
      storageKey = this.storageKey(plugin, data);
    }

    var markup;
    var element;
    var preview;

    if (this.checkForContent.indexOf(storageKey) < 0) {
      // Add lock to prevent from fetching to times simultaneously.
      ckePlaceholder.checkForContent.push(storageKey);
      var jqXHR = jQuery.getJSON(Drupal.settings.basePath + path, data);

      jqXHR.promise().done(function(response) {
        element = CKEDITOR.instances[editorName].document.findOne('[data-cke_placeholder_id="' + storageKey + '"]');

        if (typeof element !== undefined && element != null) {
          preview = element.find('.cke-placeholder-preview').$[0];
        }

        markup = response.markup;

        var lockIndex = ckePlaceholder.checkForContent.indexOf(storageKey);
        ckePlaceholder.checkForContent.splice(lockIndex, 1);
        if (element !== null) {
          preview.innerHTML = markup;

          // Ruderdal custom post processing.
          // Fetch the outer class from the data and set a class on the
          // CKEditor wrapping div based on the classes found.
          // Set class "floater" for previews having the classes
          // "col-sm-4", "col-sm-5" and "col-sm-6" or default to the class
          // "non-floater". These classes is used by the custom editor
          // styling to determine if it should be displayed as a full
          // width element or a non-full width element.
          if (data.type == 'prefab') {
            markupObject = jQuery( markup );
            if (markupObject.hasClass('col-sm-4') || markupObject.hasClass('col-sm-5') || markupObject.hasClass('col-sm-6')) {
              CKEDITOR.instances[editorName].document.findOne('[data-cke_placeholder_id="' + storageKey + '"]').getParent().addClass('floater').addClass('prefab');
            }
            else {
              CKEDITOR.instances[editorName].document.findOne('[data-cke_placeholder_id="' + storageKey + '"]').getParent().addClass('no-floater').addClass('prefab');
            }
          }
        }
      });
    }
  },
  /**
   * Extract plugin name from placeholder.
   *
   * @param placeholder
   * @returns {string}
   */
  getPluginNameFromPlaceholder: function(placeholder) {
    var match = this.regex.exec(placeholder);
    return match[1];
  },

  /**
   * Extract JSON data from a placeholder.
   */
  getJsonFromPlaceholder: function(placeholder) {
    var matches = placeholder.match(this.upcastRegex);
    if (null === matches) {
      return null;
    }
    var data = JSON.parse(matches[0].match(/\{(([^\]\\]|\\.)+)}/g)[0].replace(/\\"/g, '"'));
    return data;
  },
  /**
   * Return a placeholder without comments.
   *
   * @param plugin
   * @param data
   * @returns {string}
   */
  getPlaceholder: function(plugin, data) {
    var stringifiedData = JSON.stringify(data);
    if (stringifiedData === null) {
      stringifiedData = '{}';
    }

    return '[' + plugin + ':' + stringifiedData + ']';
  },

  /**
   * Get markup for a single widget.
   *
   * @param {string} plugin
   *   Plugin machine name.
   * @param {object} data
   *   Placeholder data.
   *
   * @returns {string}
   *   HTML markup for a single widget.
   */
  getWidgetMarkup: function(plugin, data) {
    var storageKey = this.storageKey(plugin, JSON.parse(data));
    var className = plugin.replace(/_/g, '-');
    var markup = '<div class="cke-placeholder ' + className + ' ' + plugin + '" data-cke_plugin="' + plugin + '" data-cke_placeholder_id="' + storageKey + '">' +
      '<div class="cke-placeholder-hidden">' +
      this.getPlaceholder(plugin, data) +
      '</div></div>'

    return markup;
  },

  /**
   * Retrieve placeholders from editor content and replace with markup.
   */
  readPlaceholders: function(currentEditor) {
    var cke_content = currentEditor.getData();

    if (cke_content.match(ckePlaceholder.regex) !== null) {
      var newText = ckePlaceholder.replacePlaceholderInText(cke_content);
      if (newText != cke_content) {
        currentEditor.setData(newText);
      }
    }
  },

  /**
   * Read a single placeholder
   */
  replacePlaceholderInText: function(placeholder) {
    newText = placeholder.replace(ckePlaceholder.regex, function(match, commentStart, commentWrap, comment, plugin, json) {
      // Only handle registered plugins.
      if (Drupal.settings.cke_placeholder.filter[plugin]) {
        var markup = ckePlaceholder.getWidgetMarkup(plugin, json);
        return markup;
      }
      return match;
    });

    return newText;
  },

  /**
   * Remove the widget wrapper for cleaner markup.
   *
   * This function is called on destroy - that is in downcast state.
   *
   * @param {string} string
   *   The editor content.
   */
  removeWrapper: function(string) {
    return string.replace(
      this.wrapperRegex,
      function (match, plugin, comment) {
        if (Drupal.settings.cke_placeholder.filter[plugin]) {
          return "<!--" + comment + "-->";
        }
        return match;
      });
  },
  /**
   * Get a unique identifier for a widget.
   *
   * Really bulletproof.
   *
   * @param plugin
   * @param data
   * @returns {string}
   */
  storageKey: function(plugin, data) {
    var storageKey = plugin;
    var key_keys = jQuery.unique(Drupal.settings.cke_placeholder.filter[plugin].key);

    for (var i in key_keys) {
      storageKey += '_' + encodeURI(data[key_keys[i]].replace(' ', ''));
    }

    storageKey += '_' + Math.random();
    storageKey = storageKey.replace('.', '');

    return storageKey.substring(0,128)
  },

  /**
   * Update field_status with AJAX callback and reload preview.
   *
   * @param {int} fileID
   *   The file entity ID.
   * @param {int} newStatus
   *   New value for field_status.
   * @param {object} jQ
   *   A jQuery object. The function may be called inside the editor where
   *   jQuery is not available.
   */
  updateStatus: function(fileID, newStatus, link, jQ) {
    if (!jQ) {
      jQ = jQuery;
    }
    var self = this;
    jQ.get('/cke-placeholder/media-status-update/' + fileID + '/' + newStatus, function() {
      var element = link.parentNode.parentNode;
      var storageKey = element.attributes.getNamedItem('data-cke_placeholder_id').value;
      var plugin = element.attributes.getNamedItem('data-cke_plugin').value;
      var dataRaw = element.childNodes.item(0).innerHTML;
      var data = self.getJsonFromPlaceholder(dataRaw);

      self.getContent(plugin, data, 'edit-body-und-0-value', storageKey, true);
    });

  },

  /**
   * Get cke placeholder code.
   *
   * @param elem
   *   Placeholder dragable DOM element.
   * @cke_placeholder_tag
   *   Placeholder tag name.
   */
  getPlaceholderCode: function(elem, cke_placeholder_tag) {
    var placeholder_values = {};

    for (var i in elem.attributes) {
      var attr = elem.attributes[i];
      if (attr.name && attr.name.indexOf('data-') === 0) {
        var key = attr.name.replace('data-', '');
        placeholder_values[key] = attr.value;
      }
    }

    var data = '<!--[' + cke_placeholder_tag + ':' + JSON.stringify(placeholder_values) + ']-->';
    return data;
  }
};
