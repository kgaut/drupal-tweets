<?php

namespace Drupal\tweets\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Tweets settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'tweets_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['tweets.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('tweets.settings');
    $form['consumerKey'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Consumer Key'),
      '#default_value' => $config->get('consumerKey'),
      '#required' => TRUE,
    ];
    $form['consumerSecret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Consumer Secret'),
      '#default_value' => $config->get('consumerSecret'),
      '#required' => TRUE,
    ];
    $form['accessToken'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Access Token'),
      '#default_value' => $config->get('accessToken'),
      '#required' => TRUE,
    ];
    $form['accessTokenSecret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Access Token Secret'),
      '#default_value' => $config->get('accessTokenSecret'),
      '#required' => TRUE,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('tweets.settings')
      ->set('consumerKey', $form_state->getValue('consumerKey'))
      ->set('consumerSecret', $form_state->getValue('consumerSecret'))
      ->set('accessToken', $form_state->getValue('accessToken'))
      ->set('accessTokenSecret', $form_state->getValue('accessTokenSecret'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
