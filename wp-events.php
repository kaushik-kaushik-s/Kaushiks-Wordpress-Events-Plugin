<?php
/*
Plugin Name: Events Directory
Description: Manages events with custom post type and date metadata
Version: 1.1
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
        'supports'           => array('title', 'editor', 'thumbnail'),
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'events'),
    );

    register_post_type('event', $args);
}
add_action('init', 'ed_register_event_post_type');

// Setup theme support for event thumbnails
function ed_setup_theme() {
    add_theme_support('post-thumbnails');
    add_image_size('event-thumbnail', 100, 100, true); // Crop to 100x100 pixels
}
add_action('after_setup_theme', 'ed_setup_theme');

// Remove unnecessary meta boxes for 'event' post type
function ed_remove_event_meta_boxes() {
    $meta_boxes_to_remove = array(
        'commentstatusdiv',  // Discussion settings
        'slugdiv',           // Permalink slug
        'authordiv',         // Author meta box
        'trackbacksdiv',     // Trackbacks
        'postcustom',        // Custom fields
        'postexcerpt'        // Excerpt meta box
    );

    foreach ($meta_boxes_to_remove as $box) {
        remove_meta_box($box, 'event', 'normal');
    }
}
add_action('add_meta_boxes', 'ed_remove_event_meta_boxes', 99);
