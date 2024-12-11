<?php get_header(); ?>

<div class="ed-single-event">
    <?php
    while ( have_posts() ) : the_post();
        $event_date = get_post_meta( get_the_ID(), '_ed_event_date', true );
        ?>
        <h1><?php the_title(); ?></h1>
        <p><?php echo esc_html( date( 'F j, Y', strtotime( $event_date ) ) ); ?></p>
        <?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
        <div><?php the_content(); ?></div>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
