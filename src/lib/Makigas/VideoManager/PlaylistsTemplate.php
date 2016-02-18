<?php

/*
 * This file is part of the Makigas CoreWidgets library for WordPress.
 * Copyright (C) 2016 Dani Rodríguez <danirod@outlook.com>
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

class PlaylistsTemplate {

	/**
	 * Our singleton variable.
	 * @var PlaylistsTemplate
	 */
	private static $instance = null;
	
	/**
	 * Get the static instance for this class.
	 * @return PlaylistsTemplate
	 */
	public static function get_instance() {
		if ( static::$instance == null ) {
			static::$instance = new PlaylistsTemplate();
		}
		return static::$instance;
	}
	
	private $root;
	
	private $templates;
	
	protected function __construct() {
		$this->templates = array(
			'playlists-template.php' => 'All the playlists'
		);
	}
	
	public function setup_hooks( $index ) {
		/* Get the root directory for this plugin. */
		$this->root = plugin_dir_path( $index );
		
		/* Register filters. */
		add_filter( 'page_attributes_dropdown_pages_args', array( $this, 'register_template' ) );
		add_filter( 'wp_insert_post_data', array( $this, 'register_template' ) );
		add_filter( 'template_include', array( $this, 'playlist_template_include' ) );
	}
	
	public function register_template( $atts ) {
		/* Get the list of templates that are part of the current theme. */
		$theme_templates = wp_get_theme()->get_page_templates();
		if ( empty( $theme_templates ) ) {
			$theme_templates = array();
		}
		
		/* Put our templates in the list of templates. */
		$theme_templates = array_merge( $theme_templates, $this->templates );
		
		/* Inject our template in the cache. */
		$key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
		wp_cache_delete( $key, 'themes' );
		wp_cache_add( $key, $theme_templates, 'themes', 1800 );
		
		return $atts;
	}
	
	public function playlist_template_include( $template ) {
		global $post;
		$post_template = get_post_meta( $post->ID, '_wp_page_template', true );
		
		/* Is this our custom template? No? Give up. */
		if ( ! isset( $this->templates[$post_template] ) ) {
			return $template;
		}
		
		/* It is. Serve our file. */
		$file = $this->root . '/templates/' . $post_template;
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			return $template;
		}
	}
}
