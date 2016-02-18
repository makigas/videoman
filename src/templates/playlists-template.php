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
 * Playlists page. This page will list all the playlists in this page,
 * organized in playlist categories. It is expected that root playlists are
 * categories, and child playlists are part of a category playlist.
 * 
 * @package makigas-videoman
 * @version 1.1.0
 * @since 1.1.0
 */
defined( 'ABSPATH' ) || die( 'You are not supposed to run this, punk.' );

require_once 'functions.php';

get_header();
?>

<div class="body-container container">
<h1>Playlists</h1>

<?php
$root_playlists = get_terms( 'playlist', array( 'parent' => 0 ) );
foreach ( $root_playlists as $root_playlist ) {
	echo '<div class="row">';
	echo '<div class="col-md-12">';
	echo '<h2>' . $root_playlist->name . '</h2>';
	echo '<p class="lead">' . $root_playlist->description . '</h3>';
	echo '</div>';
	
	$playlists = get_terms( 'playlist', array( 'child_of' => $root_playlist->term_id ) );
	foreach ($playlists as $playlist ) {
		echo '<div class="col-sm-6 col-md-3">';
		echo '<a class="thumbnail" href="' . esc_url( get_term_link( $playlist ) ) . '">';
		echo '<img src="' . query_thumbnail( $playlist->slug, 'mqdefault' ) . '" />';
		echo '</a>';
		echo '<a href="' . esc_url( get_term_link( $playlist ) ) . '">' . $playlist->name . '</a>';
		echo '</div>';
	}
	
	echo '</div>';
}
?>

</div>

<?php get_footer();
