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
 * This class takes care of rendering the metabox on the editor page for videos
 * as well as for saving that information when the video is saved into the
 * database.
 * 
 * @package makigas-videoman
 * @version 1.0.0
 */
class VideoMetabox {

	/**
	 * Our singleton variable.
	 * @var VideoMetabox
	 */
	private static $instance = null;
	
	/**
	 * Get the static instance for this class.
	 * @return VideoMetabox
	 */
	public static function get_instance() {
		if ( static::$instance == null ) {
			static::$instance = new VideoMetabox();
		}
		return static::$instance;
	}
	
	protected function __construct() {
		
	}
	
	public function setup_hooks() {
		add_action( 'add_meta_boxes', array( $this, 'register_metabox' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'save_post', array( $this, 'save_post' ) );
	}
	
    /**
     * Prints to output HTML code to generate a field.
     * 
     * @param $post the post to get the information from.
     * @param $field the field name to pull.
     * @param $title the title to use in the form.
     */
    private function print_field($post, $field, $title) {
        $value = get_post_meta($post->ID, $field, true);
        echo '<p>';
        echo '<label for="' . $field . '">' . $title . '</label>';
        echo '<input type="text" id="makigas-metabox-' . $field . '" placeholder="' . $title . '" name="' . $field . '" value="' . $value . '" class="widefat" />';
        echo '</p>';
    }

    public function register_metabox() {
        add_meta_box( 'metadata', __( 'Video information', 'makigas-videoman' ), array($this, 'print_metadata_box'), 'video' );
    }

    public function print_metadata_box($post) {
        // Declare a nonce field. The metabox will declare a nonce field and then when the user
        // presses save, the application will check that the nonce field is still declared and
        // that is the same that the one provided here. If they don't match, probably something
        // nasty happened (or maybe just there was a bug).
        wp_nonce_field('makigas_metabox_video', 'makigas_metabox_nonce');

        // Put the fields.
        $this->print_field( $post, '_video_id', __( 'Video ID', 'makigas-videoman' ) );
        $this->print_field( $post, '_episode', __( 'Episode Number', 'makigas-videoman' ) );
        $this->print_field( $post, '_length', __( 'Length', 'makigas-videoman' ) );
    }

    public function save_post($post_id) {
        // Check for a valid nonce.
        if (!wp_verify_nonce($_POST['makigas_metabox_nonce'], 'makigas_metabox_video')) {
            return $post_id;
        }

        // If the user is not supposed to be able to modify this post, then just give up.
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        // If this is a revision or an autosave, don't update the information.
        // This is to avoid saving a lot of information when you don't need to save it.
        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            return $post->ID;
        }

        // Ok, we are safe. Get the fields from the form and save it as metadata for this post.
        $custom_fields = array('_video_id', '_episode', '_length');
        foreach ($custom_fields as $custom_field) {
            if (isset($_POST[$custom_field])) {
                $sanitized_value = sanitize_text_field($_POST[$custom_field]);
                update_post_meta($post_id, $custom_field, $sanitized_value);
            }
        }
    }

    public function enqueue_assets() {
        wp_register_script('makigas-video-plugin-meta-box', plugins_url('videoman/js/metabox.min.js'));
        wp_enqueue_script('makigas-video-plugin-meta-box');
    }

}
