<?php
/**
 * Cities_Widget Class
 * This widget allows users to display the name of a selected city from the "Cities" custom post type.
 */
class Cities_Widget extends WP_Widget {

    /**
     * Constructor: Sets up the widget.
     */
    function __construct() {
        // Initialize the widget with an ID and name.
        parent::__construct('cities_widget', __('City Widget'));
    }

    /**
     * Outputs the widget content on the frontend.
     *
     * @param array $args Display arguments including before/after widget wrappers.
     * @param array $instance Saved settings from the widget form.
     */
    public function widget($args, $instance) {
        // Get the selected city ID and retrieve the city's name.
        $city_id = $instance['city_id'];
        $city_name = get_the_title($city_id);

        // Output the widget content.
        echo $args['before_widget'];
        echo "<h3>{$city_name}</h3>"; // Show the city name as a heading.
        echo $args['after_widget'];
    }

    /**
     * Displays the widget settings form in the admin area.
     *
     * @param array $instance Current settings for this widget.
     */
    public function form($instance) {
        // Fetch all city posts to populate the dropdown.
        $cities = get_posts(['post_type' => 'cities']);
        $city_id = $instance['city_id'] ?? ''; // Get saved city ID or set a default.
        ?>
        <label for="<?php echo $this->get_field_id('city_id'); ?>">Select City:</label>
        <select id="<?php echo $this->get_field_id('city_id'); ?>" name="<?php echo $this->get_field_name('city_id'); ?>">
            <?php foreach ($cities as $city): ?>
                <option value="<?php echo $city->ID; ?>" <?php selected($city_id, $city->ID); ?>>
                    <?php echo $city->post_title; ?> <!-- Display city title in dropdown. -->
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    /**
     * Saves widget settings when updated in the admin area.
     *
     * @param array $new_instance New settings entered by the user.
     * @param array $old_instance Previously saved settings.
     * @return array Updated settings to be saved.
     */
    public function update($new_instance, $old_instance) {
        // Sanitize and save the city ID.
        return ['city_id' => (int) $new_instance['city_id']];
    }
}

/**
 * Registers the Cities_Widget.
 *
 * Hooks into 'widgets_init' to make the widget available in the admin area.
 */
function register_cities_widget() {
    register_widget('Cities_Widget');
}
add_action('widgets_init', 'register_cities_widget');
