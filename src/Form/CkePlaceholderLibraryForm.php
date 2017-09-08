<?php

namespace Drupal\cke_placeholder\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\cke_placeholder\CkePlaceholderLibraryInterface;

/**
 * Form class to build the media library.
 */
class CkePlaceholderLibraryForm extends FormBase {

  protected $libraryForm = [];

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cke-placeholder-library-form';
  }

  public function setupBase() {
    $base_form = [];
    $base_form['panes_wrapper'] = array(
      '#type' => 'container',
      '#attributes' => array('class' => 'cke-placeholder-library-form-wrap'),
    );

    $base_form['panes_wrapper']['tabs'] = array(
      '#type' => 'container',
      '#attributes' => array('class' => 'cke-placeholder-library-tabs'),
    );

    $base_form['panes_wrapper']['closing_button'] = array(
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => t('Close'),
      '#attributes' => array(
        'class' => 'cke-placeholder-library-close',
      ),
    );

    $base_form['panes_wrapper']['cke_active_tab'] = array(
      '#type' => 'hidden',
      '#attributes' => array(
        'class' => array('cke-active-tab-input'),
      ),
    );
    $this->libraryForm = $base_form;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    return $this->libraryForm;
  }

  /**
   * Add a pane to the library form.
   */
  public function addToForm($library_pane) {
    $this->libraryForm += $library_pane;
  }

  /**
   * Add a library pane from a plugin definition.
   *
   * The form array that provides the media library.
   *
   * @param array $definition
   *   Properties from the CkePlaceholderLibrary plugin.
   * @param CkePlaceholderLibraryInterface $instance
   *   The plugin instance.
   */
  public function addPane(array $definition, CkePlaceholderLibraryInterface $instance) {

    $pane_name = $definition['id'];
    $pane_id = $pane_name . '-wrap';

    $this->libraryForm['panes_wrapper']['tabs']['tab_' . $pane_name] = array(
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => $definition['pane_title']->render(),
      '#attributes' => array(
        'class' => 'cke-placeholder-tab ' . $pane_name . '-tab',
        'data-target' => $pane_id,
      ),
    );
    $form = [];
    $this->libraryForm['panes_wrapper'][$pane_name] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'cke-placeholder-fieldset-wrapper',
        'id' => $pane_id,
      ],
      'form_item' => $instance->buildForm($form, ''),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}
