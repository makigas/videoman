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
 * This class controls settings rendering and handling. This plugin needs some
 * settings for modifying part of its behaviour. The settings that need to be
 * set include the slugs for the video browsing system and the playlist
 * navigation system.
 * 
 * @package makigas-videoman
 * @version 1.1.0
 * @todo should automatically flush the permalinks after a change
 */
class SettingsPage {
	
	const SETTINGS_PAGE = 'makigas-videoman-options';
	
	const SETTINGS_SLUG_SECTION = 'makigas-videoman-slugs';
    	
	/**
	 * Our singleton variable.
	 * @var SettingsPage
	 */
	private static $instance = null;
	
	/**
	 * Get the static instance for this class.
	 * @return SettingsPage
	 */
	public static function get_instance() {
		if ( static::$instance == null ) {
			static::$instance = new SettingsPage();
		}
		return static::$instance;
	}
	
	protected function __construct() {
		
	}
	
	public function setup_hooks() {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'register_options_page' ) );
	}
	
	public function register_options_page() {
		add_options_page(
				__( 'Video Manager Options', 'makigas-videoman' ),
				__( 'Video Manager', 'makigas-videoman' ),
				'administrator',
				self::SETTINGS_PAGE,
				array( $this, 'render_videoman_options' )
			);
	}
	
	/**
	 * This is the function that WordPress will call to render our page.
	 * It should render the contents for the video manager settings that
	 * were created using the Settings API.
	 */
	public function render_videoman_options() {
		echo '<div class="wrap">';
		echo '<h2>' . __( 'Video Manager Options', 'makigas-videoman' ) . '</h2>';
		
		echo '<form method="post" action="options.php">';
		settings_fields( self::SETTINGS_SLUG_SECTION );
		do_settings_sections( self::SETTINGS_PAGE );
		submit_button();
		echo '</form>';
		echo '</div>';
	}
	
	/**
	 * This is the function that will register the settings for our plugin.
	 * By settings I mean the "sections" and the "fields" WordPress works
	 * with.
	 */
	public function register_settings() {
		add_settings_section(
				self::SETTINGS_SLUG_SECTION,
				__( 'Slug Settings', 'makigas-videoman' ),
				array( $this, 'render_slug_settings_callback' ),
				self::SETTINGS_PAGE
			);

		add_settings_field(
				'makigas-videoman-videos-slug',
				__( 'Videos archive URL Slug', 'makigas-videoman' ),
				array( $this, 'render_video_slug_callback' ),
				self::SETTINGS_PAGE,
				self::SETTINGS_SLUG_SECTION
			);
		
		add_settings_field(
				'makigas-videoman-videos-prefix',
				__( 'Video page URL subdirectory', 'makigas-videoman' ),
				array( $this, 'render_video_prefix_callback' ),
				self::SETTINGS_PAGE,
				self::SETTINGS_SLUG_SECTION
			);
		
		register_setting( self::SETTINGS_SLUG_SECTION, 'makigas-videoman-videos-slug' );
		register_setting( self::SETTINGS_SLUG_SECTION, 'makigas-videoman-videos-prefix' );
	}
	
	public function render_slug_settings_callback() {
		echo '<p>' . __( 'These settings control the slug engine.', 'makigas-videoman' ) . '</p>';
	}
	
	/**
	 * This is the callback that will display the video slug control.
	 * This allows the user to modify the slug used in page for rendering
	 * the video archive.
	 */
	public function render_video_slug_callback() {	
		$current = get_option( 'makigas-videoman-videos-slug' );
		echo '<select name="makigas-videoman-videos-slug" id="makigas-videoman-videos-slug">';
		foreach( $this->prepare_pages_list() as $page ) {
			$selected = $current == $page['id'] ? ' selected' : '';
			echo '<option value="' . $page['id'] . '"' . $selected . '>' . $page['title'] . '</option>';
		}
		echo '</select>';
		echo '<p id="makigas-videoman-videos-slug-description" class="description">' . __( 'This is the base URL when the user is inspecting the video archive page.', 'makigas-videoman' ) . '</p>';
	}
	
	public function render_video_prefix_callback() {
		echo '<input type="text" id="makigas-videoman-videos-prefix" name="makigas-videoman-videos-prefix" value="' . get_option( 'makigas-videoman-videos-prefix', 'episode' ) . '" />';
		echo '<p id="makigas-videoman-videos-prefix-description" class="description">' . __( 'This goes between the playlist name and the video name in the permalink.', 'makigas-videoman' ) . '</p>';
	}
	
	private function prepare_pages_list( $parent = 0, $depth = 0 ) {
		$pages = get_pages( array( 'parent' => $parent ) );
		$depth_prefix = "";
		if ( $depth > 0 ) {
			$depth_prefix = str_repeat( '&nbsp;', 2 * $depth ) . '– ';
		}
		$all_pages = array();
		foreach ( $pages as $page ) {
			$all_pages[] = array( 'id' => $page->ID, 'title' => $depth_prefix . $page->post_title );
			$child_pages = $this->prepare_pages_list( $page->ID, $depth + 1 );
			$all_pages = array_merge( $all_pages, $child_pages );
		}
		return $all_pages;
	}
}

