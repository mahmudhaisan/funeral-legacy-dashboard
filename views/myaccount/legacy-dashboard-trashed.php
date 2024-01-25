<?php 


$user_id = get_current_user_id();

if (isset($_GET['trashed-funeral-dashboard']) && isset($_GET['post_id']) && isset($_GET['action'])) {
    


    $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : '';
    $post_action = isset($_GET['action']) ? $_GET['action'] : '';
    $post_type = 'legacy-funeral';



    // Check if the current user has any published posts in the specified post type
    $user_post_count = count_user_posts($user_id, $post_type, true);




    // Restore trashed

    if (!empty($post_id) && $post_action == 'restore') {
        if ($user_post_count == 0) {
            // Check if the post ID exists
            $post = get_post($post_id);

            if ($post) {
                // Check if the post is in the trash
                if ($post->post_status === 'trash') {
                    // Restore the post from trash
                    $result_untrash = wp_untrash_post($post_id);

                    if ($result_untrash !== false) {
                        // Update the post status to 'publish'
                        $updated_post = array(
                            'ID' => $post_id,
                            'post_status' => 'publish',
                        );

                        $result_publish = wp_update_post($updated_post);

                        if ($result_publish !== 0) {
                            echo '<div class="alert alert-success" role="alert">Post restored and published successfully.</div>';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Error updating post status to published.</div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Error restoring post from trash.</div>';
                    }
                } else {
                    // Post is not in the trash
                    echo '<div class="alert alert-warning" role="alert">Post is not in the trash.</div>';
                }
            } else {
                // Post ID does not exist
                echo '<div class="alert alert-warning" role="alert">Post ID does not exist.</div>';
            }
        }else{
            echo '<div class="alert alert-danger" role="alert">Error: Only one post is allowed.</div>';
        }
    }

    // delete trash posts

    if (!empty($post_id) && $post_action == 'delete') {
        // Check if the post ID exists
        $post = get_post($post_id);

        if ($post) {
            // Perform deletion
            $result = wp_delete_post($post_id, true); // Set the second parameter to true for force deletion

            if ($result !== false) {
                echo '<div class="alert alert-success" role="alert">Post deleted successfully.</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error deleting post.</div>';
            }
        } else {
            // Post ID does not exist
            echo '<div class="alert alert-warning" role="alert">Post ID does not exist.</div>';
        }
    }
}







// Get My Account page URL
$my_account_url = wc_get_account_endpoint_url('');

// Append 'funeral-list' endpoint to My Account URL
$funeral_list_url = trailingslashit($my_account_url) . 'legacy-dashboard';

echo '<div class=" mt-4">';
echo '<div class="d-flex justify-content-between">';
echo '<h3 class="mb-4">Trashed List</h3>';

echo '<div>';
echo '<p class="mb-4 d-flex"><a class="bg-primary text-white p-2 me-2 text-decoration-none" href="' . esc_url($funeral_list_url) . '">See List</a><a class="bg-primary text-white p-2 text-decoration-none" href="?trashed-funeral-dashboard">Trashed List</a></p>';
// echo '<p class=" ml-2 mb-4 d-flex"><a class="bg-primary text-white p-2 text-decoration-none" href="?trashed-funeral-dashboard">Trashed List</a></p>';
echo '</div>';
echo '</div>';



$current_user_id = get_current_user_id();


// Check if 'legacy-funeral' post type exists
if (post_type_exists('legacy-funeral')) {
    // Display the list of 'legacy-funeral' posts
    $funeral_posts = get_posts(array(
        'post_type' => 'legacy-funeral',
        'numberposts' => -1,
        'post_status' => 'trash',
        'author' => $current_user_id

    ));

    if ($funeral_posts) {
        echo '<ul class="list-group" p-0>';
        foreach ($funeral_posts as $post) {
            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
            echo '<span>' . esc_html($post->post_title) . '</span>';
            echo '<div class="btn-group">';
            echo '<a href="?trashed-funeral-dashboard&post_id=' . $post->ID . '&action=restore" class="btn btn-success btn-sm me-2">Restore</a>';
            echo '<a href="?trashed-funeral-dashboard&post_id=' . $post->ID . '&action=delete" class="btn btn-danger btn-sm">Trash</a>';
            echo '</div>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p class="mb-4">Currently Trash Is Empty. You can check your restored person here. </p>'; 
      

    }
} else {
    echo '<p>The "legacy-funeral" post type does not exist.</p>';
}

echo '</div>';