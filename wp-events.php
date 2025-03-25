<?php
/*
Plugin Name: Kaushik Sannidhi's Events Directory
Description: A comprehensive events management plugin for WordPress
Version: 1.0
Author: Kaushik Sannidhi
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/event-post-type.php';
require_once plugin_dir_path(__FILE__) . 'includes/event-meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/event-taxonomies.php';

class SimpleEventsManager {
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function init() {
=        SimpleEventsPostType::register_post_type();
        SimpleEventsTaxonomies::register_taxonomies();
    }

    public function enqueue_admin_scripts() {
=        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    }

    public static function activate() {
        SimpleEventsPostType::register_post_type();
        SimpleEventsTaxonomies::register_taxonomies();
        flush_rewrite_rules();
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }
}

register_activation_hook(__FILE__, array('SimpleEventsManager', 'activate'));
register_deactivation_hook(__FILE__, array('SimpleEventsManager', 'deactivate'));

$simple_events_manager = new SimpleEventsManager();