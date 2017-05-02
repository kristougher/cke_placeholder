<?php
/**
 * @file
 * Library item template, used on node form.
 */
?>
<?php if ($type != 'document'): ?>
    <div class="cke-placeholder-temp cke-placeholder-image-wrap">
      <img src="<?php print $thumbnail_url; ?>" draggable="false">
    </div>
    <h6><?php print \Drupal\Component\Utility\Unicode::truncate($title, 70, TRUE, TRUE); ?></h6>

    <?php if (!empty($editlink)): ?>
      <button class="btn btn-success cke-placeholder-edit" data-target="<?php echo $editlink; ?>"><?php print t('Edit'); ?></button>
    <?php endif; ?>
<?php else: ?>
  <h6><a class="cke-placeholder-draggable" href="<?php print $file_url; ?>"><?php print \Drupal\Component\Utility\Unicode::truncate($title, 70, TRUE, TRUE); ?></a></h6>
<?php endif; ?>
