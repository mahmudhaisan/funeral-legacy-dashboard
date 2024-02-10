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
function handle_admin_assistant_form()
{
    if (isset($_POST['add-admin-assistant-submit'])) {
        // Retrieve form data
        $username = sanitize_user($_POST['username']);
        $password = $_POST['password'];
        $email = sanitize_email($_POST['email']);

        // Check if username and email are unique
        if (username_exists($username) || email_exists($email)) {
            echo '<div class="container">';
            echo '<div class="alert alert-danger">Username or email already exists. Please choose unique values.</div>';
            echo '</div>';
            return;
        }

        // Create a new user
        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            echo '<div class="container">';
            echo '<div class="alert alert-danger">Error creating user. Please try again.</div>';
            echo '</div>';
            return;
        }

        // Add the user to the "admin-assistant" role
        $user = new WP_User($user_id);
        $user->set_role('admin_assistant');

        // Store additional data in user meta
        update_user_meta($user_id, '_created_by', get_current_user_id());

        // Success message
        echo '<div class="container">';
        echo '<div class="alert alert-success">Admin Assistant added successfully!</div>';
        echo '</div>';
    }
}



// Function to handle image upload
function upload_image($field_name)
{
    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    $uploaded_file = $_FILES[$field_name];
    $upload_overrides = array('test_form' => false);
    $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

    // Check for errors during file upload
    if ($movefile && empty($movefile['error'])) {
        // Prepare the attachment data
        $attachment = array(
            'post_mime_type' => $movefile['type'],
            'post_title'     => sanitize_file_name($movefile['file']),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        // Insert the attachment into the media library
        $attachment_id = wp_insert_attachment($attachment, $movefile['file']);



        return $attachment_id;
    } else {
        // Handle the error if file upload fails
        echo 'Error uploading file: ' . $movefile['error'];
        return false;
    }
}


// Function to handle multiple image uploads and get attachment IDs
function upload_images($field_name)
{

    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    $uploaded_files = $_FILES[$field_name];
    $gallery_attachment_ids = array();

    foreach ($uploaded_files['tmp_name'] as $index => $tmp_name) {
        // Check if the file is really uploaded
        if (is_uploaded_file($tmp_name)) {
            $file = array(
                'name'     => $uploaded_files['name'][$index],
                'type'     => $uploaded_files['type'][$index],
                'tmp_name' => $tmp_name,
                'error'    => $uploaded_files['error'][$index],
                'size'     => $uploaded_files['size'][$index],
            );

            $upload_overrides = array('test_form' => false);
            $movefile = wp_handle_upload($file, $upload_overrides);

            if ($movefile && empty($movefile['error'])) {
                // Prepare the attachment data
                $attachment = array(
                    'post_mime_type' => $movefile['type'],
                    'post_title'     => sanitize_file_name($movefile['file']),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                // Insert the attachment into the media library
                $attachment_id = wp_insert_attachment($attachment, $movefile['file']);

                $gallery_attachment_ids[] = $attachment_id;
            } else {
                // Handle the error if file upload fails
                echo 'Error uploading file: ' . $movefile['error'];
            }
        }
    }

    // Convert the array of attachment IDs to a comma-separated string
    $attachment_ids_string = implode(', ', $gallery_attachment_ids);

    return $attachment_ids_string;
}





// handle legacy wall submissions
function handleLegacyWallFormSubmission()
{
    $post_id = $_GET['post-id'];

    // Sanitize form inputs
    $legacy_dashboard_person_name = sanitize_text_field($_POST['legacy_dashboard_person_name']);
    $legacy_wall_submitted_user_name = sanitize_text_field($_POST['submitted_user_name']);
    $legacy_wall_submitted_user_email = sanitize_email($_POST['submitted_user_email']);

    $write_wall_title = sanitize_text_field($_POST['submitted_user_legacy_wall_title']);
    $write_wall_description = sanitize_textarea_field($_POST['submitted_user_legacy_short']);

    // Create a new post in the "legacy_photos" post type
    $post_data = array(
        'post_title'   => $write_wall_title, // Adjust the title as needed
        'post_content' => $write_wall_description,
        'post_status'  => 'pending',
        'post_type'    => 'legacy-wall',
    );

    $new_wall_post_id = wp_insert_post($post_data);


    // add post meta
    add_post_meta($new_wall_post_id, 'legacy_dashboard_id', $post_id);
   
    // Update ACF fields
    update_field('legacy_dashboard_person_name', $legacy_dashboard_person_name, $new_wall_post_id);
    update_field('legacy_wall_submitted_user_name', $legacy_wall_submitted_user_name, $new_wall_post_id);
    update_field('legacy_wall_submitted_user_email', $legacy_wall_submitted_user_email, $new_wall_post_id);

    // Display success message
    $success_message = 'Your submission is successful.';
    echo '<div class="container mt-5 mb-5">';
    echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
    echo '<a href="?add-legacy-parts&action=wall&post-id=' . $post_id . '" type="button" class="btn btn-primary">Submit Again</a>';
    echo '</div>';
    exit();
}



// handle legacy wall submissions
function handleLegacyVideoFormSubmission()
{
    $post_id = $_GET['post-id'];


    // Sanitize form inputs
    $legacy_dashboard_person_name = sanitize_text_field($_POST['legacy_dashboard_person_name']);
    $legacy_wall_submitted_user_name = sanitize_text_field($_POST['submitted_user_name']);
    $legacy_wall_submitted_user_email = sanitize_email($_POST['submitted_user_email']);

    $submitted_user_legacy_video_title = sanitize_text_field($_POST['submitted_user_legacy_video_title']);
    $submitted_user_legacy_youtube_link = sanitize_text_field($_POST['submitted_user_legacy_youtube_link']);

    // Create a new post in the "legacy_photos" post type
    $post_data = array(
        'post_title'   => $submitted_user_legacy_video_title, // Adjust the title as needed
        'post_status'  => 'pending',
        'post_type'    => 'legacy-videos',
    );

    $new_video_post_id = wp_insert_post($post_data);

    // add post meta
    add_post_meta($new_video_post_id, 'legacy_dashboard_id', $post_id);
   
    // Update ACF fields
    update_field('legacy_video_person_name', $legacy_dashboard_person_name, $new_video_post_id);
    update_field('legacy_video_submitted_user_name', $legacy_wall_submitted_user_name, $new_video_post_id);
    update_field('legacy_video_submitted_user_email', $legacy_wall_submitted_user_email, $new_video_post_id);
    update_field('legacy_video_submitted_yotube_link', $submitted_user_legacy_youtube_link, $new_video_post_id);

    // Display success message
    $success_message = 'Your submission is successful.';
    echo '<div class="container mt-5 mb-5">';
    echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
    echo '<a href="?add-legacy-parts&action=video&post-id=' . $post_id . '" type="button" class="btn btn-primary">Submit Again</a>';
    echo '</div>';
    exit();
}




 // Helper function to extract YouTube video ID
 function get_youtube_video_id($url)
 {
     $video_id = '';
     $pattern = '#(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})#';
     if (preg_match($pattern, $url, $matches)) {
         $video_id = $matches[1];
     }
     return $video_id;
 }




function publishMarkedImages($marked_images) {
    // Logic to publish marked images
    foreach ($marked_images as $image_id) {
        // Implement logic to publish image with ID $image_id
        // Example: update_post_meta($post_id, 'published_images', $image_id);
    }
    // Redirect or display success message after publishing
}

function trashMarkedImages($marked_images) {



    print_r($marked_images);

    
    // Logic to trash marked images
    foreach ($marked_images as $image_id) {
        // Implement logic to trash image with ID $image_id
        // Example: update_post_meta($post_id, 'trashed_images', $image_id);
    }
    // Redirect or display success message after trashing
}