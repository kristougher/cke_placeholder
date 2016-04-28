<?php
/**
 * @file
 * Admin-only functionality for cke_placeholder.
 */

/**
 * Widget editor preview page callback.
 *
 * AJAX response to be called from the editor.
 *
 * @param string $plugin
 *   Name of the cke_placeholder tag/plugin.
 */
function cke_placeholder_widget_preview($plugin) {
  $args = drupal_get_query_parameters();
  $tag = cke_placeholder_tags($plugin);
  $process_function = empty($tag['preview_process']) ? $tag['process'] : $tag['preview_process'];
  $output = $process_function($args, NULL);

  return drupal_json_output(array('markup' => $output));
}

/*
 * Form callback for cke_placeholder file settings.
 */
function cke_placeholder_media_file_settings_form($form, &$form_state) {
  $form['cke_placeholder_general'] = array(
    '#type' => 'fieldset',
    '#title' => t('Media settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $form['cke_placeholder_general']['cke_placeholder_allowed_extensions'] = array(
    '#type' => 'textfield',
    '#title' => t('CKE placeholder allowed file extensions'),
    '#default_value' => variable_get('cke_placeholder_allowed_extensions', 'png jpg gif pdf'),
    '#description' => t('Separate extensions with a space and do not include the leading dot.'),
    '#maxlength' => NULL,
  );

  $form['cke_placeholder_advanced'] = array(
    '#type' => 'fieldset',
    '#title' => t('Advanced settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['cke_placeholder_advanced']['cke_placeholder_filter_regex'] = array(
    '#type' => 'textfield',
    '#title' => t('CKE placeholder filter regex'),
    '#default_value' => variable_get('cke_placeholder_filter_regex', ''),
    '#maxlength' => NULL,
  );

  for ($i=1; $i<=20; $i++) {
    $default_items[$i] = $i;
  }

  $form['cke_placeholder_advanced']['cke_placeholder_items_per_page'] = array(
    '#type' => 'select',
    '#title' => t('CKE placeholder default items per page'),
    '#options' => $default_items,
    '#default_value' => variable_get('cke_placeholder_items_per_page', 8),
  );

  return system_settings_form($form);
}
