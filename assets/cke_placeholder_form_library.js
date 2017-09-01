/* Global Drupal:true; jQuery:true; ckePlaceholder:true */
(function ($) {

  /**
   * Behaviors for the media field to drop items on.
   */
  Drupal.behaviors.cke_placeholder_drop_target = {
    attach: function (context, settings) {

      context = $(context);
      var drop_target = context.find(".field-widget-droptarget");

      // Hide deleted items and set event on trashcan.
      $('.cke-placeholder-hidden-target').closest('tr').hide();

      $('.cke-placeholder-droptarget-trash').on('click', function () {
        // Empty the fid value in order for Drupal field handler do the rest.
        $(this).siblings('.cke-placeholder-item').val(0);
        $(this).parent().find('.file-image').hide(function () { $(this).remove();});
        $(this).parent().siblings().hide();
        $(this).hide();
      });

      drop_target.find('input.field-add-more-submit').parent().hide();

      // Drop functions.
      drop_target.each(function () {
        $(this).once('droptarget', function () {

          $(this).on('drop', function (ev) {
            ev.preventDefault();
            // Get the string for the suggestion-field and check if there is an
            // empty textfield.
            var textfield_content = ckePlaceholder.dragElementId;
            ckePlaceholder.dragElementId = null;

            var input_field;

            $(this).css('opacity', 1);
            if ($(this).find('input.field-add-more-submit').length > 0) {
              input_field = $(this).find('.cke-placeholder-input-target[value=""]');
              input_field.val(textfield_content);
              $(this).find('table').css('opacity', 0.5);
              $(this).find('input.field-add-more-submit').trigger('mousedown');
            }
            else {
              input_field = $(this).find('.cke-placeholder-item, .cke-placeholder-input-target');
              input_field.val(textfield_content);
              $(this).find('[value="refresh"]').trigger('mousedown');
            }
          });

          // UX only functions.
          drop_target.on('dragover', function (ev) {
            ev.preventDefault();
            $(this).css('opacity', 0.5);
          }).on('dragleave', function (ev) {
            ev.preventDefault();
            $(this).css('opacity', 1);
          });
        });
      });

      // Add UI events on the library tabs.
      // Library pane. Tabs and close buttons.
      var library = $('.cke-placeholder-library-form-wrap');

    //  library.once('library-tabs', function () {
console.log(123, library)
        $('.cke-placeholder-library-close').one('click', function () {
          $('.cke-active-tab-input').val('');
          library.removeClass('open');
          $('.cke-placeholder-tab.active').removeClass('active');
        });

        var search_field = $('.cke-placeholder-freetext-search');
        search_field.one('keydown', function (evt) {
          if (evt.keyCode == 13) {
            $('.cke-placeholder-library-submit-search').trigger('mousedown');
          }
        });

        $('.cke-placeholder-tab').one('click', function () {
            console.log($(this), 132);
          if (!library.hasClass('open')) {
            library.addClass('open');
          }
          else if ($(this).hasClass('active')) {
            library.removeClass('open');
            $(this).removeClass('active');
            return false;
          }

          var fieldsets = $('.cke-placeholder-fieldset-wrapper');

          $('.cke-placeholder-tab.active').removeClass('active');
          $(this).addClass('active');

          fieldsets.removeClass('active');
          var selected_set = $(this).data('target');
          $('.cke-active-tab-input').val(selected_set);
          library.find('#' + selected_set).addClass('active');
        });
      // });
      context.find('.cke-placeholder-library-upload [name="cke_placeholder_file_upload_new_file_upload_button"]').on('mousedown', function () {
        $('.cke-placeholder-library-upload [name="submit_upload"]').show();
      });
    }
  };
  window.EditEntityWidget = {
    dialogOptions: {
      width: 800,
      height: 650,
      modal: true,
      resizable: true,
      autoOpen: true,
      close: function (event, ui) {
        EditEntityWidget.dialogDestroy();
      }
    },
    dialogDestroy: function () {
      var dialog = jQuery('.entity-edit-dialog');
      dialog.dialog('destroy');
      dialog.remove();
    }
  };

  $.fn.editEntityIframe = function(previewWidth, previewHeight) {
    var href = this.data('target');
    var fillDialog = {width: '100%', height: '100%', 'min-height': previewHeight + 'px', 'min-width': previewWidth + 'px'};
    var dialog = $('<div class="entity-edit-dialog"></div>');
    var iframe = $('<iframe src="' + href + '" frameborder="0" />');
    iframe.css(fillDialog);
    iframe.appendTo(dialog);
    dialog.appendTo('body').dialog(EditEntityWidget.dialogOptions);
    dialog.css(fillDialog);
  };

  Drupal.behaviors.media_library = {
    attach: function(settings) {
      $('.cke-placeholder-edit').on('click', function(evt){
        evt.preventDefault();
        $(this).editEntityIframe(640, 600);
      });
    }
  };
})(jQuery);
