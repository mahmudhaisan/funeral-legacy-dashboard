<?php



// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["move_to_pending"])) {
        $marked_walls = isset($_POST['marked_walls']) ? $_POST['marked_walls'] : array();


        
        foreach ($marked_walls as $post_id) {
            // Get the current post status
            $current_status = get_post_status($post_id);

            // If the current status is not 'pending', update the post status to 'pending'
            if ($current_status !== 'pending') {
                $post_data = array(
                    'ID' => $post_id,
                    'post_status' => 'pending'
                );
                wp_update_post($post_data);


                // Add Bootstrap alert for successful deletion
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Legacy wall moved to pending list.!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            } else {
                // Add Bootstrap alert for successful deletion
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              Failed to move. please try again
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }
        }
    
}





$args = array(
    'post_status' => 'publish',
    'numberposts' => -1, // Adjust the number of posts to retrieve
    'post_type'   => 'legacy-wall',
    'author'      => $parent_local_admin_id,
);

$published_post_by_parent = get_posts($args);


// print_r($published_post_by_parent);

// Check if there is a published post
if (!$published_post_by_parent) {
    echo 'Currently No Published walls Found';
    return;
}

$post_ids = array(); // Initialize an empty array to store post IDs

foreach ($published_post_by_parent as $post) {
    $post_ids[] = $post->ID; // Add each post ID to the $post_ids array
}

// Display video links if available
if (!empty($post_ids)) {
    echo '<div class="container mt-4">';
    echo '<form method="post" action="">';

    echo '<div class="row">';
    foreach ($post_ids as $post_id) {
        // Get post object
        $post = get_post($post_id);

        if ($post) {
            // Embed YouTube video
            echo '<div class="mb-3 col-md-3 legacy-wall">';
            // Display post title
            echo '<h3>' . esc_html($post->post_title) . '</h3>';
            // Trim post content to 100 characters
            // Strip HTML tags and trim post content to 100 characters
            $content_without_tags = strip_tags($post->post_content);
            $excerpt = substr($content_without_tags, 0, 50);
            // Add "..." if the excerpt exceeds 100 characters
            if (strlen($content_without_tags) > 50) {
                $excerpt .= '...';
            }
            echo '<p>' . esc_html($excerpt) . '</p>';
            // View More button to open popup
            echo '<button type="button" class="" data-bs-toggle="modal" data-bs-target="#postModal_' . $post_id . '">View More</button>';

            // Checkbox for marking post
            echo '<div class="form-check">';
            echo '<input type="checkbox" class="form-check-input" id="post_' . esc_attr($post_id) . '" name="marked_walls[]" value="' . esc_attr($post_id) . '">';
            echo '<label class="form-check-label" for="post_' . esc_attr($post_id) . '">Mark</label>';
            echo '</div>';


            // Popup modal to show full post content
            echo '<div class="modal fade" id="postModal_' . $post_id . '" tabindex="-1" aria-labelledby="postModalLabel_' . $post_id . '" aria-hidden="true">';
            echo '<div class="modal-dialog modal-dialog-centered modal-lg">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title" id="postModalLabel_' . $post_id . '">' . esc_html($post->post_title) . '</h5>';
            echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo wp_kses_post($post->post_content); // Display full post content
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>'; // End modal
            echo '</div>'; // End legacy-wall
        }
    }
    echo '<div class="center-button text-center mt-5">';
    echo '<button type="submit" name="move_to_pending" class="btn btn-primary">Move To Pending List</button>';
    echo '</div>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
} else {
    echo 'No walls found.';
}
