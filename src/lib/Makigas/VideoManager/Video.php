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
 * This is the custom post type that we use to store information about videos.
 * The user can store information about videos coming from an external souce
 * like YouTube.
 * 
 * @package makigas
 * @version 1.1.0
 * @todo the custom post type ID doesn't make use of any prefix.
 * @todo support for other video providers: Vimeo, Livecoding...?
 */
class Video {

	/**
	 * Our singleton variable.
	 * @var Video
	 */
	private static $instance = null;
	
	/**
	 * Get the static instance for this class.
	 * @return Video
	 */
	public static function get_instance() {
		if ( static::$instance == null ) {
			static::$instance = new Video();
		}
		return static::$instance;
	}
	
	protected function __construct() {
		
	}
	
	public function setup_hooks() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}
	
    /**
     * This hook registers the custom post type for video. It has to be
	 * executed, otherwise the system won't recognize our videos.
     */
    public function register_post_type() {
		/* These settings are part of slug and can be changed by the user. */
		$root = get_option( 'makigas-videoman-videos-slug', 'videos' );
		$prefix = get_option( 'makigas-videoman-videos-prefix', 'episode' );

        /* Actually register the video post type in the system. */
        register_post_type( 'video', array(
            'labels' => array(
				'name' => __( 'Videos', 'makigas-videoman' ),
				'singular_name' => __( 'Video', 'makigas-videoman' )
			),
            'description' => __( 'YouTube video including metadata' ),
            'supports' => array( 'title', 'editor', 'excerpt' ),
            'rewrite' => array(
				'slug' => $root . '/%playlist%/' . $prefix,
				'with_front' => false
			),
            'public' => true,
            'query_var' => true,
            'has_archive' => true,
            'taxonomies' => array( 'playlist' )
        ) );
    }
}
