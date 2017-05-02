<?php
/**
 * @file
 * Library item template, used on node form.
 */
?>
<?php if (empty($no_wrapper)): ?>
<div id="<?php print $wrapper_id; ?>" class="cke-placeholder-list <?php print $cke_placeholder_tag; ?> <?php print $cke_placeholder_tag; ?>-wrap">
<?php endif; ?>
    <?php foreach($items as $item): ?>
        <div
          draggable="true"
          class="cke-placeholder-draggable"
          ondragstart="ckePlaceholder.dragStart(event, '<?php print $cke_placeholder_tag; ?>')"
          <?php foreach ($item['data'] as $key => $value): ?>
            data-<?php print $key; ?>="<?php print $value; ?>"
          <?php endforeach; ?>
        >
          <?php print $item['markup']; ?>
        </div>
    <?php endforeach; ?>
<?php if (empty($no_wrapper)): ?>
</div>
<?php endif; ?>
