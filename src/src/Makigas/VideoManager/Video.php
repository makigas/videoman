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
            'name' => __('Videos'),
            'singular_name' => __('Video')
        );

        // Arguments array.
        $args = array(
            'labels' => $labels,
            'description' => 'YouTube Video and information.',
            'supports' => array('title', 'editor', 'excerpt'),
            'rewrite' => array('slug' => 'series/%playlist%'),
            'public' => true,
            'query_var' => true,
            'has_archive' => true,
            'taxonomies' => array('playlist')
        );

        // Add video post type.
        register_post_type('video', $args);

        add_filter('post_type_link', array($this, 'filter_permalink'), 1, 3);
    }

    public function filter_permalink($url, $post) {
        // If this post is not a video, don't filter.
        if ('video' !== get_post_type($post)) {
            return $url;
        }

        // FIXME: There should be a way to manage permalinks for videos
        // that are not in a playlist.
        // Get the video playlist.
        $playlists = get_the_terms($post, 'playlist');

        // The video is in at least one playlist. Use the first playlist.
        // A video should not be anyway in more than a playlist.
        $playlist_slug = $playlists[0]->slug;
        return str_replace('%playlist%', $playlist_slug, $url);
    }

    public function makigas_add_rewrite_rules() {
        // Rewrite rule for the pagination system.
        add_rewrite_rule('^series/([^/]+)/page/([0-9]+)/?$', 'index.php?playlist=$matches[1]&paged=$matches[2]', 'top');

        // Rewrite rule for the video page.
        add_rewrite_rule('^series/([^/]+)/([^/]+)/?$', 'index.php?video=$matches[2]', 'top');

        // Rewrite rule for the page 1 of a playlist.
        add_rewrite_rule('^series/([^/]+)/?$', 'index.php?playlist=$matches[1]', 'top');

        // Rewrite rule for the videos.
        add_rewrite_rule('^videos/page/([0-9]+)/?$', 'index.php?post_type=video&paged=$matches[1]', 'top');

        // Rewrite rule for the videos.
        add_rewrite_rule('^videos/?$', 'index.php?post_type=video', 'top');
    }

}
