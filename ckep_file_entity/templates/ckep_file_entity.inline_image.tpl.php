<?php
/**
 * @file
 * Template for inline images.
 */
?>
<p>
  <span class="body-text__picture <?php print $alignment_class; ?>">
    <span class="picture">
      <?php print $picture_tag; ?>
    </span>
    <?php if (!empty($caption)): ?>
    <span class="body-text__picture__caption">
      <?php print $caption; ?>
    </span>
    <?php endif; ?>
  </span>
</p>
