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

namespace Makigas\VideoManager\Dashboard;

defined( 'ABSPATH' ) || die( 'You are not supposed to run this, punk.' );

/**
 * Adds information to the At a Glance widget at the Dashboard panel.
 * 
 * @package makigas-videoman
 * @version 1.1.0
 * @since 1.1.0
 */
class GlanceVideo {

	/**
	 * Our singleton variable.
	 * @var GlanceVideo
	 */
	private static $instance = null;
	
	/**
	 * Get the static instance for this class.
	 * @return GlanceVideo
	 */
	public static function get_instance() {
		if ( static::$instance == null ) {
			static::$instance = new GlanceVideo();
		}
		return static::$instance;
	}
	
	protected function __construct() {
		
	}
	
	public function setup_hooks() {
		add_action( 'dashboard_glance_items', array( $this, 'add_video_glance' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'glance_css_code' ) );
	}
	
	/**
	 * Adds information about how many videos are in the database.
	 */
	public function add_video_glance() {
		/* How many videos are published. */
		$video_count = wp_count_posts( 'video' )->publish;
		
		/* Get the correct label (video, videos) depending on the count. */
		$video_type = get_post_type_object( 'video' );
		$video_text = _n( $video_type->labels->singular_name, $video_type->labels->name, intval( $video_count ), 'makigas-videoman' );
		
		/* Render HTML code. */
		echo '<li class="page-count video-count">';
		echo '<a href="edit.php?post_type=video">' . $video_count . ' ' . strtolower( $video_text ) . '</a>';
		echo '</li>';
		
		/* TODO: content: "\f236"; in CSS: */
	}
	
	public function glance_css_code() {
		wp_register_style( 'makigas-videoman-admin', plugins_url( 'videoman/css/makigas-admin.css' ) );
		wp_enqueue_style( 'makigas-videoman-admin' );
	}
}

