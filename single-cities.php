<?php
// Load the header template.
get_header();

/**
 * Retrieve details for the current city post.
 */
$city_id = get_the_ID();
$city_name = get_the_title($city_id);
$latitude = get_post_meta($city_id, 'latitude', true); // Get latitude from meta data.
$longitude = get_post_meta($city_id, 'longitude', true); // Get longitude from meta data.

/**
 * Check if latitude and longitude are available.
 * If missing, display a message and stop further processing.
 */
if (empty($latitude) || empty($longitude)) {
    echo "<p>City's geographical coordinates are missing.</p>";
    get_footer(); // Load the footer and exit.
    return;
}

/**
 * Fetch weather data from the OpenWeatherMap API.
 * Replace `$api_key` with your actual API key.
 */
$api_key = 'cdd1641c2881a1b30da83840904b59e0'; // Your OpenWeatherMap API key.
$api_url = "https://api.openweathermap.org/data/3.0/onecall?lat={$latitude}&lon={$longitude}&appid={$api_key}&units=metric";

$response = wp_remote_get($api_url); // Perform an HTTP GET request to the API.

/**
 * Check for API errors.
 * Display an error message if the request fails.
 */
if (is_wp_error($response)) {
    echo "<p>Unable to retrieve weather data. Please try again later.</p>";
    get_footer();
    return;
}

$data = json_decode(wp_remote_retrieve_body($response)); // Decode the JSON response.

/**
 * Check if weather data is available.
 * Stop processing if the data is incomplete.
 */
if (empty($data->current)) {
    echo "<p>No weather data available for this city.</p>";
    get_footer();
    return;
}

// Get the current temperature (default to 'N/A' if unavailable).
$current_temp = $data->current->temp ?? 'N/A';

/**
 * Display the city name and current temperature.
 */
echo "<h1>{$city_name}</h1>";
echo "<p>Temperature: {$current_temp}°C</p>";

// Load the footer template.
get_footer();
?>
