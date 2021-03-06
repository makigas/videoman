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

/**
 * @package makigas
 * @version 1.1.0
 */
/*
 * Plugin Name: makigas-videoman
 * Plugin URI:  http://www.makigas.es
 * Description: Video management library for WordPress.
 * Version:     1.1.0-beta2
 * Author:      Dani Rodríguez
 * Author URI:  http://www.danirod.es
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl.html
 * Domain Path: /languages
 * Text Domain: makigas-videoman
 */

defined( 'ABSPATH' ) || die( 'You are not supposed to run this, punk.' );

spl_autoload_register(function( $class_name ) {
    // Filter classes coming only from Makigas\VideoManager namespace.
    if (false === stripos($class_name, 'Makigas\VideoManager')) {
        return;
    }

    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
    $root = plugin_dir_path(__FILE__) . 'lib' . DIRECTORY_SEPARATOR;
    $file = $root . $path;

    if (file_exists($file)) {
        require_once $file;
    } else {
        die('Error: class ' . $class_name . ' not found.');
    }
});

/* Set up hooks. */
Makigas\VideoManager\Video::get_instance()->setup_hooks();
Makigas\VideoManager\Playlist::get_instance()->setup_hooks();
Makigas\VideoManager\VideoMetabox::get_instance()->setup_hooks();
Makigas\VideoManager\UrlRewriter::get_instance()->setup_hooks();
Makigas\VideoManager\SettingsPage::get_instance()->setup_hooks();
Makigas\VideoManager\PlaylistsTemplate::get_instance()->setup_hooks( __FILE__ );
Makigas\VideoManager\Dashboard\GlanceVideo::get_instance()->setup_hooks();

// Load translations.
add_action( 'plugins_loaded', function() {
	load_plugin_textdomain( 'makigas-videoman', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
});

// Load templates.
add_filter( 'single_template', function( $single_template ) {
	if ( get_post_type() == 'video' ) {
		$single_template = dirname( __FILE__ ) . '/templates/single-video.php';
	}
	return $single_template;
});
add_filter( 'archive_template', function( $archive_template ) {
	if ( is_post_type_archive( 'video' ) ) {
		$archive_template = dirname( __FILE__ ) . '/templates/archive-video.php';
	}
	return $archive_template;
});
add_filter( 'taxonomy_template', function( $taxonomy_template ) {
	if ( is_tax( 'playlist' ) ) {
		$taxonomy_template = dirname( __FILE__ ) . '/templates/taxonomy-playlist.php';
	}
	return $taxonomy_template;
});

// Register widget.
add_action('widgets_init', function() {
    register_widget('Makigas\VideoManager\RecentVideosWidget');
});