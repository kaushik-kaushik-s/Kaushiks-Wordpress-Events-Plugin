<?php
if (!defined('ABSPATH')) {
    exit;
}

class SimpleEventsMetaBoxes {
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_event_meta_boxes'));
        add_action('save_post', array($this, 'save_event_meta'));
    }

    public function add_event_meta_boxes() {
        add_meta_box(
            'event_details_box',
            __('Event Details', 'simple-events-manager'),
            array($this, 'render_event_details_meta_box'),
            'event',
            'normal',
            'high'
        );
    }

    public function render_event_details_meta_box($post) {
        wp_nonce_field('event_details_nonce', 'event_details_nonce');

        $event_date = get_post_meta($post->ID, '_event_date', true);
        $event_time = get_post_meta($post->ID, '_event_time', true);
        $event_location = get_post_meta($post->ID, '_event_location', true);
        ?>
        <div class="event-meta-wrapper">
            <div class="event-meta-field">
                <label for="event_date"><?php _e('Event Date', 'simple-events-manager'); ?></label>
                <input type="text" id="event_date" name="event_date" 
                       value="<?php echo esc_attr($event_date); ?>" 
                       class="datepicker" />
            </div>
            <div class="event-meta-field">
                <label for="event_time"><?php _e('Event Time', 'simple-events-manager'); ?></label>
                <input type="time" id="event_time" name="event_time" 
                       value="<?php echo esc_attr($event_time); ?>" />
            </div>
            <div class="event-meta-field">
                <label for="event_location"><?php _e('Event Location', 'simple-events-manager'); ?></label>
                <input type="text" id="event_location" name="event_location" 
                       value="<?php echo esc_attr($event_location); ?>" />
            </div>
        </div>
        <script>
        jQuery(document).ready(function($) {
            $('#event_date').datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
        </script>
        <?php
    }

    public function save_event_meta($post_id) {
        if (!isset($_POST['event_details_nonce']) || 
            !wp_verify_nonce($_POST['event_details_nonce'], 'event_details_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['event_date'])) {
            update_post_meta(
                $post_id, 
                '_event_date', 
                sanitize_text_field($_POST['event_date'])
            );
        }

        if (isset($_POST['event_time'])) {
            update_post_meta(
                $post_id, 
                '_event_time', 
                sanitize_text_field($_POST['event_time'])
            );
        }

        if (isset($_POST['event_location'])) {
            update_post_meta(
                $post_id, 
                '_event_location', 
                sanitize_text_field($_POST['event_location'])
            );
        }
    }
}

new SimpleEventsMetaBoxes();