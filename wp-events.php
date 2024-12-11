<?php
/*
Plugin Name: Kaushik Sannidhi's WP Events
Description: An events directory with calendar view and customizable UI colors.
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

// Add Settings Page
function ed_add_settings_page() {
    add_options_page(
        __('Events Directory Settings', 'events-directory'),
        __('Events Directory', 'events-directory'),
        'manage_options',
        'ed-settings',
        'ed_render_settings_page'
    );
}
add_action( 'admin_menu', 'ed_add_settings_page' );

// Render Settings Page
function ed_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e( 'Events Directory Settings', 'events-directory' ); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'ed_settings_group' );
            do_settings_sections( 'ed-settings' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register Settings
function ed_register_settings() {
    register_setting( 'ed_settings_group', 'ed_colors' );

    add_settings_section(
        'ed_color_settings_section',
        __('Color Settings', 'events-directory'),
        null,
        'ed-settings'
    );

    add_settings_field(
        'ed_background_color',
        __('Background Color', 'events-directory'),
        'ed_background_color_callback',
        'ed-settings',
        'ed_color_settings_section'
    );

    add_settings_field(
        'ed_text_color',
        __('Text Color', 'events-directory'),
        'ed_text_color_callback',
        'ed-settings',
        'ed_color_settings_section'
    );

    // Add more color fields as needed
}
add_action( 'admin_init', 'ed_register_settings' );

// Callbacks for Settings Fields
function ed_background_color_callback() {
    $options = get_option( 'ed_colors' );
    ?>
    <input type="text" name="ed_colors[background]" value="<?php echo esc_attr( $options['background'] ?? '#ffffff' ); ?>" class="ed-color-field" />
    <?php
}

function ed_text_color_callback() {
    $options = get_option( 'ed_colors' );
    ?>
    <input type="text" name="ed_colors[text]" value="<?php echo esc_attr( $options['text'] ?? '#000000' ); ?>" class="ed-color-field" />
    <?php
}

// Enqueue Admin Scripts and Styles
function ed_enqueue_admin_assets( $hook ) {
    if ( 'settings_page_ed-settings' != $hook ) {
        return;
    }
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'ed-admin-script', plugin_dir_url( __FILE__ ) . 'js/ed-admin.js', array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'ed_enqueue_admin_assets' );

// Enqueue Front-End Styles
function ed_enqueue_front_assets() {
    wp_enqueue_style( 'ed-styles', plugin_dir_url( __FILE__ ) . 'css/ed-styles.css' );

    // Enqueue FullCalendar Assets
    wp_enqueue_style( 'fullcalendar-css', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' );
    wp_enqueue_script( 'fullcalendar-js', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js', array( 'jquery' ), null, true );

    // Enqueue Custom Calendar Script
    wp_enqueue_script( 'ed-calendar', plugin_dir_url( __FILE__ ) . 'js/ed-calendar.js', array( 'fullcalendar-js' ), false, true );

    // Localize Events Data
    wp_localize_script( 'ed-calendar', 'edEventsData', ed_get_events_data() );
}
add_action( 'wp_enqueue_scripts', 'ed_enqueue_front_assets' );

