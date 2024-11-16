<?php
// Load the header template.
get_header();
global $wpdb;

/**
 * Fetch all terms in the "countries" taxonomy.
 */
$countries = get_terms(array(
    'taxonomy' => 'countries', // Specify the "countries" taxonomy.
    'hide_empty' => false, // Include terms even if no cities are assigned.
));
?>

<div>
    <?php 
    // Custom hook for actions before the countries table is displayed.
    do_action('before_countries_table'); 
    ?>
    <!-- Search bar for filtering cities -->
    <input type="text" id="city-search" placeholder="Search cities..." />
</div>

<?php if ($countries): ?>
    <div>
        <h1>Countries and Cities</h1>

        <?php foreach ($countries as $country): ?>
            <div class="country-row" data-country-name="<?php echo esc_attr($country->name); ?>">
                <h2><?php echo esc_html($country->name); ?></h2>

                <table class="cities-table">
                    <thead>
                        <tr>
                            <th>City</th>
                            <th>Temperature</th>
                        </tr>
                    </thead>
                    <tbody id="cities-table-body">
                        <?php
                        /**
                         * Query to fetch cities assigned to the current country term.
                         */
                        $cities = $wpdb->get_results($wpdb->prepare("
                            SELECT p.ID, p.post_title AS city_name,
                                   (SELECT pm.meta_value FROM {$wpdb->postmeta} pm WHERE pm.post_id = p.ID AND pm.meta_key = 'latitude') AS latitude,
                                   (SELECT pm.meta_value FROM {$wpdb->postmeta} pm WHERE pm.post_id = p.ID AND pm.meta_key = 'longitude') AS longitude
                            FROM {$wpdb->posts} p
                            LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
                            LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                            LEFT JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
                            WHERE p.post_type = 'cities' AND p.post_status = 'publish'
                            AND t.term_id = %d
                            ORDER BY p.post_title ASC
                        ", $country->term_id));

                        if ($cities): 
                            foreach ($cities as $city):
                                // Fetch weather data for the city's coordinates.
                                $latitude = $city->latitude;
                                $longitude = $city->longitude;
                                $api_key = 'cdd1641c2881a1b30da83840904b59e0'; // Replace with a valid API key.
                                $api_url = "https://api.openweathermap.org/data/3.0/onecall?lat={$latitude}&lon={$longitude}&appid={$api_key}&units=metric";

                                // Get weather data from the API.
                                $response = wp_remote_get($api_url);
                                $weather_data = wp_remote_retrieve_body($response);
                                $weather = json_decode($weather_data);

                                // Determine the current temperature.
                                if (is_wp_error($response)) {
                                    $current_temp = 'Error fetching data';
                                } elseif (isset($weather->current)) {
                                    $current_temp = $weather->current->temp;
                                } else {
                                    $current_temp = 'N/A';
                                }
                        ?>
                            <!-- Display city data in a table row -->
                            <tr class="city-row" data-city-name="<?php echo esc_attr($city->city_name); ?>">
                                <td class="city-name"><?php echo esc_html($city->city_name); ?></td>
                                <td><?php echo esc_html($current_temp); ?>°C</td>
                            </tr>
                        <?php endforeach; 
                        else: ?>
                            <!-- Display message if no cities are found -->
                            <tr>
                                <td colspan="2">No cities found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php 
// Custom hook for actions after the countries table is displayed.
do_action('after_countries_table'); 
?>

<?php 
// Load the footer template.
get_footer(); 
?>

<script>
    // Add event listener for the search bar.
    document.getElementById('city-search').addEventListener('input', function() {
        var searchTerm = this.value.toLowerCase();

        // Loop through each country row to filter cities.
        document.querySelectorAll('.country-row').forEach(function(countryRow) {
            var countryName = countryRow.getAttribute('data-country-name').toLowerCase();
            var cityRows = countryRow.querySelectorAll('.city-row');
            
            var countryVisible = false;

            cityRows.forEach(function(cityRow) {
                var cityName = cityRow.getAttribute('data-city-name').toLowerCase();
                
                // Show or hide city rows based on search term.
                if (cityName.includes(searchTerm) || countryName.includes(searchTerm)) {
                    cityRow.style.display = '';
                    highlightText(cityRow.querySelector('.city-name'), searchTerm);
                    countryVisible = true;
                } else {
                    cityRow.style.display = 'none';
                }
            });

            // Show or hide the entire country row if matches are found.
            if (countryVisible || countryName.includes(searchTerm)) {
                countryRow.style.display = '';
                highlightText(countryRow.querySelector('h2'), searchTerm);
            } else {
                countryRow.style.display = 'none';
            }
        });
    });

    // Highlight matching text in elements.
    function highlightText(element, searchTerm) {
        var text = element.textContent || element.innerText;
        var regex = new RegExp('(' + searchTerm + ')', 'gi');
        var newText = text.replace(regex, '<span class="highlight">$1</span>');
        
        element.innerHTML = newText;
    }
</script>


<style>
    .highlight {
        background-color: yellow;
        font-weight: bold;
    }

    .cities-table {
        width: 100%;
        border-collapse: collapse;
    }

    .cities-table tbody tr {
        margin-bottom: 10px;
    }

    .cities-table td, .cities-table th {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    .cities-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .country-row {
        margin-bottom: 20px;
    }

    .cities-table tbody {
        display: block;
        max-height: 400px;
        overflow-y: auto;
    }

    .cities-table thead, .cities-table tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    .country-row h2 {
        margin-bottom: 10px;
    }

</style>
