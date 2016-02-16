<?php

/*
 * This file is part of the Makigas CoreWidgets library for WordPress.
 * Copyright (C) 2015 Dani Rodríguez <danirod@outlook.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Makigas\VideoManager;

defined( 'ABSPATH' ) || die( 'You are not supposed to run this, punk.' );

/**
 * This class is intended to set up the permalink system. It has to set up
 * permalink structure for the video post type and any other rewrite rule
 * required by our custom post types.
 * 
 * @package makigas-videoman
 * @version v1.1.0
 */
class UrlRewriter {
	
	private static $instance = null;
	
	/**
	 * Get the static instance for this class.
	 * @return UrlRewriter
	 */
	public static function get_instance() {
		if ( static::$instance == null ) {
			static::$instance = new UrlRewriter();
		}
		return static::$instance;
	}
	
	protected function __construct() {
		
	}
	
	public function setup_hooks() {
		add_filter('post_type_link', array( $this, 'filter_permalink' ), 1, 3);
		add_action('init', array( $this, 'makigas_add_rewrite_rules') );
	}
	
	/**
	 * This is the filter that gets the appropiate permalink for a post.
	 * Usually this permalink will include the atom %playlist% at any point
	 * since this filter is intended to be used only with video type.
	 * 
	 * @param string $url URL that WordPress thinks fits best for this post.
	 * @param \WP_Post $post post structure with information for this item.
	 * @return string URL that we might have changed 
	 */
	public function filter_permalink($url, $post) {
        // If this post is not a video, don't filter.
        if ('video' !== get_post_type($post)) {
            return $url;
        }

        // FIXME: There should be a way to manage permalinks for videos
        // that are not in a playlist.
        // Get the video playlist.
        $playlists = get_the_terms($post, 'playlist');

        // The video is in at least one playlist. Use the first playlist.
        // A video should not be anyway in more than a playlist.
        $playlist_slug = $playlists[0]->slug;
        return str_replace('%playlist%', $playlist_slug, $url);
    }
	
	/**
	 * This hook sets up the rewrite rules associated to the video manager.
	 * It has to be executed when WordPress is setting up any other rewrite
	 * rule, given that this plugin requires a complex system for rewrite
	 * rules to work.
	 */
	public function setup_rewrite_rules() {
        // Rewrite rule for the pagination system.
        add_rewrite_rule('^' . get_option( 'makigas-videoman-videos-slug' ) . '/([^/]+)/page/([0-9]+)/?$', 'index.php?playlist=$matches[1]&paged=$matches[2]', 'top');

		// Rewrite rule for the videos.
        add_rewrite_rule('^' . get_option( 'makigas-videoman-videos-slug' ) . '/page/([0-9]+)/?$', 'index.php?post_type=video&paged=$matches[1]', 'top');

        // Rewrite rule for the videos.
        add_rewrite_rule('^' . get_option( 'makigas-videoman-videos-slug' ) . '/?$', 'index.php?post_type=video', 'top');
    }
	
}

