
<?php




// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['wall-pending'])) {
   
// Handle Form Submission
if ( isset($_POST["move_to_pending"])) {
    $marked_walls = isset($_POST['marked_walls']) ? $_POST['marked_walls'] : array();


    $unique_recipients = array();

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

            // Retrieve recipient name and email
            $recipient_name = get_post_meta($post_id, 'legacy_wall_submitted_user_name', true);
            $recipient_email = get_post_meta($post_id, 'legacy_wall_submitted_user_email', true);

            // Store unique recipient name and email combination
            $unique_recipients[$recipient_email] = $recipient_name;
        } 
    }


    if (!empty($unique_recipients)) {

        // Add Bootstrap alert for successful publication
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Walls moved to pending list Successfully !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';

        foreach ($unique_recipients as $recipient_email => $recipient_name) {

            $email_subject = "Your legacy wall submission";
            $email_body = 'Your wall submission has been denied.';
            // Send custom email
            // send_custom_email($recipient_name, $recipient_email, $email_subject, $email_body);
        }
    } else {
        // Add Bootstrap alert if the post is already published
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Walls are already published.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
}



    if (isset($_POST["delete_marked_walls"])) {
        $marked_walls = isset($_POST['marked_walls']) ? $_POST['marked_walls'] : array();



        foreach ($marked_walls as $post_id) {
            // Check if post exists before deleting
            if (get_post_status($post_id)) {
                $deleted = wp_delete_post($post_id, true); // Set second parameter to true for force delete

                // Add Bootstrap alert for successful deletion
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                 suuceesfully deleted selected walls!
                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>';
            } else {
                // Add Bootstrap alert if the post doesn't exist
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Legacy wall with ID ' . $post_id . ' does not exist.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            }
        }
    }
}






$args = array(
    'post_status' => 'trash',
    'numberposts' => -1, // Adjust the number of posts to retrieve
    'post_type'   => 'legacy-wall',
    'meta_query'     => array(
        array(
            'key'     => 'legacy_dashboard_id',
            'value'   => $legacy_dashboard_id ,
            'compare' => '=',
        ),
    ),
);

$published_post_by_parent = get_posts($args);

// Check if there is a published post
if (!$published_post_by_parent) {
    echo 'Currently No trashed walls Found';
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
    echo '<div class="">';
    foreach ($post_ids as $post_id) {
        // Get post object
        $post = get_post($post_id);
        if ($post) {
            echo '<table class="table border-1">';
            echo '<tbody>';
            echo '<tr>';
            echo '<td style="width: 10px;">';
            echo '<input type="checkbox" class="form-check-input" id="post_' . esc_attr($post_id) . '" name="marked_walls[]" value="' . esc_attr($post_id) . '">';
            echo '</td>';
            echo '<td>';
            echo '<p class="h5 mb-0">' . esc_html($post->post_title) . '</p>';
            echo '</td>';
            echo '<td class="text-end">';
            echo '<a href="" class="btn btn-primary btn-sm me-2"  data-bs-toggle="modal" data-bs-target="#postModal_' . $post_id . '">View</a>';
            echo '</td>';
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';

            
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

    echo '<input type="hidden" name="wall-pending" >';

    echo '<button type="submit" name="move_to_pending" class="me-2">Move to Pending</button>';
    echo '<button type="submit" name="delete_marked_walls" class="">Delete Permanently</button>';
    echo '</div>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
} else {
    echo 'No walls found.';
}
