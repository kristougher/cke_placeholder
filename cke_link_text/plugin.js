/**
 * @file
 * Extend built-in link plugin with link text edit field.
 */

(function($) {

  CKEDITOR.plugins.add('cke_link_text', {

    init: function(editor) {
      // Overrides definition.
      CKEDITOR.on('dialogDefinition', function(e) {
        if ((e.editor != editor) || (e.data.name != 'link')) {
          return;
        }

        var definition = e.data.definition;

        // Adds link text field.
        var infoTab = definition.getContents('info');
        infoTab.elements.push({
          type: 'vbox',
          id: 'linkExtras',
          children: [{
            type: 'text',
            id: 'linkText',
            label: Drupal.t('Link text'),
            required: false,
            setup: function(data) {
              var selection = editor.getSelection();
              if (selection) {
                this.setValue(selection.getSelectedText());
              }
            },
            validate: function() {
              var dialog = this.getDialog();
              if (dialog.getValueOf('info', 'linkText') == '') {
                alert(Drupal.t('!name field is required.', {'!name': Drupal.t('Link text')}));
                this.focus();
                return false;
              }
              return true;
            },
            commit: function(data) {
              var selection = editor.getSelection();
              if (selection && selection.getSelectedElement()) {
                $(selection.getSelectedElement().$).text(this.getValue());
              }
            }
          }]
        });
      });
    }
  });
})(jQuery);
