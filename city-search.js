jQuery(document).ready(function($) {
    // On keyup in the search field, make an Ajax request
    $('#city-search').on('keyup', function() {
        var searchTerm = $(this).val();

        // Send the search request to the server
        $.ajax({
            url: citySearch.ajax_url, // This is the localized AJAX URL
            method: 'GET',
            data: {
                action: 'search_cities',
                search_term: searchTerm
            },
            beforeSend: function() {
                // Optional: Show loading indicator
                $('#cities-table-body').html('<tr><td colspan="3">Loading...</td></tr>');
            },
            success: function(response) {
                $('#cities-table-body').html(response);
            },
            error: function() {
                $('#cities-table-body').html('<tr><td colspan="3">Error fetching data.</td></tr>');
            }
        });
    });
});
