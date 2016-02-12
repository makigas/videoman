<?php
/*
 * This file is part of the makigas v4 theme by danirod
 * Copyright (C) 2015 Dani Rodríguez <danirod@outlook.com>
 * All rights reserved.
 */

/**
 * Archive video. This page will list all the videos available in the system
 * in reverse chronollogical order. The most recent videos will be displayed
 * on the first pages and it will be possible to navigate to older pages.
 */
if (!defined('ABSPATH')) {
	header('HTTP/1.1 403 Forbidden');
	die('You are not supposed to directly view this file, buddy.');
}

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