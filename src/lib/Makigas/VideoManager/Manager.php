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

class Manager {

    private $video, $playlist, $video_metabox;

    public function __construct() {
        $this->video = new Video;
        $this->playlist = new Playlist;
        $this->video_metabox = new VideoMetabox;
    }

    public function init() {
        add_action('init', [ $this->video, 'register_post_type']);
        add_action('init', [ $this->video, 'makigas_add_rewrite_rules']);
        add_action('init', [ $this->playlist, 'register_playlist']);
        add_action('add_meta_boxes', [ $this->video_metabox, 'register_metabox']);
        add_action('admin_enqueue_scripts', [ $this->video_metabox, 'enqueue_assets']);
        add_action('save_post', [ $this->video_metabox, 'save_post']);
    }

}
