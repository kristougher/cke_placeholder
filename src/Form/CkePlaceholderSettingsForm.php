<?php

/**
 * @file
 * Contains \Drupal\cke_placeholder\Form\CkePlaceholderSettingsForm.
 */

namespace Drupal\cke_placeholder\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class CkePlaceholderSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cke_placeholder_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['cke_placeholder.settings'];
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $form['cke_placeholder_general'] = [
      '#type' => 'fieldset',
      '#title' => t('Media settings'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    ];

    $form['cke_placeholder_general']['cke_placeholder_allowed_extensions'] = [
      '#type' => 'textfield',
      '#title' => t('CKE placeholder allowed file extensions'),
      '#default_value' => \Drupal::config('cke_placeholder.settings')->get('cke_placeholder_allowed_extensions'),
      '#description' => t('Separate extensions with a space and do not include the leading dot.'),
      '#maxlength' => NULL,
    ];

    $form['cke_placeholder_advanced'] = [
      '#type' => 'fieldset',
      '#title' => t('Advanced settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    $form['cke_placeholder_advanced']['cke_placeholder_filter_regex'] = [
      '#type' => 'textfield',
      '#title' => t('CKE placeholder filter regex'),
      '#default_value' => \Drupal::config('cke_placeholder.settings')->get('cke_placeholder_filter_regex'),
      '#maxlength' => NULL,
    ];

    for ($i = 1; $i <= 20; $i++) {
      $default_items[$i] = $i;
    }

    $form['cke_placeholder_advanced']['cke_placeholder_items_per_page'] = [
      '#type' => 'select',
      '#title' => t('CKE placeholder default items per page'),
      '#options' => $default_items,
      '#default_value' => \Drupal::config('cke_placeholder.settings')->get('cke_placeholder_items_per_page'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    if (!empty($form_state->getValue('cke_placeholder_allowed_extensions'))) {
      $extensions = preg_replace('/([, ]+\.?)/', ' ', trim(strtolower($form_state->getValue('cke_placeholder_allowed_extensions'))));
      $extensions = array_filter(explode(' ', $extensions));
      $extensions = implode(' ', array_unique($extensions));
      if (!preg_match('/^([a-z0-9]+([.][a-z0-9])* ?)+$/', $extensions)) {
        $form_state->setErrorByName('cke_placeholder_allowed_extensions', $this->t('The list of allowed extensions is not valid, be sure to exclude leading dots and to separate extensions with a comma or space.'));
      }
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('cke_placeholder.settings');

    $keys = array(
      'cke_placeholder_allowed_extensions',
      'cke_placeholder_filter_regex',
      'cke_placeholder_items_per_page',
    );
    foreach ($keys as $key) {
      $config->set($key, $form_state->getValue($key));
    }
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
