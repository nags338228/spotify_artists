<?php
/**
* @file
* Contains \Drupal\spotify_artists\SpotifyApiService.
*/

namespace Drupal\spotify_artists;

use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;

/**
 * Service for interacting with Spotify Web API.
 */
class SpotifyApiService {
  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The Spotify API access token.
   *
   * @var string
   */
  protected $accessToken;

  /**
   * Constructs a SpotifyApiService object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ClientInterface $http_client) {
    $this->config = $config_factory->get('spotify_artists.settings');
    $this->httpClient = $http_client;
  }

  /**
   * Gets an access token from Spotify.
   *
   * @return string
   *   The access token.
   */
  protected function getAccessToken() {
    if (!$this->accessToken) {
      $response = $this->httpClient->request('POST', 'https://accounts.spotify.com/api/token', [
        'form_params' => [
          'grant_type' => 'client_credentials',
        ],
        'headers' => [
          'Authorization' => 'Basic ' . base64_encode(
            $this->config->get('spotify_client_id') . ':' . $this->config->get('spotify_client_secret')
          ),
        ],
      ]);

      $data = json_decode($response->getBody(), TRUE);
      $this->accessToken = $data['access_token'];
    }

    return $this->accessToken;
  }

  /**
   * Gets multiple artists from Spotify API.
   *
   * @return array
   *   Array of artist data.
   */
  public function getArtists() {
    $artist_ids = $this->config->get('artist_ids');
    if (empty($artist_ids)) {
      return [];
    }

    $response = $this->httpClient->request('GET', 'https://api.spotify.com/v1/artists', [
      'query' => ['ids' => implode(',', $artist_ids)],
      'headers' => [
        'Authorization' => 'Bearer ' . $this->getAccessToken(),
      ],
    ]);

    $data = json_decode($response->getBody(), TRUE);
    return $data['artists'];
  }

  /**
   * Gets a single artist from Spotify API.
   *
   * @param string $artist_id
   *   The Spotify artist ID.
   *
   * @return array
   *   Artist data.
   */
  public function getArtist($artist_id) {
    $response = $this->httpClient->request('GET', "https://api.spotify.com/v1/artists/{$artist_id}", [
      'headers' => [
        'Authorization' => 'Bearer ' . $this->getAccessToken(),
      ],
    ]);

    return json_decode($response->getBody(), TRUE);
  }
}