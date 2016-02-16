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
 * Archive video. This page will list all the videos available in the system
 * in reverse chronollogical order. The most recent videos will be displayed
 * on the first pages and it will be possible to navigate to older pages.
 * 
 * @package makigas-videoman
 * @version 1.1.0
 */
defined( 'ABSPATH' ) || die( 'You are not supposed to run this, punk.' );

require_once 'functions.php';

get_header();
?>

<div class="body-container container">

<?php if (have_posts()): ?>

<nav>
	<ul class="pager">
		<li class="previous"><?php previous_posts_link( __( 'Previous page' ) ); ?></li>
		<li class="next"><?php next_posts_link( __('Next page') ); ?></li>
	</ul>
</nav>

<ul class="list-group makigas-video-archive">
	
<?php while ( have_posts() ) : ?>
	
	<?php the_post(); ?>
	
	<a href="<?php the_permalink(); ?>" class="list-group-item">
		<div class="media">
			<div class="media-left">
				<?php $thumbnail = get_youtube_thumbnail( get_post_meta( get_the_ID(), '_video_id', true ), 'mqdefault' ); ?>
				<img class="media-object" src="<?php echo $thumbnail; ?>" alt="<?php the_title(); ?>">
			</div>
			<div class="media-body">
				<?php
				/* If this video is part of a series, put the number. */
				$episode = get_post_meta(get_the_ID(), '_episode', true);
				$title = ($episode) ? $episode . '. ' . get_the_title() : get_the_title();
				?>
				<h4><?php
					$episode = get_post_meta( get_the_ID(), '_episode', true );
					echo (($episode) ? ($episode . '. ') : '') . get_the_title();
				?><?php get_the_title(); ?></h4>
				<?php the_excerpt(); ?>
			</div>
		</div>
	</a>
	
<?php endwhile; ?>
	
</ul>

<nav>
	<ul class="pager">
		<li class="previous"><?php previous_posts_link( __( 'Previous page' ) ); ?></li>
		<li class="next"><?php next_posts_link( __('Next page') ); ?></li>
	</ul>
</nav>
	
<?php else: ?>
	<h2><?php _e( 'No videos have been found here.', 'makigas-theme' ); ?></h2>
<?php endif; ?>
	
</div>

<?php get_footer();