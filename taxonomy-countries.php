<?php
// Load the header template.
get_header();

/**
 * Retrieve the current taxonomy term being queried.
 */
$current_term = get_queried_object();

/**
 * Fetch all "cities" posts assigned to the current taxonomy term.
 */
$cities = get_posts(array(
    'post_type' => 'cities', // Fetch posts of type "cities".
    'tax_query' => array(
        array(
            'taxonomy' => 'countries', // Match the "countries" taxonomy.
            'field'    => 'id', // Use the term ID for filtering.
            'terms'    => $current_term->term_id, // Filter by the current term ID.
            'operator' => 'IN', // Include posts matching this term.
        ),
    ),
));
?>

<!-- Display the current term's name as the heading -->
<h1>Cities in <?php echo esc_html($current_term->name); ?></h1>

<?php if ($cities): ?>
    <!-- List the cities if any are found -->
    <ul>
        <?php foreach ($cities as $city): ?>
            <li>
                <!-- Link each city to its individual post -->
                <a href="<?php echo get_permalink($city->ID); ?>">
                    <?php echo esc_html($city->post_title); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <!-- Display a message if no cities are found -->
    <p>No cities found in <?php echo esc_html($current_term->name); ?>.</p>
<?php endif; ?>

<?php
// Load the footer template.
get_footer();
?>
