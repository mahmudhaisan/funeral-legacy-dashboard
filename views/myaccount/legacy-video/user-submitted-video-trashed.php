<?php




// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["move_to_pending"])) {
        $marked_videos = isset($_POST['marked_videos']) ? $_POST['marked_videos'] : array();

        $unique_recipients = array();

        foreach ($marked_videos as $post_id) {

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
                $recipient_name = get_post_meta($post_id, 'legacy_video_submitted_user_name', true);
                $recipient_email = get_post_meta($post_id, 'legacy_video_submitted_user_email', true);

                // Store unique recipient name and email combination
                $unique_recipients[$recipient_email] = $recipient_name;

               
            }
        }




        if (!empty($unique_recipients)) {

             // Add Bootstrap alert for successful deletion
             echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
             Videos moved to pending list.!
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
           </div>';

            foreach ($unique_recipients as $recipient_email => $recipient_name) {

                $email_subject = "Your legacy video submission";
                $email_body = 'Your video submission has been denied. ';
                // Send custom email
                // send_custom_email($recipient_name, $recipient_email, $email_subject, $email_body);
            }
        }else{

            // Add Bootstrap alert for successful deletion
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Failed to move to pending list. please try again
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';

        }
    }

    if (isset($_POST["delete_marked_videos"])) {
        $marked_videos = isset($_POST['marked_videos']) ? $_POST['marked_videos'] : array();

        foreach ($marked_videos as $post_id) {
            // Check if post exists before deleting
            if (get_post_status($post_id)) {
                $deleted = wp_delete_post($post_id, true); // Set second parameter to true for force delete

                if ($deleted) {
                    // Add Bootstrap alert for successful deletion
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Video with ID ' . $post_id . ' deleted successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                } else {
                    // Add Bootstrap alert for failure to delete
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Failed to delete video with ID ' . $post_id . '. Please try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                }
            } else {
                // Add Bootstrap alert if the post doesn't exist
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Video with ID ' . $post_id . ' does not exist.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            }
        }
    }


}








$args = array(
    'post_status' => 'trash',
    'numberposts' => -1, // Adjust the number of posts to retrieve
    'post_type'   => 'legacy-videos',
    'meta_query'     => array(
        array(
            'key'     => 'legacy_dashboard_id',
            'value'   => $legacy_dashboard_id ,
            'compare' => '=',
        ),
    ),
);



// var_dump(get_current_user_id());





$published_post_by_parent = get_posts($args);





// Check if there is a published post
if (!$published_post_by_parent) {

    echo 'Currently No pending videos found';
    return;
}

$post_ids = array(); // Initialize an empty array to store post IDs

foreach ($published_post_by_parent as $post) {
    $post_ids[] = $post->ID; // Add each post ID to the $post_ids array
}



$published_videos = array();

// Retrieve video links for each post ID
foreach ($post_ids as $post_id) {
    $video_link = get_field('legacy_video_submitted_yotube_link', $post_id);
    if ($video_link) {
        $published_videos[$post_id] = $video_link;
    }
}

// Display video links if available
if (!empty($published_videos)) {
    echo '<div class="container mt-4">';
    echo '<form method="post" action="">';

    echo '<div class="row">';
    foreach ($published_videos as $post_id => $video_link) {
        // Extract YouTube video ID from the URL
        $video_id = get_youtube_video_id($video_link);

        if ($video_id) {
            // Embed YouTube video
            echo '<div class="mb-3 col-md-3 legacy-video">';
            echo '<iframe width="100%" height="auto" src="https://www.youtube.com/embed/' . esc_attr($video_id) . '" frameborder="0" allowfullscreen></iframe>';
            echo '<div class="form-check mt-2">';
            echo '<input type="checkbox" class="form-check-input" id="video_' . esc_attr($post_id) . '" name="marked_videos[]" value="' . esc_attr($post_id) . '">';
            echo '<label class="form-check-label" for="video_' . esc_attr($post_id) . '">Mark</label>';
            echo '</div>';
            echo '</div>';
        }
    }
    echo '<div class="center-button text-center mt-5">';
    echo '<button type="submit" name="move_to_pending" class="btn btn-primary me-2">Move to pending list</button>';
    echo '<button type="submit" name="delete_marked_videos" class="btn btn-primary">Delete Permanently</button>';
    echo '</div>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
} else {
    echo 'No videos found.';
}
