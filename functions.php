<?php
// Prevent direct access to the file.
if (!defined('ABSPATH')) exit;

/**
 * Enqueue an RTL stylesheet if needed.
 *
 * Checks if the site is in an RTL language. If a `rtl.css` file exists in the theme,
 * this will load it. Useful for proper styling in RTL layouts.
 *
 * @param string $uri The default stylesheet URI.
 * @return string Updated stylesheet URI if RTL applies.
 */
function chld_thm_cfg_locale_css($uri) {
    if (is_rtl() && file_exists(get_template_directory() . '/rtl.css')) {
        return get_template_directory_uri() . '/rtl.css';
    }
    return $uri;
}
add_filter('locale_stylesheet_uri', 'chld_thm_cfg_locale_css');

/**
 * Create the "Cities" custom post type.
 *
 * This function registers a post type for "Cities," allowing users
 * to manage city-related content in the WordPress admin.
 */
function create_cities_post_type() {
    register_post_type('cities', [
        'labels' => [
            'name' => __('Cities'),
            'singular_name' => __('City'),
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'cities'],
        'supports' => ['title', 'editor', 'thumbnail'],
        'menu_icon' => 'dashicons-location-alt',
        'show_in_rest' => true, // Enables the block editor.
    ]);
}
add_action('init', 'create_cities_post_type');

/**
 * Add a meta box for city location data.
 *
 * Attaches a meta box to the "Cities" post type for entering
 * latitude and longitude values.
 */
function add_cities_meta_box() {
    add_meta_box('cities_meta_box', 'City Location', 'render_cities_meta_box', 'cities', 'normal', 'high');
}
add_action('add_meta_boxes', 'add_cities_meta_box');

/**
 * Render the city location meta box.
 *
 * Displays input fields for latitude and longitude in the meta box.
 * These values can be saved for each city.
 *
 * @param WP_Post $post The current post object.
 */
function render_cities_meta_box($post) {
    $latitude = get_post_meta($post->ID, 'latitude', true);
    $longitude = get_post_meta($post->ID, 'longitude', true);
    ?>
    <label for="latitude">Latitude:</label>
    <br>
    <input type="text" name="latitude" value="<?php echo esc_attr($latitude); ?>" />
    <br>
    <label for="longitude">Longitude:</label>
    <br>
    <input type="text" name="longitude" value="<?php echo esc_attr($longitude); ?>" />
    <?php
}

/**
 * Save city location meta box data.
 *
 * Handles saving the latitude and longitude fields when a city post
 * is saved or updated in the admin.
 *
 * @param int $post_id The ID of the post being saved.
 */
function save_cities_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['latitude'])) update_post_meta($post_id, 'latitude', floatval($_POST['latitude']));
    if (isset($_POST['longitude'])) update_post_meta($post_id, 'longitude', floatval($_POST['longitude']));
}
add_action('save_post', 'save_cities_meta_box');

/**
 * Register the "Countries" taxonomy.
 *
 * Adds a taxonomy for grouping cities by country. This can be
 * used for categorization or filtering in the admin.
 */
function create_countries_taxonomy() {
    register_taxonomy('countries', 'cities', [
        'label' => __('Countries'),
        'rewrite' => ['slug' => 'countries'],
        'hierarchical' => true,
        'show_in_rest' => true, // Makes it usable with the block editor.
    ]);
}
add_action('init', 'create_countries_taxonomy');

/**
 * Handle city search requests via Ajax.
 *
 * Queries the "Cities" post type based on a search term sent via an Ajax request and returns the results as table rows.
 */
function search_cities() {
    if (isset($_GET['search_term'])) {
        $search_term = sanitize_text_field($_GET['search_term']);
        $query = new WP_Query([
            'post_type' => 'cities',
            's' => $search_term,
            'posts_per_page' => -1,
        ]);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                echo '<tr>';
                echo '<td>' . get_the_title() . '</td>';
                echo '<td>' . get_post_meta(get_the_ID(), 'latitude', true) . '</td>';
                echo '<td>' . get_post_meta(get_the_ID(), 'longitude', true) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="3">No cities found.</td></tr>';
        }
        wp_die();
    }
}
add_action('wp_ajax_search_cities', 'search_cities');
add_action('wp_ajax_nopriv_search_cities', 'search_cities');
