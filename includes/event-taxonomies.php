<?php
if (!defined('ABSPATH')) {
    exit;
}

class SimpleEventsTaxonomies {
    public static function register_taxonomies() {
        $category_labels = array(
            'name'              => _x('Event Categories', 'taxonomy general name', 'simple-events-manager'),
            'singular_name'     => _x('Event Category', 'taxonomy singular name', 'simple-events-manager'),
            'search_items'      => __('Search Event Categories', 'simple-events-manager'),
            'all_items'         => __('All Event Categories', 'simple-events-manager'),
            'parent_item'       => __('Parent Event Category', 'simple-events-manager'),
            'parent_item_colon' => __('Parent Event Category:', 'simple-events-manager'),
            'edit_item'         => __('Edit Event Category', 'simple-events-manager'),
            'update_item'       => __('Update Event Category', 'simple-events-manager'),
            'add_new_item'      => __('Add New Event Category', 'simple-events-manager'),
            'new_item_name'     => __('New Event Category Name', 'simple-events-manager'),
            'menu_name'         => __('Event Categories', 'simple-events-manager'),
        );

        $category_args = array(
            'hierarchical'      => true,
            'labels'            => $category_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'event-category'),
        );

        register_taxonomy('event_category', array('event'), $category_args);

        $tag_labels = array(
            'name'                       => _x('Event Tags', 'taxonomy general name', 'simple-events-manager'),
            'singular_name'              => _x('Event Tag', 'taxonomy singular name', 'simple-events-manager'),
            'search_items'               => __('Search Event Tags', 'simple-events-manager'),
            'popular_items'              => __('Popular Event Tags', 'simple-events-manager'),
            'all_items'                  => __('All Event Tags', 'simple-events-manager'),
            'edit_item'                  => __('Edit Event Tag', 'simple-events-manager'),
            'update_item'                => __('Update Event Tag', 'simple-events-manager'),
            'add_new_item'               => __('Add New Event Tag', 'simple-events-manager'),
            'new_item_name'              => __('New Event Tag Name', 'simple-events-manager'),
            'separate_items_with_commas' => __('Separate tags with commas', 'simple-events-manager'),
            'add_or_remove_items'        => __('Add or remove tags', 'simple-events-manager'),
            'choose_from_most_used'      => __('Choose from the most used tags', 'simple-events-manager'),
            'not_found'                  => __('No tags found.', 'simple-events-manager'),
            'menu_name'                  => __('Event Tags', 'simple-events-manager'),
        );

        $tag_args = array(
            'hierarchical'      => false,
            'labels'            => $tag_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'event-tag'),
        );

        register_taxonomy('event_tag', array('event'), $tag_args);
    }
}