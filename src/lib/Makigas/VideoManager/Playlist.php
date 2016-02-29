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
 * Playlists are custom taxonomies that contain videos. The user can manage
 * the playlists and add videos to playlists. Playlists are hierarchical, and
 * it is a good way for managing playlists to organize related playlists in
 * a parent playlist.
 * 
 * @package makigas-videoman
 * @since 1.0.0
 * @todo Custom post fields? I'm mainly interested in a playlist thumbnail.
 */
class Playlist {

	/**
	 * Our singleton variable.
	 * @var Playlist
	 */
	private static $instance = null;
	
	/**
	 * Get the static instance for this class.
	 * @return Playlist
	 */
	public static function get_instance() {
		if ( static::$instance == null ) {
			static::$instance = new Playlist();
		}
		return static::$instance;
	}
	
	protected function __construct() {
		
	}
	
	public function setup_hooks() {
		add_action( 'init', array( $this, 'register_playlist' ) );
	}
	
    /**
     * This function registers the playlist taxonomy.
     */
    public function register_playlist() {
		/* Extract some settings customizable by the user. */
		$root_id = get_option( 'makigas-videoman-videos-slug', 'videos' );
		
		/* Get the slug for the root page. */
		$root = get_permalink( $root_id );
		$root = str_replace( home_url(), '', $root );
		
		/* Maybe remove trailing slash. */
		if ( substr( $root, -1 ) == '/' ) {
			$root = substr( $root, 0, -1 );
		}
		
        /* Register taxonomy. */
        register_taxonomy('playlist', 'video', array(
            'label' => __( 'Playlist', 'makigas-videoman' ),
            'labels' => array(
				'name' => __( 'Playlists', 'makigas-videoman' ),
				'singular_name' => __( 'Playlist', 'makigas-videoman' ),
				'search_items' => __( 'Search Playlists', 'makigas-videoman' ),
				'all_items' => __('All Playlists', 'makigas-videoman' ),
				'parent_item' => __( 'Parent Playlist', 'makigas-videoman' ),
				'parent_item_colon' => __( 'Parent Playlist:', 'makigas-videoman' ),
				'edit_item' => __( 'Edit Playlist', 'makigas-videoman' ),
				'update_item' => __( 'Update Playlist', 'makigas-videoman' ),
				'add_new_item' => __( 'Add new Playlist', 'makigas-videoman' ),
				'new_item_name' => __( 'Playlist Title', 'makigas-videoman' ),
				'menu_name' => __( 'Playlist', 'makigas-videoman' ),
			),
            'query_var' => true,
            'hierarchical' => true,
            'rewrite' => array(
				'slug' => $root,
				'with_front' => false,
				'feed' => true,
				'pages' => true,
				/* Allow parent playlists to appear in the slug. */
				'hierarchical' => true
			)
        ) );
    }

}
