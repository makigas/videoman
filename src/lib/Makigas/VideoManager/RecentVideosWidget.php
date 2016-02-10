<?php

namespace Makigas\VideoManager;

defined('ABSPATH') or die('No way, punk');

/**
 * This widget displays recent videos from the database.
 */
class RecentVideosWidget extends \WP_Widget {
    
    /**
     * Create a new instance for the widget.
     */
    public function __construct() {
        $widget_ops = array(
            'classname' => 'makigas-recent-videos',
            'description' => __( 'Recent Videos Widget', 'makigas-videoman' )
        );
        $title = __( 'Recent Videos', 'makigas-videoman' );
        parent::__construct( 'makigas-recent-videos',$title, $widget_ops );
    }
    
    /**
     * To be executed when the widget has to be rendered.
     * @param type $args
     * @param type $instance
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
     * To be executed when the admin form has to be rendered.
     * @param type $instance
     */
    public function form( $inst ) {
        /* Prepare settings to be rendered. */
        $instance = wp_parse_args((array) $inst, array(
            'title' => __( 'Recent Videos', 'makigas-videoman' ),
            'button_text' => __( 'See recent episodes', 'makigas-videoman' ),
            'button_href' => __( '#', 'makigas-videoman' )
        ));
        
        $title = sanitize_text_field( $instance[ 'title' ] );
        $button_text = sanitize_text_field( $instance[ 'button_text' ] );
        $button_link = sanitize_text_field( $instance[ 'button_href' ] );
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        
        <p><label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e( 'Button Text:', 'makigas-videoman' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr($button_text); ?>" /></p>
        
        <p><label for="<?php echo $this->get_field_id('button_href'); ?>"><?php _e( 'Button Link:', 'makigas-videoman' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_href'); ?>" name="<?php echo $this->get_field_name('button_href'); ?>" type="text" value="<?php echo esc_attr($button_link); ?>" /></p>
        <?php
    }
    
    /**
     * To be executed when the widget has to be saved.
     * @param type $new_inst
     * @param type $old_inst
     */
    public function update( $new_inst, $old_inst ) {
        $out_instance = $old_inst;
        
        $out_instance[ 'title' ] = sanitize_text_field( $new_inst[ 'title' ] );
        $out_instance[ 'button_text' ] = sanitize_text_field( $new_inst[ 'button_text' ] );
        $out_instance[ 'button_href' ] = sanitize_text_field( $new_inst[ 'button_href' ] );
        
        return $out_instance;
    }
    
}