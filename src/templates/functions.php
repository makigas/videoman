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

/**
 * @package makigas-videoman
 * @version 1.1.0
 */
defined( 'ABSPATH' ) || die( 'You are not supposed to run this, punk.' );

/**
 * Get the YouTube thumbnail associated with a video.
 *
 * @param string $video_id  the video ID for the requested thumbnail.
 * @param string $thumbnail  the thumbnail ID (default, maxresdefault...)
 * @return string  the requested thumbnail URL.
 */
function get_youtube_thumbnail( $video_id, $thumbnail = 'default' ) {
    return 'https://i1.ytimg.com/vi/' . $video_id . '/' . $thumbnail . '.jpg';
}

/**
 * This function will print a link to the previous video in case it exists.
 * Else, it will print a message saying that this is the first video from the
 * series.
 * @param string $before  text to put before the link
 * @param string $after  text to put after the link
 */
function print_previous_video_link( $before = '', $after = '' ) {
    $previous_video = get_previous_post( TRUE, ' ', 'playlist' );
    if ( $previous_video ) {
        $number = get_post_meta( $previous_video->ID, '_episode', true );
        previous_post_link( $before . '%link' . $after, $number . '. %title', TRUE, ' ', 'playlist' );
    } else {
        _e( 'You are at the first episode', 'makigas-theme' );
    }
}

/**
 * This function will print a link to the next video in case it exists. Else
 * it will print a message saying that this is the last video from the series.
 * @param string $before  text to put before the link
 * @param string $after  text to put after the link
 */
function print_next_video_link( $before = '', $after = '' ) {
    $next_video = get_next_post( TRUE, ' ', 'playlist' );
    if ( $next_video ) {
        $number = get_post_meta( $next_video->ID, '_episode', true );
        next_post_link( $before . '%link' . $after, $number . '. %title', TRUE, ' ', 'playlist' );
    } else {
       _e( 'You are at the last episode', 'makigas-theme' );
    }
}
