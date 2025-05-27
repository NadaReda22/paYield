// When the DOM is fully loaded, run the following code
$(document).ready(function() {

    // Used to debounce the search input (to delay requests while typing)
    let searchTimeout = null;

    // Cache selectors for the desktop and mobile search result containers
    const searchResultsContainer = $('#searchProducts');
    const mobileSearchResultsContainer = $('#mobilesearchProducts');

    // Function to show search results container based on which input is active
    window.search_result_show = function() {
        const container = $(document.activeElement).attr('id') === 'mobilesearch' 
            ? mobileSearchResultsContainer 
            : searchResultsContainer;

        // Only show container if it has content
        if (container.html().trim() !== '') {
            container.addClass('search-results-visible'); // Display result area
        }
    };

    // Function to hide search results (with a slight delay to allow clicking)
    window.search_result_hide = function() {
        setTimeout(function() {
            $('.search-results-visible').removeClass('search-results-visible'); // Hide result area
        }, 200); // Delay to allow events like blur/click to register
    };

    // Core function to send the AJAX request and update results
    function performSearch(searchInput, resultsContainer) {
        let text = searchInput.val(); // Get search input value
        let category_id = $('#category_id').val() || ''; // Get category (if any)

        // Proceed only if input has text
        if (text.length > 0) {
            $.ajax({
                data: {
                    search: text,
                    category_id: category_id,
                    _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                },
                url: '/product/search-product', // Backend endpoint
                method: 'POST',
                beforeSend: function(xhr) {
                    // Set CSRF header manually (Laravel compatibility)
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                    // Optional loading message while waiting
                    resultsContainer.html('<div class="searching">Searching...</div>');
                },
                success: function(result) {
                    // Replace container content with response
                    resultsContainer.html(result);

                    // Show container if result isn't empty
                    if (result.trim() !== '') {
                        resultsContainer.addClass('search-results-visible');
                    }

                    // Call show function (ensures container is shown properly)
                    search_result_show();
                },
                error: function(xhr, status, error) {
                    // Log error details for debugging
                    console.error("Search error:", error);
                    console.log("Response text:", xhr.responseText);

                    // Show error message in result container
                    resultsContainer.html('<div class="error">Search failed. Please try again.</div>');
                }
            });
        } else {
            // If input is empty, clear results and hide container
            resultsContainer.html('').removeClass('search-results-visible');
        }
    }

    // Attach event for desktop search input
    $('#search').on('keyup', function() {
        const $this = $(this);

        // Clear any previous timeout to debounce
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        // Wait 300ms after last keypress before making AJAX call
        searchTimeout = setTimeout(function() {
            performSearch($this, searchResultsContainer);
        }, 300);
    });

    // Attach event for mobile search input
    $('#mobilesearch').on('keyup', function() {
        const $this = $(this);

        // Clear previous timeout (debouncing)
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        // Debounced AJAX call for mobile input
        searchTimeout = setTimeout(function() {
            performSearch($this, mobileSearchResultsContainer);
        }, 300);
    });

    // (Optional) Prevent default form submit if search is wrapped in a form
    // $('form[action="/product/search"]').on('submit', function(e) {
    //     e.preventDefault(); // Prevent full page reload
    //     performSearch($('#search'), searchResultsContainer);
    // });

    // Hide search results when clicking outside search input or result containers
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#search, #searchProducts, #mobilesearch, #mobilesearchProducts').length) {
            $('.search-results-visible').removeClass('search-results-visible');
        }
    });
});
