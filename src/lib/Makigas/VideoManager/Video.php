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

class Video {

    /**
     * Register the post type that defines a video. This uses WordPress custom post types.
     * This method should generate the post type but not any associated taxonomies such
     * as playlists.
     */
    public function register_post_type() {
        // Labels array.
        $labels = array(
            'name' => __( 'Videos', 'makigas-videoman' ),
            'singular_name' => __( 'Video', 'makigas-videoman' )
        );

        // Arguments array.
        $args = array(
            'labels' => $labels,
            'description' => __( 'YouTube video including metadata' ),
            'supports' => array('title', 'editor', 'excerpt'),
            'rewrite' => array('slug' => get_option( 'makigas-videoman-videos-slug' ) . '/%playlist%'),
            'public' => true,
            'query_var' => true,
            'has_archive' => true,
            'taxonomies' => array('playlist')
        );

        // Add video post type.
        register_post_type('video', $args);
    }

    

    

}
