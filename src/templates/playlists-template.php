<?php

/**
 * Template Name: Playlist List
 * 
 * This file is part of the makigas v4 theme by danirod
 * Copyright (C) 2015 Dani Rodríguez <danirod@outlook.com>
 * All rights reserved.
 * 
 * @package makigas
 */

if (!defined('ABSPATH')) {
	header('HTTP/1.1 403 Forbidden');
	die('You are not supposed to directly view this file, buddy.');
}

?>

<?php get_header(); ?>

<div class="body-container container">
  <h2>Series</h2>
  <?php
    /**
     * Count how many playlists we have printed out. You might wonder why?
     * Because I need to manually put a Bootstrap row every two playlists
     * so that the grid is rendered correctly.
     */
    $pl_num = 0;
    
    $playlists = get_terms( 'playlist' );
    foreach($playlists as $playlist):     
      /* Open a new row in case the playlist is on the left. */
      if (($pl_num % 2) == 0) { echo '<div class="row">'; }
      
      ?><div class="col-sm-6">
        <div class="thumbnail">
          <a href="<?php echo esc_url( get_term_link( $playlist ) ); ?>">
            <img src="<?php echo query_thumbnail( $playlist->slug, 'maxresdefault' ); ?>" alt="<?php echo $playlist->name; ?>">
          </a>
          <div class="caption">
            <h3><a href="<?php echo esc_url( get_term_link( $playlist ) ); ?>"><?php echo $playlist->name; ?></a></h3>
            <p><?php echo $playlist->description; ?></p>
          </div>
        </div>
      </div><?php
      
      /* Close this row in case this is the playlist on the right. */
      if ((++$pl_num % 2) == 0) { echo '</div>'; }
    endforeach;
    
    /* To make sure that the last row is closed all the times. */
    if (($pl_num % 2) == 1) { echo '</div>'; }
  ?>
</div>

<?php get_footer(); ?>
