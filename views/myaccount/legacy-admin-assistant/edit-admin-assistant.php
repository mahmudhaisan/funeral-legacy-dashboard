<?php



// Get My Account page URL
$my_account_url = wc_get_account_endpoint_url('');
$funeral_admin_assistant_list_url = trailingslashit($my_account_url) . 'legacy-admin-assistant';


// Check if the user_id is set in the query string
if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = absint($_GET['user_id']);

    // Check if the user exists
    $user = get_user_by('ID', $user_id);

    if ($user) {
        // Process form submission
        if (isset($_POST['edit-admin-assistant-submit'])) {
            $edited_email = sanitize_email($_POST['email']);
            $edited_password = isset($_POST['password']) ? sanitize_text_field($_POST['password']) : '';

            // Prepare user data for updating
            $user_data = array(
                'ID'           => $user_id,
                'user_email'   => $edited_email,
            );

            // Check if a new password is provided and update accordingly
            if (!empty($edited_password)) {
                wp_set_password($edited_password, $user_id);
            }

            // Update the user
            $updated = wp_update_user($user_data);

            // Check if the update was successful
            if (is_wp_error($updated)) {
                echo '<p class="text-danger">Error updating user: ' . esc_html($updated->get_error_message()) . '</p>';
            } else {
                echo '<p class="text-success">User updated successfully!</p>';
            }
        }




        // Check if the user exists
        $user = get_user_by('ID', $user_id);



        // Generate a unique identifier
        $unique_identifier = 'admin_assistant';

        echo '<div class="container mt-4">';
        echo '<div class="d-flex justify-content-between">';
        echo '<h3 class="mb-4">Edit Admin Assistant</h3>';
        echo '<p class="d-inline-block ml-2 mb-4">';
        echo '<a class="bg-primary me-2 text-white p-2 text-decoration-none" href="?published-funeral-dashboard&action=add-admin-assistant">Add Admin Assistant</a>';
        echo '<a class="bg-primary me-2 text-white p-2 text-decoration-none" href="' . $funeral_admin_assistant_list_url . '">All Admin Assistants</a>';
        echo '</p>';
        echo '</div>';

        // Display the user edit form
?>
        <form method="post" action="">
            <!-- Username (read-only) -->
            <div class="mb-3">
                <label for="<?php echo $unique_identifier; ?>_username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="<?php echo $unique_identifier; ?>_username" name="username" value="<?php echo esc_attr($user->user_login); ?>" readonly>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="<?php echo $unique_identifier; ?>_email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="<?php echo $unique_identifier; ?>_email" name="email" value="<?php echo esc_attr($user->user_email); ?>" required>
            </div>

            <!-- Password (optional) -->
            <div class="mb-3">
                <label for="<?php echo $unique_identifier; ?>_password" class="form-label">New Password:</label>
                <input type="text" class="form-control" id="<?php echo $unique_identifier; ?>_password" name="password">
            </div>

            <!-- User ID (hidden input) -->
            <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id); ?>">

            <!-- Submit Button -->
            <div class="mb-3">
                <button type="submit" name="edit-admin-assistant-submit" class="btn btn-primary">Update Admin Assistant</button>
            </div>
        </form>
<?php

        echo '</div>'; // Close the container div
    } else {
        // Display error if the user doesn't exist
        echo '<p class="text-danger">User not found.</p>';
    }
}
?>