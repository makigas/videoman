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
		add_filter( 'post_type_link', array( $this, 'filter_permalink' ), 1, 3);
		add_action( 'init', array( $this, 'setup_rewrite_rules' ) );
	}
	
	/**
	 * This function will generate a slug for the playlist whose ID is given.
	 * It works recursivelly checking whether this playlist is child of any
	 * other category.
	 * 
	 * @param int $playlist_id playlist ID
	 */
	private function extract_playlists( $playlist_id ) {
		/* Just in case there is no playlist. */
		if ( $playlist_id == 0 ) {
			return 'unlisted';
		}
		
		$url = '';
		while ( $playlist_id > 0 ) {
			$playlist = get_term_by( 'id', $playlist_id, 'playlist' );
			$url = $playlist->slug . '/' . $url;
			$playlist_id = $playlist->parent; /* 0 if no parent. */
		}
		return substr( $url, 0, -1 ); /* Remove trailing slash. */
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
        if ( 'video' !== get_post_type($post) ) {
            return $url;
        }
		
		/* In which playlist this video is in. */
		$playlists = get_the_terms( $post, 'playlist' );
		
		/*
		 * Check if the video is in a playlist. If it's not, put a dummy
		 * value, like 'unlisted'. This is also used when the user is editing
		 * an unsaved video: the interface will suggest a permalink based on
		 * this function, and the post is not part of a taxonomy yet.
		 */
		if ( $playlists == false ) {
			return str_replace( '%playlist%', 'unlisted', $url );
		} else {
			$playlist_url = $this->extract_playlists( $playlists[0]->term_id );
			return str_replace( '%playlist%', $playlist_url, $url );
		}
    }
	
	/**
	 * This hook sets up the rewrite rules associated to the video manager.
	 * It has to be executed when WordPress is setting up any other rewrite
	 * rule, given that this plugin requires a complex system for rewrite
	 * rules to work.
	 */
	public function setup_rewrite_rules() {
		/* Extract some settings customizable by the user. */
		$root_id = get_option( 'makigas-videoman-videos-slug', 'videos' );
		
		/* Get the slug for the root page. */
		$root = get_permalink( $root_id );
		$root = str_replace( home_url(), '', $root );
		
		/* Maybe remove trailing slash. */
		if ( substr( $root, -1 ) == '/' ) {
			$root = substr( $root, 0, -1 );
		}
		
		/* And maybe remove leading slash. */
		if ( substr( $root, 0, 1) == '/' ) {
			$root = substr( $root, 1 );
		}
		
		/* These rewrite rules makes it possible to browse the video archive. */
		add_rewrite_rule( "${root}/?$", 'index.php?post_type=video', 'top' );
		add_rewrite_rule( "${root}/page/([0-9]+)/?$", 'index.php?post_type=video&paged=$matches[1]', 'top' );
    }
	
}

