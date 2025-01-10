<?php
/**
* @file
* Contains \Drupal\spotify_artists\Plugin\Block\ArtistsBlock.
*/

namespace Drupal\spotify_artists\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\spotify_artists\SpotifyApiService;

/**
 * @Block(
 *   id = "spotify_artists_block",
 *   admin_label = @Translation("Spotify Artists")
 * )
 */
class ArtistsBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
  * The Spotify API service.
  *
  * @var \Drupal\spotify_artists\SpotifyApiService
  */
  protected $spotifyApi;

  /**
  * {@inheritdoc}
  */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('spotify_artists.api')
    );
  }

  /**
  * {@inheritdoc}
  */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, SpotifyApiService $spotify_api) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->spotifyApi = $spotify_api;
  }

  /**
  * {@inheritdoc}
  */
  public function build() {
    $artists = $this->spotifyApi->getArtists();
    
    return [
      '#theme' => 'spotify_artists_block',
      '#artists' => $artists,
      '#cache' => [
        'max-age' => 3600,
        'contexts' => ['user.roles'],
      ],
    ];
  }
}