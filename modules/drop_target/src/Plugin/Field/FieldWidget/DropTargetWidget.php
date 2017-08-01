<?php

namespace Drupal\drop_target\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\WidgetInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * A widget DropTargetWidget.
 *
 * @FieldWidget(
 *   id = "drop_target",
 *   label = @Translation("DropTarget widget"),
 *   field_types = {
 *     "image",
 *     "file",
 *     "entityreference",
 *   }
 * )
 */
class DropTargetWidget extends WidgetBase implements WidgetInterface {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $taget_column = '';
    $title = '';
    $entity = NULL;
    $field_name = $this->fieldDefinition->getName();
    $prefix = '<div class="cke-placeholder-droptarget-trash" title="' . t('Remove') . '">' . t('Remove') . '</div>';
    $fid = '';
    switch ($this->fieldDefinition->getType()) {
      case 'image':
      case 'file':
        $target_column = 'fid';
        break;

      case 'entityreference':
        $target_column = 'target_id';
        break;

      default:
        $target_column = 'value';
    }

    if (!empty($form_state->getValue($field_name))) {
      $entity = file_load($form_state->getValue($field_name));
      $title = $entity->getFilename();
    }
    $element['db_target_column'] = array(
      '#type' => 'hidden',
      '#title' => 'Target DB column',
      '#value' => $target_column,
    );

    $element[$target_column] = array(
      '#type' => 'hidden',
      '#title' => $title,
      '#default_value' => $fid,
      '#attributes' => array('class' => 'cke-placeholder-item'),
    );

    $element['reference_id'] = array(
      '#type' => 'hidden',
      '#title' => 'Reference element',
      '#default_value' => $fid,
    );

    $element['display'] = array(
      '#type' => 'hidden',
      '#title' => 'display',
      '#default_value' => 1,
    );

    $dimensions = '';
    /*
     * @TODO

    if ($entity && !empty($entity->getTranslation())) {
      $dimensions = '<span class="droptarget-dimensions">' .
          $entity->metadata['width'] . 'x' . $entity->metadata['height'] .
          '</span>';
    }
     */

// If the value is exactly '0' it is a deleted field, that will not be
// displayed, but needs to be there to be unregistered.
    if (strval(trim($fid)) === '0') {
      $element[$target_column]['#attributes']['class'] = array('cke-placeholder-hidden-target');
      return $element;
    }
    if (empty($entity)) {
      $element[$target_column]['#attributes']['class'] = array('cke-placeholder-input-target');
    }
    else {
      $element['preview'] = file_view($entity, 'preview');
      $element[$target_column]['#prefix'] = $prefix;
    }

    if ($this->fieldDefinition->getSetting('cardinality') != '-1') {
      $target_id = 'droptarget-wrap-' . $field_name;
      $element['#prefix'] = '<div id="' . $target_id . '" class="droptarget-target">'
          . '<label>' . $this->fieldDefinition->getLabel() . '</label>';
      $element['#suffix'] = '</div>';

// When ajax is triggered node edit form is submitted and validated so we
// need to disable validation of all fields having drop_target as widget.
// For that we limit validation to only these fields for EVERY field having
// drop_target as widget.
      /*       * *
       * TODO
       */
  /*    switch ($instance['entity_type']) {
        case 'node':
          $all_fields = field_info_instances('node', $form['#node']->type);
          break;

        case 'taxonomy_term':
          $all_fields = field_info_instances('taxonomy_term', $form['#entity']->vocabulary_machine_name);
          break;

        default:
// Todo: Should probably look up bundle keys for the found entity...
          $all_fields = field_info_instances($instance['entity_type'], $form['#' . $instance['entity_type']]->type);
          break;
      } */

      $drop_target_widgets = array();

      foreach ($all_fields as $field_name => $info) {
        if (!empty($info['widget']['module']) && $info['widget']['module'] == 'drop_target') {
          $drop_target_widgets[] = array($field_name);
        }
      }

      $element['refresh'] = array(
        '#type' => 'button',
        '#value' => 'refresh',
        '#name' => 'btn_' . $field_name,
        '#attributes' => array(
          'class' => array('droptarget-hidden'),
        ),
        '#ajax' => array(
          'wrapper' => $target_id,
          'callback' => 'drop_target_single_widget_callback',
          'method' => 'replace',
        ),
        '#limit_validation_errors' => $drop_target_widgets,
      );
    }

    return $element;
  }

  /*
   * {@inheritdoc}
   */

  public static function defaultSettings() {
    return array(
      'types' => [],
        ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['types'] = array(
      '#title' => t('Allowed types'),
      '#type' => 'checkboxes',
      '#options' => array(
        'video' => t('Video'),
        'image' => t('Image'),
        'document' => t('Document'),
      ),
      '#default_value' => $this->getSetting('types'),
    );

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();

    $summary[] = t('Allowed types: @types', array('@types' => implode(',', array_filter($this->getSetting('types')))));

    return $summary;
  }

}
