<?php

namespace Makigas\VideoManager;

defined('ABSPATH') or die('No way, punk');

class RecentVideosWidget extends \WP_Widget {
    
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
        ?>
        <div class="home-section">
                <h1><?php _e( 'Recent videos', 'makigas-videoman' ) ?></h1>
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
                    <a href="/series/" class="btn btn-lg btn-primary"><?php _e( 'Ver todas las series &raquo;', 'makigas-videoman' ); ?></a>
                </p>
                </section>
            </div>
        <?php
    }
    
    /**
     * To be executed when the admin form has to be rendered.
     * @param type $instance
     */
    public function form( $instance ) {
        /* No settings. */
        ?>
        <p><?php _e( 'This plugin has no configurable settings at the moment.', 'makigas-videoman' ); ?></p>
        <?php
    }
    
    /**
     * To be executed when the widget has to be saved.
     * @param type $new_inst
     * @param type $old_inst
     */
    public function update( $new_inst, $old_inst ) {
        /* No options means nothing to save. */
    }
    
}