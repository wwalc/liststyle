<?php

/**
 * @file
 * Definition of \Drupal\liststyle\Plugin\CKEditorPlugin\ListStyle.
 */

namespace Drupal\liststyle\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginContextualInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "ListStyle" plugin.
 *
 * @CKEditorPlugin(
 *   id = "liststyle",
 *   label = @Translation("ListStyle")
 * )
 */
class ListStyle extends PluginBase implements CKEditorPluginInterface, CKEditorPluginContextualInterface {

  /**
   * Implements \Drupal\ckeditor\Plugin\CKEditorPluginInterface::getDependencies().
   */
  function getDependencies(Editor $editor) {
    return array();
  }

  /**
   * Implements \Drupal\ckeditor\Plugin\CKEditorPluginInterface::getLibraries().
   */
  function getLibraries(Editor $editor) {
    return array();
  }

  /**
   * Implements \Drupal\ckeditor\Plugin\CKEditorPluginInterface::isInternal().
   */
  function isInternal() {
    return FALSE;
  }

  /**
   * Implements \Drupal\ckeditor\Plugin\CKEditorPluginInterface::getFile().
   */
  function getFile() {
    return drupal_get_path('module', 'liststyle') . '/js/plugins/liststyle/plugin.js';
  }

  /**
   * Implements \Drupal\ckeditor\Plugin\CKEditorPluginInterface::getConfig().
   */
  public function getConfig(Editor $editor) {
    return array();
  }

  /**
   * Implements \Drupal\ckeditor\Plugin\CKEditorPluginContextualInterface::isEnabled().
   */
  function isEnabled(Editor $editor) {
    if (!$editor->hasAssociatedFilterFormat()) {
      return FALSE;
    }

    // Automatically enable this plugin if the text format associated with this
    // text editor does *not* use the filter_html filter (because of relying on inline styles)
    // and some of the Lists buttons are enabled.
    $format = $editor->getFilterFormat();
    if (!$format->filters('filter_html')->status) {
      $enabled = FALSE;
      $settings = $editor->getSettings();
      foreach ($settings['toolbar']['rows'] as $row) {
        foreach ($row as $group) {
          foreach ($group['items'] as $button) {
            if ($button === 'BulletedList' || $button === 'NumberedList') {
              $enabled = TRUE;
            }
          }
        }
      }
      return $enabled;
    }

    return FALSE;
  }
}
