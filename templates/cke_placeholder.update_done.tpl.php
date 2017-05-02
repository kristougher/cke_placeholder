<?php
/**
 * @file
 * Template for the page the user is redirected to after submitting crop form.
 */
?>
<script>
  var library_item = window.parent.jQuery('[data-id="<?php print $fid; ?>"]');
  library_item.find('h6').text('<?php print $title; ?>');
  library_item.attr('data-caption', '<?php print $caption; ?>');

  var droptarget_item = window.parent.jQuery('#file-<?php print $fid; ?>');
  droptarget_item.text('<?php print $title; ?>');
  window.parent.EditEntityWidget.dialogDestroy();
</script>
