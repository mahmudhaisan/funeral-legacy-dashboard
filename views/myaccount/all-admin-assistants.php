<?php

echo '<div class="mt-4">';
echo '<div class="d-flex justify-content-between">';
echo '<h3 class="mb-4">Admin Assistants</h3>';

echo '<div>';
echo '<p class="mb-4 d-flex"><a class="bg-primary text-white p-2 me-2 text-decoration-none" href="?published-funeral-dashboard&action=add-admin-assistant">Add New Admin Assistant</a></p>';
echo '</div>';
echo '</div>';

$current_user_id = get_current_user_id();



// Check if 'delete' action is triggered and user_id is provided
if (isset($_GET['user_id']) && isset($_GET['delete']) && $_GET['delete'] == 'true') {
    $user_id_to_delete = sanitize_text_field($_GET['user_id']);
    $user_info              =   get_userdata($user_id_to_delete);
    $this_user_roles        =   $user_info->roles;

    //For wp_delete_user() function
    require_once(ABSPATH . 'wp-admin/includes/user.php');

    if (in_array("administrator", $this_user_roles)) {
        echo "This user is admin, cannot be deleted";
    } else {

        // Delete the user
        if (wp_delete_user($user_id_to_delete, $current_user_id)) {
            echo '<div class="alert alert-success">Admin Assistant deleted successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error deleting Admin Assistant. Please try again.</div>';
        }
    }
}


// Retrieve users with the 'admin-assistant' role and matching '_created_by' meta
$args = array(
    'role'       => 'admin_assistant',
    'meta_query' => array(
        array(
            'key'   => '_created_by',
            'value' => $current_user_id,
        ),
    ),
);

$admin_assistants = get_users($args);


if ($admin_assistants) {
    echo '<ul class="list-group">';
    foreach ($admin_assistants as $admin_assistant) {
        echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
        echo '<span>' . esc_html($admin_assistant->display_name) . '</span>';
        echo '<div class="btn-group">';
        echo '<a href="?published-funeral-dashboard&user_id=' . $admin_assistant->ID . '&action=all-admin-assistants&delete=true" class="btn btn-danger btn-sm">Delete Assistant</a>';
        echo '</div>';
        echo '</li>';
    }
    echo '</ul>';
} else {
    echo '<p class="mb-4">No Admin Assistants found.</p>';
}

echo '</div>';
