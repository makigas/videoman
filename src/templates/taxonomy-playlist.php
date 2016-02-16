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
 * This page lists the videos in a playlist. It is used when the playlist page
 * is browsed. The requested playlist is passed as parameter in the URL.
 * 
 * @package makigas-videoman
 * @version 1.1.0
 */
defined( 'ABSPATH' ) || die( 'You are not supposed to run this, punk.' );

require_once 'functions.php';

get_header();
?>

<div class="body-container container">
    
    <header class="archive-header">
        <h2><?php single_term_title(); ?></h2>
        <p class="lead"><?php echo term_description(); ?></p>
    </header>
        
    <?php if (have_posts()): ?>
        <nav>
            <ul class="pager">
                <li class="previous"><?php previous_posts_link( __( 'Previous page', 'makigas-theme' ) ); ?></li>
                <li class="next"><?php next_posts_link( __( 'Next page', 'makigas-theme' ) ); ?></li>
            </ul>
        </nav>
    
        <ul class="list-group">
        <?php
        query_posts( $query_string . '&order=ASC');
        ?>
        <?php while (have_posts()): the_post(); ?>
            <a href="<?php the_permalink(); ?>" class="list-group-item">
                <?php $id = get_post_meta( get_the_ID(), '_video_id', true ); ?>
                <div class="media">
                    <div class="media-left">
                        <img height=80" class="media-object" src="https://i1.ytimg.com/vi/<?php echo $id; ?>/mqdefault.jpg" alt="<?php the_title(); ?>">
                    </div>
                    <div class="media-body">
                        <?php
                        $episode = get_post_meta( get_the_ID(), '_episode', true);
                        $title = ($episode) ? $episode . '. ' . get_the_title() : get_the_title();
                        ?>
                        <h4><?php echo $title; ?></h4>
                        <?php the_excerpt(); ?>
                    </div>
                </div>
            </a>
        <?php endwhile; ?>
        </ul>
    
        <nav>
            <ul class="pager">
                <li class="previous"><?php previous_posts_link( __( 'Previous page', 'makigas-theme' ) ); ?></li>
                <li class="next"><?php next_posts_link( __( 'Next page', 'makigas-theme' ) ); ?></li>
            </ul>
        </nav>
    <?php else: ?>
        <p><?php _e( 'Sorry, there are no videos here.', 'makigas-theme' ); ?></p>
    <?php endif; ?>
    
</div>

<?php get_footer(); ?>