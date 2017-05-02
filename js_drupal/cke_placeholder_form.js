/**
 * @file
 * Form additions to support features of CKE Placeholder.
 */
(function($) {
  Drupal.behaviors.ckePlaceholder = {
    attach: function(settings, context) {
      $('form').on('submit', function(evt) {
        var is_upcast = false;
        var self = $(this);
        for (var i in Drupal.settings.cke_placeholder.editors) {
          var key = Drupal.settings.cke_placeholder.editors[i];
          if (CKEDITOR.instances[key]) {
            var editor = CKEDITOR.instances[key];
            if ((editor.mode != 'wysiwyg') && !is_upcast) {
              evt.preventDefault();

              CKEDITOR.instances[key].setMode('wysiwyg', function() {
                is_upcast = true;
                editor.destroy();
                self.submit();
              });
            }
            else {
              editor.destroy();
            }
          }
        }
      });
    }
  };
})(jQuery);
