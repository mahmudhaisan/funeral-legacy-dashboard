<?php

$user_id = get_current_user_id();


if (isset($_GET['published-funeral-dashboard']) && isset($_GET['post_id']) && isset($_GET['action'])) {
    $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : '';
    $post_action = isset($_GET['action']) ? $_GET['action'] : '';
    $post_type = 'legacy-funeral';
    $user_id = null; // Set user ID if needed

    echo handle_funeral_dashboard_action($post_id, $post_action, $post_type, $user_id);
}


// Retrieve the success message from the URL parameter
if (isset($_GET['success_msg'])) {
    $success_message = urldecode($_GET['success_msg']);
    echo '<div class="alert alert-success" id="successMessage">' . esc_html($success_message) . '</div>';
    // Add JavaScript to remove query parameters after they've been displayed
    echo '<script>
            // Wait for the DOM to be fully loaded
            document.addEventListener("DOMContentLoaded", function() {
                // Remove query parameters from the URL without reloading the page
                window.history.replaceState({}, document.title, window.location.pathname);
            });
          </script>';
}




// Get My Account page URL
$my_account_url = wc_get_account_endpoint_url('');

// Append 'funeral-list' endpoint to My Account URL
$funeral_list_url = trailingslashit($my_account_url) . 'add-funeral-personality';


$user = wp_get_current_user();



print_r($user->roles);


if(in_array('local_admin',$user->roles )){

    $parent_user_id = get_current_user_id();
    
    
}elseif(in_array('admin_assistant',$user->roles )){
    
    
    $current_user_id = get_current_user_id();
    $parent_user_id = get_user_meta($current_user_id, '_created_by', true);


}else{
    $parent_user_id = get_current_user_id();
}

$post_type = 'legacy-funeral';

$user_post_count = intval(count_user_posts($parent_user_id, $post_type, true));

if ($user_post_count  > 0) {
    $hide_item = 'd-none';
} else {
    $hide_item = '';
}
echo '<div class="container mt-4">';
echo '<div class="d-flex justify-content-between">';
echo '<h3 class="mb-4">Legacy Dashboard</h3>';
echo "<p class='d-inline-block ml-2 mb-4'>\n";

if (in_array('local_admin', $user->roles)) {
    echo "<a class='bg-primary me-2 text-white p-2 text-decoration-none " . esc_attr($hide_item) . "' href='?published-funeral-dashboard&action=add'>Add New Dashboard</a>\n";
}

echo "<a class='bg-primary text-white p-2 text-decoration-none' href='?trashed-funeral-dashboard'>Trashed</a>\n";
echo "</p>\n";
echo '</div>';


$current_user_id = get_current_user_id();

// Check if 'legacy-funeral' post type exists
if (post_type_exists('legacy-funeral')) {
    // Display the list of 'legacy-funeral' posts
    $funeral_posts = get_posts(array(
        'post_type' => 'legacy-funeral',
        'numberposts' => -1,
        'author' => $parent_user_id,
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
            echo '<a href="' . esc_url($post_permalink) . '" class="btn btn-primary btn-sm me-2" target="blank">View</a>';
            echo '<a href="?published-funeral-dashboard&post_id=' . $post->ID . '&action=edit" class="btn btn-success btn-sm me-2">Edit</a>';
          
            if (in_array('local_admin', $user->roles)) {
                echo '<a href="?published-funeral-dashboard&post_id=' . $post->ID . '&action=trash" class="btn btn-danger btn-sm">Trash</a>';
            }

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
