<?php
/**
 * @file
 * Contains \Drupal\spotify_artists\Controller\ArtistController.
 */

namespace Drupal\spotify_artists\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\spotify_artists\SpotifyApiService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for handling Spotify artist pages and listings.
 */
class ArtistController extends ControllerBase {
  /**
   * The Spotify API service.
   *
   * @var \Drupal\spotify_artists\SpotifyApiService
   */
  protected $spotifyApi;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('spotify_artists.api'));
  }

  /**
   * Constructs an ArtistController object.
   *
   * @param \Drupal\spotify_artists\SpotifyApiService $spotify_api
   *   The Spotify API service.
   */
  public function __construct(SpotifyApiService $spotify_api) {
    $this->spotifyApi = $spotify_api;
  }


  /**
   * Displays a Spotify artist's details.
   *
   * @param string $artist_id
   *   The Spotify artist ID.
   *
   * @return array
   *   Render array for the artist page.
   */
  public function view($artist_id) {
    $artist = $this->spotifyApi->getArtist($artist_id);
    
    return [
      '#theme' => 'spotify_artist',
      '#artist' => $artist,
    ];
  }

  /**
   * Returns the title for the artist page.
   *
   * @param string $artist_id
   *   The Spotify artist ID.
   *
   * @return string
   *   The artist name.
   */
  public function title($artist_id) {
    $artist = $this->spotifyApi->getArtist($artist_id);
    return $artist['name'];
  }

  /**
   * Displays the artists listing page.
   *
   * @return array
   *   Render array for the artists listing page.
   */
  public function listPage() {
    return [
      '#markup' => '<div id="spotify-artists-page"></div>',
      '#cache' => [
        'contexts' => ['user.roles'],
      ],
    ];
  }
}