<?php 

$user_id = get_current_user_id();

if (isset($_GET['published-funeral-dashboard']) && isset($_GET['post_id']) && isset($_GET['action'])) {


    $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : '';
    $post_action = isset($_GET['action']) ? $_GET['action'] : '';
    $post_type = 'legacy-funeral';

    // Check if the current user has any published posts in the specified post type
    $user_post_count = count_user_posts($user_id , $post_type, true);

   
    if (!empty($post_id) && $post_action == 'delete') {
        echo '<div class="container">';
        // Check if the post ID exists
        $post = get_post($post_id);

        if ($post) {
            // Perform deletion
            $result = wp_trash_post($post_id); // Set the second parameter to true for force deletion

            if ($result !== false) {
                echo '<div class="alert alert-success" role="alert">Post trashed successfully.</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error trashing dashboard. Please try again</div>';
            }
        } else {
            // Post ID does not exist
            echo '<div class="alert alert-warning" role="alert">Post ID does not exist.</div>';
        }
    }

    if (!empty($post_id) && $post_action == 'edit') {
    
        echo 'edit';
    }
    echo '</div>';
}




// Get My Account page URL
$my_account_url = wc_get_account_endpoint_url('');

// Append 'funeral-list' endpoint to My Account URL
$funeral_list_url = trailingslashit($my_account_url) . 'add-funeral-personality';



$current_user_id = get_current_user_id();
$post_type = 'legacy-funeral';

$user_post_count = intval(count_user_posts($current_user_id, $post_type, true));

if($user_post_count  > 0){
    $hide_item = 'd-none';
}else{
    $hide_item = '';
    
}
echo '<div class="container mt-4">';
echo '<div class="d-flex justify-content-between">';
echo '<h3 class="mb-4">Legacy Dashboard</h3>';
echo '<p class="d-inline-block ml-2 mb-4"><a class="bg-primary me-2 text-white p-2 text-decoration-none" href="?published-funeral-dashboard&action=add-admin-assistant">Add Admin Assistant</a><a class="bg-primary me-2 text-white p-2 text-decoration-none" href="?published-funeral-dashboard&action=all-admin-assistants">All Admin Assistants</a><a class="bg-primary me-2 text-white p-2 text-decoration-none ' . esc_attr($hide_item) . '" href="?published-funeral-dashboard&action=add">Add New Dashboard</a><a class="bg-primary text-white p-2 text-decoration-none" href="?trashed-funeral-dashboard">Trashed</a></p>';
echo '</div>';


$current_user_id = get_current_user_id();

// Check if 'legacy-funeral' post type exists
if (post_type_exists('legacy-funeral')) {
    // Display the list of 'legacy-funeral' posts
    $funeral_posts = get_posts(array(
        'post_type' => 'legacy-funeral',
        'numberposts' => -1,
        'author' => $current_user_id,
    ));


    if ($funeral_posts) {
        echo '<ul class="list-group p-0">';
        foreach ($funeral_posts as $post) { 

            $post_id = $post->ID;

            // Get the permalink for the post
            $post_permalink = get_permalink($post_id);


            echo '<li class="list-group-item  d-flex justify-content-between align-items-center">';
            echo '<span>' . esc_html($post->post_title) . '</span>';
            echo '<div class="btn-group">';
            echo '<a href="'.esc_url($post_permalink).'" class="btn btn-primary btn-sm me-2">View</a>';
            echo '<a href="?published-funeral-dashboard&post_id=' . $post->ID . '&action=edit" class="btn btn-success btn-sm me-2">Edit</a>';
            echo '<a href="?published-funeral-dashboard&post_id=' . $post->ID . '&action=delete" class="btn btn-danger btn-sm">Delete</a>';
            echo '</div>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p class="mb-4">Currently No Legacy Dashboard found. </p>'; 
        // echo '<p class="mb-4"><a class="bg-primary text-white p-2 text-decoration-none" href="' . esc_url($funeral_list_url) . '">Add New</a></p>';

    }
} else {
    echo '<p>The "legacy-funeral" post type does not exist.</p>';
}

echo '</div>';