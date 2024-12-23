<?php
/*
Plugin Name: Kaushik Sannidhi's Events Directory
Description: Manages events with custom post type and date metadata
Version: 1.3
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
        'supports'           => array('title', 'editor', 'thumbnail', 'comments'),
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'events'),
        'show_in_rest'       => true,
        'taxonomies'         => array('event_category'), // Use only event_category
    );

    register_post_type('event', $args);
}
add_action('init', 'ed_register_event_post_type');

// Register Custom Taxonomy: Event Category
function ed_register_event_category_taxonomy() {
    $labels = array(
        'name'              => __('Event Categories', 'events-directory'),
        'singular_name'     => __('Event Category', 'events-directory'),
        'search_items'      => __('Search Event Categories', 'events-directory'),
        'all_items'         => __('All Event Categories', 'events-directory'),
        'parent_item'       => __('Parent Event Category', 'events-directory'),
        'parent_item_colon' => __('Parent Event Category:', 'events-directory'),
        'edit_item'         => __('Edit Event Category', 'events-directory'),
        'update_item'       => __('Update Event Category', 'events-directory'),
        'add_new_item'      => __('Add New Event Category', 'events-directory'),
        'new_item_name'     => __('New Event Category Name', 'events-directory'),
        'menu_name'         => __('Event Categories', 'events-directory'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'show_in_rest'      => true,
    );

    register_taxonomy('event_category', array('event'), $args);
}
add_action('init', 'ed_register_event_category_taxonomy');

// Shortcode to display event and post categories
function ed_event_categories_shortcode() {
    $event_categories = get_terms(array(
        'taxonomy' => 'event_category',
        'hide_empty' => false,
    ));

    $post_categories = get_terms(array(
        'taxonomy' => 'category',
        'hide_empty' => false,
    ));

    $output = '<ul class="event-categories">';
    if (!empty($event_categories) && !is_wp_error($event_categories)) {
        $output .= '<li><strong>Event Categories:</strong></li>';
        foreach ($event_categories as $category) {
            $output .= '<li><a href="' . get_term_link($category) . '">' . $category->name . '</a></li>';
        }
    }

    if (!empty($post_categories) && !is_wp_error($post_categories)) {
        $output .= '<li><strong>Post Categories:</strong></li>';
        foreach ($post_categories as $category) {
            $output .= '<li><a href="' . get_term_link($category) . '">' . $category->name . '</a></li>';
        }
    }
    $output .= '</ul>';

    return $output;
}
add_shortcode('event_categories', 'ed_event_categories_shortcode');

// Setup theme support for event thumbnails
function ed_setup_theme() {
    add_theme_support('post-thumbnails');
    add_image_size('event-thumbnail', 100, 100, true);
}
add_action('after_setup_theme', 'ed_setup_theme');

// Remove unnecessary meta boxes for 'event' post type
function ed_remove_event_meta_boxes() {
    $meta_boxes_to_remove = array(
        'slugdiv',
        'trackbacksdiv',
        'postcustom',
        'postexcerpt'
    );

    foreach ($meta_boxes_to_remove as $box) {
        remove_meta_box($box, 'event', 'normal');
    }
}
add_action('add_meta_boxes', 'ed_remove_event_meta_boxes', 99);

// Remove default categories from event post type
function ed_remove_default_categories() {
    unregister_taxonomy_for_object_type('category', 'event');
}
add_action('init', 'ed_remove_default_categories');

// Flush rewrite rules on activation
function ed_flush_rewrite_rules() {
    ed_register_event_post_type();
    ed_register_event_category_taxonomy();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'ed_flush_rewrite_rules');
?>
