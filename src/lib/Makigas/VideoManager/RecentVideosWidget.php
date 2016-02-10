<?php

namespace Makigas\VideoManager;

defined('ABSPATH') or die('No way, punk');

/**
 * This widget fetches recent videos from database and renders them in a list.
 * This widget is used in the front page to display the recent videos, although
 * it might be adapted for other uses.
 */
class RecentVideosWidget extends \WP_Widget {
    
    public function __construct() {
        $widget_ops = array(
            'classname' => 'makigas-recent-videos',
            'description' => __( 'Recent Videos Widget', 'makigas-videoman' )
        );
        $title = __( 'Recent Videos', 'makigas-videoman' );
        parent::__construct( 'makigas-recent-videos', $title, $widget_ops );
    }
    
    /**
     * This function renders the widget using HTML in the public page.
     * @param type $args  widget arguments
     * @param type $instance  instance data
     */
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $this->id_base);
        $button_text = empty($instance['button_text']) ? '' : $instance['button_text'];
        $button_link = empty($instance['button_href']) ? '#' : $instance['button_href'];
        ?>
<div class="home-section">
    <h1><?php echo $title; ?></h1>
    <div class="row">
        <?php $recent_videos = new \WP_Query('post_type=video&showposts=3'); ?>
        <?php while ($recent_videos->have_posts()): ?>
            <?php $recent_videos->the_post(); ?>
            <div class="col-sm-4">
                <?php $id = get_post_meta(get_the_ID(), '_video_id', true); ?>
                <a href="<?php the_permalink(); ?>">
                    <img style="width: 100%;" src="https://i1.ytimg.com/vi/<?php echo $id; ?>/mqdefault.jpg">
                </a>
                <p class="lead"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
            </div>
        <?php endwhile; ?>
    </div>
    <p class="text-center">
        <a href="<?php echo $button_link; ?>" class="btn btn-lg btn-primary"><?php echo $button_text; ?></a>
    </p>
    </section>
</div>
        <?php
    }
    
    /**
     * Renders the HTML contents for the editor when the admin is modifying.
     * @param type $inst current instance values (to be filled in the fields).
     */
    public function form( $inst ) {
        /* Safety check: provide default values. */
        $instance = wp_parse_args((array) $inst, array(
            'title' => __( 'Recent Videos', 'makigas-videoman' ),
            'button_text' => __( 'See recent episodes', 'makigas-videoman' ),
            'button_href' => __( '#', 'makigas-videoman' )
        ));
        ?>
<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>

<p><label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e( 'Button Text:', 'makigas-videoman' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr( $instance['button_text'] ); ?>" /></p>

<p><label for="<?php echo $this->get_field_id('button_href'); ?>"><?php _e( 'Button Link:', 'makigas-videoman' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('button_href'); ?>" name="<?php echo $this->get_field_name('button_href'); ?>" type="text" value="<?php echo esc_attr( $instance['button_href'] ); ?>" /></p>
        <?php
    }
    
    /**
     * Saves widget data.
     * @param type $new_inst  new instance - values provided by the user.
     * @param type $old_inst  old instance - values existing in database.
     */
    public function update( $new_inst, $old_inst ) {
        $out_instance = $old_inst;
        $out_instance['title'] = sanitize_text_field( $new_inst['title'] );
        $out_instance['button_text'] = sanitize_text_field( $new_inst['button_text'] );
        $out_instance['button_href'] = sanitize_text_field( $new_inst['button_href'] );
        return $out_instance;
    }
    
}