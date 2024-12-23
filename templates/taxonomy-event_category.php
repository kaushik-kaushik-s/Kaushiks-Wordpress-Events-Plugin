<?php
get_header();
?>

<div class="content-area">
    <main class="site-main">
        <?php if (have_posts()) : ?>
            <header class="page-header">
                <h1 class="page-title"><?php single_term_title(); ?></h1>
                <?php the_archive_description('<div class="taxonomy-description">', '</div>'); ?>
            </header>

            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
                    </header>
                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>

            <?php the_posts_navigation(); ?>
        <?php else : ?>
            <p><?php _e('No events found in this category.', 'events-directory'); ?></p>
        <?php endif; ?>
    </main>
</div>

<?php
get_sidebar();
get_footer();
?>
