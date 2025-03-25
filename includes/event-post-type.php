<?php
if (!defined('ABSPATH')) {
    exit;
}

class SimpleEventsPostType {
    public static function register_post_type() {
        $labels = array(
            'name'               => _x('Events', 'post type general name', 'simple-events-manager'),
            'singular_name'      => _x('Event', 'post type singular name', 'simple-events-manager'),
            'menu_name'          => _x('Events', 'admin menu', 'simple-events-manager'),
            'name_admin_bar'     => _x('Event', 'add new on admin bar', 'simple-events-manager'),
            'add_new'            => _x('Add New', 'event', 'simple-events-manager'),
            'add_new_item'       => __('Add New Event', 'simple-events-manager'),
            'new_item'           => __('New Event', 'simple-events-manager'),
            'edit_item'          => __('Edit Event', 'simple-events-manager'),
            'view_item'          => __('View Event', 'simple-events-manager'),
            'all_items'          => __('All Events', 'simple-events-manager'),
            'search_items'       => __('Search Events', 'simple-events-manager'),
            'parent_item_colon'  => __('Parent Events:', 'simple-events-manager'),
            'not_found'          => __('No events found.', 'simple-events-manager'),
            'not_found_in_trash' => __('No events found in Trash.', 'simple-events-manager')
        );     

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'event'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-calendar-alt',
            'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
        );
          
        register_post_type('event', $args);
    }
}