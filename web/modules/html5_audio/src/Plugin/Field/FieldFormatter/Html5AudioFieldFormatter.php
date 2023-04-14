<?php

namespace Drupal\html5_audio\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'HTML5 Audio' formatter.
 *
 * @FieldFormatter(
 *   id = "html5_audio_formatter",
 *   label = @Translation("HTML5 Audio"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class Html5AudioFieldFormatter extends FormatterBase
{

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings()
  {
    return [
      'autoplay' => '0',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state)
  {

    $elements['autoplay'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Autoplay enabled'),
      '#default_value' => $this->getSetting('Autoplay'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $settings = $this->getSettings();
    if ($settings['autoplay']) {
      $summary[] = $this->t('Autoplay is enabled.');
    }
    else {
      $summary[] = $this->t('Autoplay is not enabled.');
    }
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $element = [];

    // Render all field values as part of a single <audio> tag.
    $sources = [];
    foreach ($items as $item) {
      // Get the mime type.
      $mimetype = \Drupal::service('file.mime_type.guesser')->guessMimeType($item->uri);
      $sources[] = [
        'src' => $item->uri,
        'mimetype' => $mimetype,
      ];
    }

    //Configuration
    $autoplay = '';
    if ($this->getSetting('autoplay')) {
      $autoplay = 'autoplay';
    }

    // Put everything in a render array for theming.
    $element[] = [
      '#theme' => 'audio_tag',
      '#sources' => $sources,
      '#autoplay' =>$autoplay,
    ];

    dpm($element);

    return $element;
  }
}
