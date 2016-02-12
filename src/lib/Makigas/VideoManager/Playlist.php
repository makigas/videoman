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

/**
 * Playlists are custom taxonomies that contain videos.
 * The user can create a taxonomy and register videos there.
 */
class Playlist {

    /**
     * This function registers the playlist taxonomy.
     */
    public function register_playlist() {
        // Labels array. This should be internationalizated.
        $labels = array(
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
        );

        // Playlist arguments.
        $args = array(
            'label' => __( 'Playlist', 'makigas-videoman' ),
            'labels' => $labels,
            'query_var' => true,
            // We WANT the playlists to be a hierarchical type.
            // I'm not sure if I want the playlist to be hierarchical because
            // of supporting child playlists, or just because I don't want
            // to use tags for this.
            'hierarchical' => true,
            'rewrite' => array( 'slug' => get_option( 'makigas-videoman-videos-slug' ) )
        );

        register_taxonomy('playlist', 'video', $args);
    }

}
