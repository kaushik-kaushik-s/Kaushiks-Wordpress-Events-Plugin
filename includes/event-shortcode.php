<?php
if (!defined('ABSPATH')) {
    exit;
}

class SimpleEventsShortcode {
    public function __construct() {
        add_shortcode('simple_events', array($this, 'render_events_shortcode'));
    }

    public function render_events_shortcode($atts) {
        $atts = shortcode_atts(array(
            'category' => '',
            'tag' => '',
            'limit' => 10,
            'order' => 'DESC',
            'orderby' => 'date'
        ), $atts, 'simple_events');

        $args = array(
            'post_type' => 'event',
            'posts_per_page' => intval($atts['limit']),
            'order' => sanitize_text_field($atts['order']),
            'orderby' => sanitize_text_field($atts['orderby'])
        );

        if (!empty($atts['category'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'event_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category'])
            );
        }

        if (!empty($atts['tag'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'event_tag',
                'field' => 'slug',
                'terms' => explode(',', $atts['tag'])
            );
        }

        $events_query = new WP_Query($args);

        ob_start();

        if ($events_query->have_posts()) {
            echo '<div class="simple-events-list">';
            
            while ($events_query->have_posts()) {
                $events_query->the_post();
                
                $event_date = get_post_meta(get_the_ID(), '_event_date', true);
                $event_time = get_post_meta(get_the_ID(), '_event_time', true);
                $event_location = get_post_meta(get_the_ID(), '_event_location', true);
                
                ?>
                <div class="event-item">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <div class="event-meta">
                        <?php if ($event_date): ?>
                            <span class="event-date"><?php echo esc_html($event_date); ?></span>
                        <?php endif; ?>
                        <?php if ($event_time): ?>
                            <span class="event-time"><?php echo esc_html($event_time); ?></span>
                        <?php endif; ?>
                        <?php if ($event_location): ?>
                            <span class="event-location"><?php echo esc_html($event_location); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="event-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                </div>
                <?php
            }
            
            echo '</div>';
            
            wp_reset_postdata();
        } else {
            echo '<p>No events found.</p>';
        }

        return ob_get_clean();
    }
}

new SimpleEventsShortcode();