<?php
/*
Plugin Name: Events Directory
Description: Manages events with custom post type and date metadata
Version: 1.0
Author: Kaushik Sannidhi
*/

// Register Custom Post Type: Event
function ed_register_event_post_type() {
    $labels = array(
        'name'                  => __('Events', 'events-directory'),
        'singular_name'         => __('Event', 'events-directory'),
        'menu_name'             => __('Events', 'events-directory'),
        'add_new_item'          => __('Add New Event', 'events-directory'),
        'edit_item'             => __('Edit Event', 'events-directory'),
        'new_item'              => __('New Event', 'events-directory'),
        'view_item'             => __('View Event', 'events-directory'),
        'all_items'             => __('All Events', 'events-directory'),
        'search_items'          => __('Search Events', 'events-directory'),
        'not_found'             => __('No events found.', 'events-directory'),
        'not_found_in_trash'    => __('No events found in Trash.', 'events-directory'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'events' ),
    );

    register_post_type( 'event', $args );
}
add_action( 'init', 'ed_register_event_post_type' );

// Add Meta Box for Event Date
function ed_add_event_meta_boxes() {
    add_meta_box(
        'ed_event_date',
        __('Event Date', 'events-directory'),
        'ed_event_date_callback',
        'event',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'ed_add_event_meta_boxes' );

// Meta Box Callback Function
function ed_event_date_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ed_event_nonce' );
    $event_date = get_post_meta( $post->ID, '_ed_event_date', true );
    ?>
    <label for="ed_event_date_field"><?php _e( 'Date:', 'events-directory' ); ?></label>
    <input type="date" id="ed_event_date_field" name="ed_event_date_field" value="<?php echo esc_attr( $event_date ); ?>" />
    <?php
}

// Save Event Date Meta Data
function ed_save_event_meta( $post_id ) {
    if ( ! isset( $_POST['ed_event_nonce'] ) || ! wp_verify_nonce( $_POST['ed_event_nonce'], basename( __FILE__ ) ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['ed_event_date_field'] ) ) {
        update_post_meta( $post_id, '_ed_event_date', sanitize_text_field( $_POST['ed_event_date_field'] ) );
    }
}
add_action( 'save_post', 'ed_save_event_meta' );

// Use Custom Template for Single Event
function ed_single_event_template( $single_template ) {
    global $post;

    if ( 'event' === $post->post_type ) {
        $custom_template = plugin_dir_path( __FILE__ ) . 'templates/single-event.php';
        if ( file_exists( $custom_template ) ) {
            return $custom_template;
        } else {
            // Fallback to default content output in case the template file is missing
            add_filter( 'the_content', function( $content ) {
                global $post;
                $event_date = get_post_meta( $post->ID, '_ed_event_date', true );
                ob_start();
                ?>
                <div class="ed-single-event" style="max-width: 800px; margin: 0 auto; font-family: Arial, sans-serif; line-height: 1.6;">
                    <h1 style="text-align: center; font-size: 2.5rem; color: #167a87; margin-bottom: 1rem;">
                        <?php the_title(); ?>
                    </h1>
                    <p style="text-align: center; font-size: 1.2rem; color: #555; margin-bottom: 2rem;">
                        <?php echo esc_html( date( 'F j, Y', strtotime( $event_date ) ) ); ?>
                    </p>
                    <?php if ( has_post_thumbnail() ) { ?>
                        <div style="text-align: center; margin-bottom: 2rem;">
                            <?php the_post_thumbnail( 'large', [ 'style' => 'max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);' ] ); ?>
                        </div>
                    <?php } ?>
                    <div style="background: #f9f9f9; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <?php the_content(); ?>
                    </div>
                </div>
                <?php
                return ob_get_clean();
            }, 10, 1 );

            return $single_template; // Keep default theme's template
        }
    }
    return $single_template;
}
add_filter( 'single_template', 'ed_single_event_template' );

// Setup theme support for event thumbnails
function ed_setup_theme() {
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'event-thumbnail', 100, 100, true ); // Crop to 100x100 pixels
}
add_action( 'after_setup_theme', 'ed_setup_theme' );
