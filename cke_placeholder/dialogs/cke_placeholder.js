CKEDITOR.dialog.add('cke_placeholder', function(editor) {
  // We only show the dialog for the cke_placeholder types declared below.

  var elements = [];

  var alignment = {
    id: 'align',
    type: 'select',
    label: 'Align',
    items: [
      [Drupal.t('Full'), 'full']
    ],
    setup: function(widget) {
      var dataDiv = widget.element.$.firstElementChild;
      var widgetData = ckePlaceholder.getJsonFromPlaceholder(dataDiv.innerHTML);
      if (widgetData.alignment) {
        this.setValue(widgetData.alignment);
      }
      else {
        // Set default in case there is none selected.
        this.setValue('full');
      }
    },
    commit: function(widget) {
      // Get the placeholder-containing div, extract the data and set
      // alignment. Then insert the updated placeholder and update classes on
      // the wrapping element.
      var dataDiv = widget.element.$.firstElementChild;
      var pluginName = widget.element.$.attributes['data-cke_plugin'];
      var widgetData = ckePlaceholder.getJsonFromPlaceholder(dataDiv.innerHTML);
      widgetData.alignment = this.getValue();
      dataDiv.innerHTML = ckePlaceholder.getPlaceholder(pluginName, widgetData);

      // Find the preview wrapper inside the element.
      var elm;
      for(var i in widget.element.$.children) {
        if (widget.element.$.childNodes.item(i).className.split(' ').indexOf('cke-placeholder-preview') > -1) {
          elm = widget.element.$.childNodes.item(i).firstElementChild;
          break;
        }
      }
      // If we found an element then we set the wrap alignment class from the
      // widgetData.
      if (elm) {
        var regex = /wrap-[^\s]+/g;
        var new_class = 'wrap-' + widgetData.alignment;
        elm.className = elm.className.replace(regex, '');
        elm.classList.add(new_class);
      }
      widget.setData('align', this.getValue());
    }
  };

  alignment.items.push([editor.lang.common.alignLeft, 'left']);
  alignment.items.push([editor.lang.common.alignRight, 'right']);

  elements.push(alignment);

  var alignmentDescription = {
    id: 'align',
    type: 'html',
    html: Drupal.t('Adjust inline alignment')
  };

  elements.push(alignmentDescription);

  var dialog = {
    title: 'Change widget settings',
    minWidth: 350,
    minHeight: 250,
    contents: [
      {
        id: 'info',
        elements: elements
      }
    ]
  };

  return dialog;
});
