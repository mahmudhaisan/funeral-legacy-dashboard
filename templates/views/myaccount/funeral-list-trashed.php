<?php 

// Get My Account page URL
$my_account_url = wc_get_account_endpoint_url('');

// Append 'funeral-list' endpoint to My Account URL
$funeral_list_url = trailingslashit($my_account_url) . 'funeral-list';

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
            echo '<a href="?trashed-funeral-dashboard&post_id=' . $post->ID . '&action=delete" class="btn btn-danger btn-sm">Delete</a>';
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