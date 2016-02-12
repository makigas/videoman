<?php get_header(); ?>

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