# Kaushik Sannidhi's Events Directory

**Author**: Kaushik Sannidhi  

## Description
Kaushik Sannidhi's Events Directory is a comprehensive WordPress plugin designed for managing and showcasing events effortlessly. It provides custom post types, taxonomies, and meta boxes for detailed event information, and features a shortcode for displaying events seamlessly on your site.

## Features
- **Custom Post Type**: Create and manage events with ease.
- **Event Details**: Add event-specific metadata like date, time, and location.
- **Custom Taxonomies**: Organize events using categories and tags.
- **Shortcode Integration**: Display events anywhere on your site using `[simple_events]`.
- **Admin-Friendly Interface**: Built-in admin scripts for improved usability.

## Installation
1. Download and upload the plugin folder to your WordPress `wp-content/plugins` directory.
2. Activate the plugin through the WordPress admin dashboard (`Plugins > Installed Plugins`).
3. Configure settings and start creating events from the WordPress admin panel.

## Shortcode Usage
Embed events on any page or post using the `[simple_events]` shortcode. Attributes include:
- `category`: Filter by event categories (e.g., `category="workshops, conferences"`).
- `tag`: Filter by event tags (e.g., `tag="networking"`).
- `limit`: Number of events to display (default: 10).
- `order`: Display order (`ASC` for ascending, `DESC` for descending; default: `DESC`).
- `orderby`: Sort by (`date`, `title`, etc.; default: `date`).

### Example:
```php
[simple_events category="workshops" tag="networking" limit="5" order="ASC" orderby="title"]
