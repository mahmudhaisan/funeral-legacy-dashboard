<?php

$user_id = get_current_user_id();

if (isset($_GET['trashed-funeral-dashboard']) && isset($_GET['post_id']) && isset($_GET['action'])) {


    $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : '';
    $post_action = isset($_GET['action']) ? $_GET['action'] : '';
    $post_type = 'legacy-funeral';

    // Check if the current user has any published posts in the specified post type
    $user_post_count = count_user_posts(get_current_user_id(), $post_type);

    // echo $user_post_count;

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
    } else {
        // User has published posts, display an error message
        echo '<div class="alert alert-danger" role="alert">Error: You already have published posts in this post type.</div>';
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




if (isset($_GET['published-funeral-dashboard']) && isset($_GET['post_id']) && isset($_GET['action'])) {



    $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : '';
    $post_action = isset($_GET['action']) ? $_GET['action'] : '';
    $post_type = 'legacy-funeral';

    // Check if the current user has any published posts in the specified post type
    $user_post_count = count_user_posts(get_current_user_id(), $post_type);

    // echo $user_post_count;

    $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : '';
    $post_action = isset($_GET['action']) ? $_GET['action'] : '';
    $post_type = 'legacy-funeral';



    // Check if the current user has any published posts in the specified post type
    $user_post_count = count_user_posts($user_id , $post_type, true);




    // delete trash posts

    if (!empty($post_id) && $post_action == 'delete') {
        // Check if the post ID exists
        $post = get_post($post_id);

        if ($post) {
            // Perform deletion
            $result = wp_trash_post($post_id); // Set the second parameter to true for force deletion

            if ($result !== false) {
                echo '<div class="alert alert-success" role="alert">Post trashed successfully.</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error trahing post.</div>';
            }
        } else {
            // Post ID does not exist
            echo '<div class="alert alert-warning" role="alert">Post ID does not exist.</div>';
        }
    }

    if (!empty($post_id) && $post_action == 'edit') {
    
        echo 'edit';
    }
}


