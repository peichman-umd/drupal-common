<?php
/**
 * @file
 * Definition of Drupal\lib_cal\Plugin\Block\LibCalBlock
 */

namespace Drupal\lib_cal\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\lib_cal\Controller\LibCalController;

/**
 * Implements the LibCalBlock
 * 
 * @Block(
 *   id = "lib_cal",
 *   admin_label = @Translation("LibCal Events"),
 *   category = @Translation("custom"),
 * )
 */
class LibCalBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $blockConfig = $this->getConfiguration();
    $libCalController = new LibCalController();
    $events = $libCalController->getEvents($blockConfig['limit']);
    $no_events = $blockConfig['no_events_text'];
    return [
      '#theme' => 'lib_cal_block',
      '#events' => $events,
      '#no_events' => $no_events,
      '#cache' => [
        'max-age' => 3600,
      ]
    ];
  }

  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    $form['limit'] = [
      '#type' => 'textfield',
      '#title' => t('Limit'),
      '#description' => t('Number of calendar events to display.'),
      '#default_value' =>  isset($config['limit']) ? $config['limit'] : '3',
      '#required' => TRUE
    ];
    $form['no_events_text'] = [
      '#type' => 'textfield',
      '#title' => t('No Events Text'),
      '#description' => t('Text to display when no events.'),
      '#default_value' =>  isset($config['no_events_text']) ? $config['no_events_text'] : null,
      '#required' => TRUE
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('limit', $form_state->getValue('limit'));
    $this->setConfigurationValue('no_events_text', $form_state->getValue('no_events_text'));
  }

}
