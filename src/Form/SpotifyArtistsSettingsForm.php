<?php
/**
* @file
* Contains \Drupal\spotify_artists\Form\SpotifyArtistsSettingsForm.
*/

namespace Drupal\spotify_artists\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
* Configures Spotify Artists settings.
*/
class SpotifyArtistsSettingsForm extends ConfigFormBase {
  /**
  * {@inheritdoc}
  */
  protected function getEditableConfigNames() {
    return ['spotify_artists.settings'];
  }

  /**
  * {@inheritdoc}
  */
  public function getFormId() {
    return 'spotify_artists_settings';
  }

  /**
  * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('spotify_artists.settings');

    $form['spotify_client_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Spotify Client ID'),
      '#default_value' => $config->get('spotify_client_id'),
      '#required' => TRUE,
    ];

    $form['spotify_client_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Spotify Client Secret'),
      '#default_value' => $config->get('spotify_client_secret'),
      '#required' => TRUE,
    ];

    $form['artist_ids'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Artist IDs'),
      '#default_value' => implode("\n", $config->get('artist_ids') ?: []),
      '#description' => $this->t('Enter one Spotify artist ID per line. Maximum 20 artists.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
  * {@inheritdoc}
  */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $client_id = $form_state->getValue('spotify_client_id');
    $client_secret = $form_state->getValue('spotify_client_secret');

    if (empty($client_id)) {
      $form_state->setErrorByName('spotify_client_id', $this->t('The Spotify Client ID is required.'));
    }

    if (empty($client_secret)) {
      $form_state->setErrorByName('spotify_client_secret', $this->t('The Spotify Secret Key is required.'));
    }
    
    $artist_ids = array_filter(explode("\n", $form_state->getValue('artist_ids')));
    if (count($artist_ids) > 20) {
      $form_state->setErrorByName('artist_ids', $this->t('Maximum 20 artists allowed.'));
    }
  }

  /**
  * {@inheritdoc}
  */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $artist_ids = array_map('trim', explode("\n", $form_state->getValue('artist_ids')));
    $artist_ids = array_filter($artist_ids); // Remove empty lines
    
    $this->config('spotify_artists.settings')
      ->set('spotify_client_id', $form_state->getValue('spotify_client_id'))
      ->set('spotify_client_secret', $form_state->getValue('spotify_client_secret'))
      ->set('artist_ids', array_values($artist_ids))
      ->save();
  
    parent::submitForm($form, $form_state);
  }
}