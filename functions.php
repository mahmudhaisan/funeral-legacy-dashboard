<?php

function upload_image_to_media_library($image_data, $upload_dir)
{
    // Set the file name and path in the uploads directory
    $target_file = $upload_dir['path'] . '/' . basename($image_data["name"]);

    // Move the uploaded image to the uploads directory
    $upload_result = wp_upload_bits($image_data["name"], null, file_get_contents($image_data["tmp_name"]));

    // Check if the image upload was successful
    if (!$upload_result['error']) {

        // Set up the attachment data
        $attachment = array(
            'post_title'     => sanitize_file_name($image_data['name']),
            'post_mime_type' => $image_data['type'],
            'post_status'    => 'inherit',
        );

        // Insert the attachment into the media library
        $attachment_id = wp_insert_attachment($attachment, $target_file);

        return $attachment_id;
    } else {
        // Handle the case when the image upload fails
        echo "Error uploading image: " . $upload_result['error'] . "<br>";
        return ''; // Return an empty string or handle the error as needed
    }
}




// Custom function to handle form submission
function handle_admin_assistant_form() {
    if (isset($_POST['add-admin-assistant-submit'])) {
        // Retrieve form data
        $username = sanitize_user($_POST['username']);
        $password = $_POST['password'];
        $email = sanitize_email($_POST['email']);

        // Check if username and email are unique
        if (username_exists($username) || email_exists($email)) {
            echo '<div class="alert alert-danger">Username or email already exists. Please choose unique values.</div>';
            return;
        }

        // Create a new user
        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            echo '<div class="alert alert-danger">Error creating user. Please try again.</div>';
            return;
        }

        // Add the user to the "admin-assistant" role
        $user = new WP_User($user_id);
        $user->set_role('admin_assistant');

        // Store additional data in user meta
        update_user_meta($user_id, '_created_by', get_current_user_id());

        // Success message
        echo '<div class="alert alert-success">Admin Assistant added successfully!</div>';
    }
}