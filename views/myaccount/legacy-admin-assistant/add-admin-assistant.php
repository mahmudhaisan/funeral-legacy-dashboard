<?php

handle_admin_assistant_form();


// Generate a unique identifier (you can use any method suitable for your application)
$unique_identifier = 'admin_assistant';

// Get My Account page URL
$my_account_url = wc_get_account_endpoint_url('');
$funeral_admin_assistant_list_url = trailingslashit($my_account_url) . 'legacy-admin-assistant';


$current_user_id = get_current_user_id();
$post_type = 'legacy-funeral';

$user_post_count = intval(count_user_posts($current_user_id, $post_type, true));

if ($user_post_count  > 0) {
    $hide_item = 'd-none';
} else {
    $hide_item = '';
}
echo '<div class="container mt-4">';
echo '<div class="d-flex justify-content-between">';
echo '<h3 class="mb-4">Add Admin Assistant</h3>';
echo '<p class="d-inline-block ml-2 mb-4">';
echo '<a class="bg-primary me-2 text-white p-2 text-decoration-none" href="?published-funeral-dashboard&action=add-admin-assistant">Add Admin Assistant</a>';
echo '<a class="bg-primary me-2 text-white p-2 text-decoration-none" href="' . $funeral_admin_assistant_list_url . '">All Admin Assistants</a>';
echo '</p>';
echo '</div>';

?>
<form method="post" action="">
    <!-- Username -->
    <div class="mb-3">
        <label for="<?php echo $unique_identifier; ?>_username" class="form-label">Username:</label>
        <input type="text" class="form-control" id="<?php echo $unique_identifier; ?>_username" name="username" required>
    </div>

    <!-- Email -->
    <div class="mb-3">
        <label for="<?php echo $unique_identifier; ?>_email" class="form-label">Email:</label>
        <input type="email" class="form-control" id="<?php echo $unique_identifier; ?>_email" name="email" required>
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="<?php echo $unique_identifier; ?>_password" class="form-label">Password:</label>
        <input type="text" class="form-control" id="<?php echo $unique_identifier; ?>_password" name="password" required>
    </div>



    <!-- Submit Button -->
    <div class="mb-3">
        <button type="submit" name="add-admin-assistant-submit" class="btn btn-primary">Add Admin Assistant</button>
    </div>
</form>