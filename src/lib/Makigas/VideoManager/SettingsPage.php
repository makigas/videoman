<?php

namespace Makigas\VideoManager;

/**
 * This class controls settings rendering and handling. This plugin needs some
 * settings for modifying part of its behaviour. The settings that need to be
 * set include the slugs for the video browsing system and the playlist
 * navigation system.
 * 
 * @package makigas-videoman
 */
class SettingsPage {
	
	const SETTINGS_PAGE = 'makigas-videoman-options';
	
	const SETTINGS_SLUG_SECTION = 'makigas-videoman-slugs';
    
	public function __construct() {
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
		settings_errors();
		
		echo '<form method="post" action="options.php">';
		settings_fields( self::SETTINGS_PAGE );
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
		
		register_setting( self::SETTINGS_PAGE, 'makigas-videoman-videos-slug' );
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
		echo '<input type="text" id="makigas-videoman-videos-slug" name="makigas-videoman-videos-slug" value="' . get_option( 'makigas-videoman-videos-slug', 'video' ) . '" />';
		echo '<p id="makigas-videoman-videos-slug-description" class="description">' . __( 'This is the base URL when the user is inspecting the video archive page.', 'makigas-videoman' ) . '</p>';
	}
    
}

