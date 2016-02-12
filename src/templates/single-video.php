<?php

/* 
 * This file is part of the makigas theme by danirod
 * Copyright (C) 2015 Dani Rodríguez <danirod@outlook.com>
 */

// FPD check
if (!defined('ABSPATH')) {
    header('HTTP/1.1 403 Forbidden');
    // TO-DO: Show a more appealing error message.
    die('You are not supposed to directly view this file, buddy.');
}

?>

<?php get_header(); ?>

<div class="body-container container">
  <?php the_post(); ?>
  <header class="post-header">
    <h2><?php the_title(); ?></h2>
    <?php if (get_the_terms(get_the_ID(), 'playlist')): ?>
    <p class="lead"><?php _e( 'An episode from:', 'makigas-theme' ); ?> <?php echo the_terms(get_the_ID(), 'playlist'); ?></p>      
    <?php endif; ?>
  </header>
    
    <div class="row">
        <div class="col-md-8">
            
            <?php if (get_post_meta(get_the_ID(), '_episode', true)): ?>
            
            <div class="row hidden-xs hidden-sm">
                <p class="col-md-6"><?php print_previous_video_link( '&laquo; ', '' ); ?></p>
                <p class="col-md-6 text-right"><?php print_next_video_link( '', ' &raquo;' ); ?>
            </div>
            <div class="visible-sm visible-xs">
                <p><?php print_previous_video_link( __( 'Previous episode: ', 'makigas-theme' ), '' ); ?></p>
                <p><?php print_next_video_link( __( 'Next episode: ', 'makigas-theme' ), '' ); ?></p>
            </div>
            
            <?php endif; ?>
            
            <div class="makigas-player embed-responsive embed-responsive-16by9">
                <?php $id = get_post_meta(get_the_ID(), '_video_id', true); ?>
                <iframe class="embed-responsive-item" src="https://youtube.com/embed/<?php echo $id; ?>/"></iframe>
            </div>
            
            <p>
                <a href="https://www.youtube.com/watch?v=<?php echo $id; ?>" target="_blank" class="btn btn-default">
                    <i class="fa fa-youtube"></i> <?php echo __( 'Watch on YouTube', 'makigas-theme' ); ?>
                </a>
            </p>
        </div>
        <div class="col-md-4">
            <p class="lead"><?php echo __('Video Description:', 'makigas-theme' ); ?></p>
            <?php the_content(); ?>
        </div>
    </div>
    
</div>

<?php get_footer(); ?>