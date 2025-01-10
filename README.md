# Spotify Artists

## INTRODUCTION
The Spotify Artists module allows site administrators to display information about 
selected Spotify artists. It provides a configurable block showing artist names and 
detailed artist pages for logged-in users.

## REQUIREMENTS
This module requires the following:
* Drupal 10/11
* A Spotify Developer account with API credentials

## INSTALLATION
1. Install as you would normally install a contributed Drupal module:
   ```
   composer require 'drupal/spotify_artists'
   ```
   or place manually in the `modules/custom` directory.

2. Enable the module via UI (/admin/modules) or:
   ```
   drush en spotify_artists
   ```

## CONFIGURATION
1. Navigate to Configuration > Web services > Spotify Artists Settings 
   (/admin/config/services/spotify-artists)
2. Enter your Spotify API credentials
3. Add up to 20 Spotify artist IDs (one per line)

## FEATURES
* Configurable block displaying artist names
* Artist detail pages for authenticated users
* Caching integration for optimal performance
