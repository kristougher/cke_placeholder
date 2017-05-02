<?php
/**
 * @file
 * Media library template.
 */
?>
<div class="cke-placeholder-lists">
  <?php foreach ($source_items as $item): ?>
    <div draggable="true"
       class="cke-placeholder-draggable <?php echo $item['type']; ?>"
       ondragstart="ckePlaceholder.dragStart(event, 'cke_placeholder_file')"
       data-type="<?php echo $item['type']; ?>"
       data-caption="<?php echo $item['caption']; ?>"
       data-id="<?php echo $item['id']; ?>">
    <div class="cke-placeholder-image-wrap">
      <img src="<?php print $item['preview']; ?>" draggable="false"/>
    </div>
    <h6><?php echo \Drupal\Component\Utility\Unicode::truncate($item['title'], 40, TRUE, TRUE); ?></h6>
    <button class="btn btn-success cke-placeholder-edit" data-target="/file/<?php echo $item['id']; ?>/edit?destination=cke-placeholder/update-done/<?php echo $item['id']; ?>">
      <?php print t('Edit'); ?>
    </button>
    <?php print $item['dimensions']; ?>
  </div>
  <?php endforeach; ?>
</div>
