<?php 


// Get My Account page URL
$my_account_url = wc_get_account_endpoint_url('');

// Append 'funeral-list' endpoint to My Account URL
$funeral_list_url = trailingslashit($my_account_url) . 'add-funeral-personality';

;

$current_user_id = get_current_user_id();
$post_type = 'legacy-funeral';

$user_post_count = intval(count_user_posts($current_user_id, $post_type, true));

if($user_post_count  > 0){
    $hide_item = 'd-none';
}else{
    $hide_item = '';
    
}
echo '<div class="mt-4">';
echo '<div class="d-flex justify-content-between">';
echo '<h3 class="mb-4">Funeral List</h3>';
echo '<p class="d-inline-block ml-2 mb-4"><a class="bg-primary me-2 text-white p-2 text-decoration-none ' . esc_attr($hide_item) . '" href="' . esc_url($funeral_list_url) . '">Add New</a><a class="bg-primary text-white p-2 text-decoration-none" href="?trashed-funeral-dashboard">Trashed</a></p>';
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
        echo '<ul class="list-group">';
        foreach ($funeral_posts as $post) {
            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
            echo '<span>' . esc_html($post->post_title) . '</span>';
            echo '<div class="btn-group">';
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