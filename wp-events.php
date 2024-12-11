<?php
/*
Plugin Name: Kaushik Sannidhi's WP Events
Description: An events directory with calendar view and customizable UI colors.
Version: 1.0
Author: Kaushik Sannidhi
*/

function ed_register_event_post_type() {
    $labels = array(
        'name'               => 'Events',
        'singular_name'      => 'Event',
        'menu_name'          => 'Events',
        'name_admin_bar'     => 'Event',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Event',
        'new_item'           => 'New Event',
        'edit_item'          => 'Edit Event',
        'view_item'          => 'View Event',
        'all_items'          => 'All Events',
        'search_items'       => 'Search Events',
        'not_found'          => 'No events found.',
        'not_found_in_trash' => 'No events found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'menu_icon'          => 'dashicons-calendar',
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'events' ),
    );

    register_post_type( 'event', $args );
}
add_action( 'init', 'ed_register_event_post_type' );

function ed_add_event_meta_boxes() {
    add_meta_box(
        'ed_event_date',
        'Event Date',
        'ed_event_date_callback',
        'event',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'ed_add_event_meta_boxes' );

function ed_event_date_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ed_event_nonce' );
    $event_date = get_post_meta( $post->ID, '_ed_event_date', true );
    echo '<label for="ed_event_date">Date:</label>';
    echo '<input type="date" id="ed_event_date" name="ed_event_date" value="' . esc_attr( $event_date ) . '" />';
}

function ed_save_event_meta( $post_id ) {
    if ( ! isset( $_POST['ed_event_nonce'] ) || ! wp_verify_nonce( $_POST['ed_event_nonce'], basename( __FILE__ ) ) ) {
        return $post_id;
    }
    $new_date = ( isset( $_POST['ed_event_date'] ) ? sanitize_text_field( $_POST['ed_event_date'] ) : '' );
    update_post_meta( $post_id, '_ed_event_date', $new_date );
}
add_action( 'save_post', 'ed_save_event_meta' );

function ed_events_shortcode() {
    ob_start();

    // Query Events
    $args = array(
        'post_type'      => 'event',
        'posts_per_page' => -1,
        'meta_key'       => '_ed_event_date',
        'orderby'        => 'meta_value',
        'order'          => 'DESC',
    );
    $events = new WP_Query( $args );

    // Prepare Events Data
    $events_data = array();
    while ( $events->have_posts() ) {
        $events->the_post();
        $events_data[] = array(
            'title' => get_the_title(),
            'date'  => get_post_meta( get_the_ID(), '_ed_event_date', true ),
            'link'  => get_permalink(),
            'image' => get_the_post_thumbnail_url( get_the_ID(), 'full' ),
        );
    }
    wp_reset_postdata();

    // Display Calendar (Placeholder - integrate a JS calendar library for full functionality)
    echo '<div id="ed-calendar"></div>';

    // Display Events List
    echo '<div class="ed-events-list">';
    foreach ( $events_data as $event ) {
        echo '<div class="ed-event-item">';
        echo '<h2><a href="' . esc_url( $event['link'] ) . '">' . esc_html( $event['title'] ) . '</a></h2>';
        echo '<p>' . esc_html( date( 'F j, Y', strtotime( $event['date'] ) ) ) . '</p>';
        if ( $event['image'] ) {
            echo '<img src="' . esc_url( $event['image'] ) . '" alt="' . esc_attr( $event['title'] ) . '">';
        }
        echo '</div>';
    }
    echo '</div>';

    return ob_get_clean();
}
add_shortcode( 'events_directory', 'ed_events_shortcode' );

function ed_register_settings() {
    register_setting( 'ed_settings_group', 'ed_colors' );
}
add_action( 'admin_init', 'ed_register_settings' );

function ed_add_admin_menu() {
    add_menu_page(
        'Events Directory Settings',
        'Event Colors',
        'manage_options',
        'ed_settings',
        'ed_settings_page',
        'dashicons-art',
        100
    );
}
add_action( 'admin_menu', 'ed_add_admin_menu' );

function ed_settings_page() {
    ?>
    <div class="wrap">
        <h1>Events Directory Color Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'ed_settings_group' ); ?>
            <?php do_settings_sections( 'ed_settings_group' ); ?>
            <?php $colors = get_option( 'ed_colors' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Background Color</th>
                    <td><input type="text" name="ed_colors[background]" value="<?php echo esc_attr( $colors['background'] ?? '#ffffff' ); ?>" class="ed-color-field" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Text Color</th>
                    <td><input type="text" name="ed_colors[text]" value="<?php echo esc_attr( $colors['text'] ?? '#000000' ); ?>" class="ed-color-field" /></td>
                </tr>
                <!-- Add more color fields as needed -->
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function ed_enqueue_admin_scripts( $hook_suffix ) {
    if ( 'toplevel_page_ed_settings' !== $hook_suffix ) {
        return;
    }
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'ed-admin-script', plugin_dir_url( __FILE__ ) . 'js/ed-admin.js', array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'ed_enqueue_admin_scripts' );

function ed_enqueue_front_scripts() {
    wp_enqueue_style( 'ed-styles', plugin_dir_url( __FILE__ ) . 'css/ed-styles.css' );
}
add_action( 'wp_enqueue_scripts', 'ed_enqueue_front_scripts' );

function ed_single_template( $single ) {
    global $post;

    if ( $post->post_type == 'event' ) {
        if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/single-event.php' ) ) {
            return plugin_dir_path( __FILE__ ) . 'templates/single-event.php';
        }
    }
    return $single;
}
add_filter( 'single_template', 'ed_single_template' );

function ed_output_custom_styles() {
    $colors = get_option( 'ed_colors' );
    $custom_css = "
        .ed-event-item {
            background-color: {$colors['background']};
            color: {$colors['text']};
        }
    ";
    wp_add_inline_style( 'ed-styles', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'ed_output_custom_styles' );
<?php>

