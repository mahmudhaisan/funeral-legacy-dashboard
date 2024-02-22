<?php

function upload_image_to_media_library($image_data)
{
    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }


    $upload_overrides = array('test_form' => false);
    $movefile = wp_handle_upload($image_data, $upload_overrides);

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


function handle_funeral_dashboard_action($post_id, $post_action, $post_type, $user_id = null)
{
    $output = ''; // Initialize output variable

    if (empty($post_id) || empty($post_action)) {
        return '<div class="alert alert-warning" role="alert">Invalid parameters.</div>';
    }

    // Check if the current user has any published posts in the specified post type
    if (!empty($user_id)) {
        $user_post_count = count_user_posts($user_id, $post_type, true);
    }

    if ($post_action == 'trash') {
        // Check if the post ID exists
        $post = get_post($post_id);

        if ($post) {
            // Perform deletion
            $result = wp_trash_post($post_id); // Set the second parameter to true for force deletion

            if ($result !== false) {
                $output .= '<div class="alert alert-success" role="alert">Post trashed successfully.</div>';
            } else {
                $output .= '<div class="alert alert-danger" role="alert">Error trashing dashboard. Please try again.</div>';
            }
        } else {
            // Post ID does not exist
            $output .= '<div class="alert alert-warning" role="alert">Post ID does not exist.</div>';
        }
    } elseif ($post_action == 'edit') {
        // Handle edit action
        $output .= 'Edit action handled.';
    } else {
        // Invalid action
        $output .= '<div class="alert alert-warning" role="alert">Invalid action.</div>';
    }

    return '<div class="container">' . $output . '</div>';
}



function handle_trashed_funeral_dashboard_action($post_id, $post_action, $post_type, $user_post_count)
{

    // Get My Account page URL
    $my_account_url = wc_get_account_endpoint_url('');
    // Append 'funeral-list' endpoint to My Account URL
    $funeral_list_url = trailingslashit($my_account_url) . 'legacy-dashboard';
    $output = ''; // Initialize output variable

    if (empty($post_id) || empty($post_action)) {
        return '<div class="alert alert-warning" role="alert">Invalid parameters.</div>';
    }

    // Restore trashed posts
    if ($post_action == 'restore') {
        // Check if the user has any published posts
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

                            $success_message = "Post restored and published successfully.";
                            $redirect_url = add_query_arg('success_msg', urlencode($success_message), $funeral_list_url);
                            header("Location: $redirect_url");
                            exit;
                        } else {
                            $output .= '<div class="alert alert-danger" role="alert">Error updating post status to published.</div>';
                        }
                    } else {
                        $output .= '<div class="alert alert-danger" role="alert">Error restoring post from trash.</div>';
                    }
                } else {
                    // Post is not in the trash
                    $output .= '<div class="alert alert-warning" role="alert">Post is not in the trash.</div>';
                }
            } else {
                // Post ID does not exist
                $output .= '<div class="alert alert-warning" role="alert">Post ID does not exist.</div>';
            }
        } else {
            $output .= '<div class="alert alert-danger" role="alert">Error: Only one post is allowed.</div>';
        }
    }

    // Delete trashed posts
    if ($post_action == 'delete') {
        // Check if the post ID exists
        $post = get_post($post_id);

        if ($post) {
            // Perform deletion
            $result = wp_delete_post($post_id, true); // Set the second parameter to true for force deletion

            if ($result !== false) {
                $output .= '<div class="alert alert-success" role="alert">Post deleted successfully.</div>';
            } else {
                $output .= '<div class="alert alert-danger" role="alert">Error deleting post.</div>';
            }
        } else {
            // Post ID does not exist
            $output .= '<div class="alert alert-warning" role="alert">Post ID does not exist.</div>';
        }
    }

    return $output;
}

function handle_funeral_dashboard_submission()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['funeral_submit_button'])) {
        // Check if the current user has any published posts of the specified post type
        $user_id = get_current_user_id();
        $post_type = 'legacy-funeral';

        $args = array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'author' => $user_id,
            'posts_per_page' => 1, // Retrieve only one post
        );

        $existing_posts = get_posts($args);

        $my_account_url = wc_get_account_endpoint_url('');
        // Append 'funeral-list' endpoint to My Account URL
        $funeral_list_url = trailingslashit($my_account_url) . 'legacy-dashboard';

        if (empty($existing_posts)) {
            // Process form data
            $person_name = sanitize_text_field($_POST["persons_name"]);
            $short_description = sanitize_text_field($_POST["short_description"]);
            $obituary = sanitize_textarea_field($_POST["obituary"]);
            $music_link = esc_url($_POST["music_link"]);
            $service_date = sanitize_text_field($_POST["service_date"]);
            $service_time = sanitize_text_field($_POST["service_time"]);
            $service_location = sanitize_text_field($_POST["service_location"]);
            $service_phone_number = sanitize_text_field($_POST["service_phoneNumber"]);
            $dress_attire = sanitize_text_field($_POST["dress_attire"]);
            $favorite_flowers = sanitize_text_field($_POST["favorite_flowers"]);
            $religion_observed = sanitize_text_field($_POST["religion_observed"]);
            $live_streamed_link = sanitize_text_field($_POST["live_streamed_link"]);

            $charity_name = sanitize_text_field($_POST["charity_name"]);
            $charity_details = sanitize_textarea_field($_POST["charity_details"]);
            $charity_link = esc_url($_POST["charity_link"]);
            // Person's Image
            $person_image = $_FILES["persons_image"];
            // Charity Image
            $charity_image = $_FILES["charity_image"];

            // Handle uploaded images and add to media library
            $upload_dir = wp_upload_dir();

            // Handle Person's Image
            $person_image_attachment_id = upload_image_to_media_library($person_image, $upload_dir);
            $charity_image_attachment_id = upload_image_to_media_library($charity_image);

            $current_user_id = get_current_user_id();

            $new_post = array(
                'post_title'   => $person_name,
                'post_status'  => 'publish',
                'post_type'    => $post_type,
                'author' => $current_user_id
            );

            $post_id = wp_insert_post($new_post);

            if ($post_id) {
                set_post_thumbnail($post_id, $person_image_attachment_id);
                // person info
                update_field('funeral_person_short_description', $short_description, $post_id);
                update_field('funeral_obituary', $obituary, $post_id);
                update_field('funeral_music_link', $music_link, $post_id);

                // funeral service
                update_field('funeral_service_date', $service_date, $post_id);
                update_field('funeral_service_time', $service_time, $post_id);
                update_field('funeral_service_location', $service_location, $post_id);
                update_field('funeral_service_phone_number', $service_phone_number, $post_id);
                update_field('funeral_service_dress_attire', $dress_attire, $post_id);
                update_field('funeral_service_favorite_flowers', $favorite_flowers, $post_id);
                update_field('funeral_service_religion_observed', $religion_observed, $post_id);
                update_field('funeral_service_live_streamed_details',  $live_streamed_link, $post_id);

                //charity
                update_field('charity_name', $charity_name, $post_id);
                update_field('charity_details', $charity_details, $post_id);
                update_field('charity_link', $charity_link, $post_id);
                update_field('charity_image', $charity_image_attachment_id, $post_id);

                // Redirect to funeral list with success message
                $success_message = "Legacy Dashboard added Successfully!";
                $redirect_url = add_query_arg('success_msg', urlencode($success_message), $funeral_list_url);
                wp_redirect($redirect_url);
                exit;
            }
        } else {
            // User already has a funeral dashboard entry
            $output = '<div class="container">';
            $output .= '<div class="d-flex justify-content-between alert alert-warning">';
            $output .= '<div class="" role="alert">You have already a funeral personality added. You can edit it here.</div>';
            $output .= '<p class="d-inline-block ml-2 mb-4"><a class="bg-primary text-white p-2 text-decoration-none" href="' . $funeral_list_url . '">View List</a></p>';
            $output .= '</div>';
            $output .= '</div>';
            echo $output;
        }
    }
}



function update_legacy_dashboard()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['funeral_update_button'])) {


        $my_account_url = wc_get_account_endpoint_url('');
        // Append 'funeral-list' endpoint to My Account URL
        $funeral_list_url = trailingslashit($my_account_url) . 'legacy-dashboard';

        $user_id = get_current_user_id();
        $post_type = 'legacy-funeral';




        $user = wp_get_current_user();


        if (in_array('local_admin', $user->roles)) {

            $parent_user_id = get_current_user_id();
        } elseif (in_array('admin_assistant', $user->roles)) {


            $current_user_id = get_current_user_id();
            $parent_user_id = get_user_meta($current_user_id, '_created_by', true);
        } else {
            $parent_user_id = get_current_user_id();
        }




        $user_post_count = count_user_posts($parent_user_id, $post_type, true);
        $post_id = $_GET['post_id'];

        if ($user_post_count == 1) {
            $person_name = sanitize_text_field($_POST["persons_name"]);
            $short_description = sanitize_text_field($_POST["short_description"]);
            $obituary = sanitize_textarea_field($_POST["obituary"]);
            $music_link = esc_url($_POST["music_link"]);
            $service_date = sanitize_text_field($_POST["service_date"]);
            $service_time = sanitize_text_field($_POST["service_time"]);
            $service_location = sanitize_text_field($_POST["service_location"]);
            $service_phone_number = sanitize_text_field($_POST["service_phoneNumber"]);
            $dress_attire = sanitize_text_field($_POST["dress_attire"]);
            $favorite_flowers = sanitize_text_field($_POST["favorite_flowers"]);
            $religion_observed = sanitize_text_field($_POST["religion_observed"]);
            $live_streamed_link = sanitize_text_field($_POST["live_streamed_link"]);
            $charity_name = sanitize_text_field($_POST["charity_name"]);
            $charity_details = sanitize_textarea_field($_POST["charity_details"]);
            $charity_link = esc_url($_POST["charity_link"]);
            $person_image = $_FILES["persons_image"];
            $charity_image = $_FILES["charity_image"];
            $upload_dir = wp_upload_dir();

            $person_image_attachment_id = upload_image_to_media_library($person_image);
            $charity_image_attachment_id = upload_image_to_media_library($charity_image);


            if (!empty($post_id)) {


                // Create an array with the updated post data
                $post_data = array(
                    'ID'         => $post_id,
                    'post_title' =>  $person_name,
                );

                // Update the post with the new title
                wp_update_post($post_data);
                set_post_thumbnail($post_id, $person_image_attachment_id);
                update_field('funeral_person_short_description', $short_description, $post_id);
                update_field('funeral_obituary', $obituary, $post_id);
                update_field('funeral_music_link', $music_link, $post_id);
                update_field('funeral_service_date', $service_date, $post_id);
                update_field('funeral_service_time', $service_time, $post_id);
                update_field('funeral_service_location', $service_location, $post_id);
                update_field('funeral_service_phone_number', $service_phone_number, $post_id);
                update_field('funeral_service_dress_attire', $dress_attire, $post_id);
                update_field('funeral_service_favorite_flowers', $favorite_flowers, $post_id);
                update_field('funeral_service_religion_observed', $religion_observed, $post_id);
                update_field('funeral_service_live_streamed_details', $live_streamed_link, $post_id);
                update_field('charity_name', $charity_name, $post_id);
                update_field('charity_details', $charity_details, $post_id);
                update_field('charity_link', $charity_link, $post_id);
                update_field('charity_image', $charity_image_attachment_id, $post_id);


                // Get the permalink for the post
                $post_permalink = get_permalink($post_id);

                // Get My Account page URL
                $my_account_url = wc_get_account_endpoint_url('');
                // Append 'funeral-list' endpoint to My Account URL
                $funeral_list_url = trailingslashit($my_account_url) . 'legacy-dashboard';



                // Assuming $funeral_list_url contains the URL you want to redirect to
                $success_message = "Legacy Dashboard Updated Successfully!";
                $redirect_url = add_query_arg('success_msg', urlencode($success_message), $funeral_list_url);
                header("Location: $redirect_url");
                exit;
            }
        } else {
            echo '<div class="container">';
            echo '<div class="alert alert-warning" role="alert">You have already added a funeral personality. You can edit it here.</div>';
            echo '</div>';
        }
    }
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
        'post_status'  => 'trash',
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




function publishMarkedImages($marked_images)
{
    // Logic to publish marked images
    foreach ($marked_images as $image_id) {
        // Implement logic to publish image with ID $image_id
        // Example: update_post_meta($post_id, 'published_images', $image_id);
    }
    // Redirect or display success message after publishing
}

function trashMarkedImages($marked_images)
{



    print_r($marked_images);


    // Logic to trash marked images
    foreach ($marked_images as $image_id) {
        // Implement logic to trash image with ID $image_id
        // Example: update_post_meta($post_id, 'trashed_images', $image_id);
    }
    // Redirect or display success message after trashing
}




function check_all_exist_in_string($marked_images, $pending_galleries_images)
{
    // Convert the string to an array for easier comparison
    $pending_images_array = explode(',', $pending_galleries_images);

    // Check if all values in $marked_images exist in $pending_images_array
    foreach ($marked_images as $value) {
        if (!in_array($value, $pending_images_array)) {
            return false;
        }
    }
    return true;
}





/**
 * Retrieve post data for marked images
 *
 * @param array $marked_images An array containing the marked image IDs
 * @param object $wpdb The WordPress database object
 * @return array An array containing post data associated with the marked images
 */
function get_post_data_for_marked_images($marked_images, $subject, $message_status)
{

    global $wpdb;
    $post_data = array();

    // Loop through each marked image
    foreach ($marked_images as $meta_value_to_search) {
        // Prepare the SQL query to retrieve post IDs based on the meta value
        $query = $wpdb->prepare("
            SELECT DISTINCT post_id
            FROM $wpdb->postmeta
            WHERE (`meta_key` = 'submitted_user_gallery' OR `meta_value` = 'submitted_user_gallery')
            AND `meta_value` LIKE %s
        ", '%' . $meta_value_to_search . '%');

        // Execute the query
        $results = $wpdb->get_results($query);

        // Extract post IDs from the results and add them to the post_data array
        foreach ($results as $result) {
            // Get data
            $post_title = get_the_title($result->post_id);
            $legacy_submitted_user_email = get_post_meta($result->post_id, 'submitted_user_email', true);

            // Check if legacy_submitted_user_email is not empty and is a valid email address
            if (!empty($legacy_submitted_user_email) && filter_var($legacy_submitted_user_email, FILTER_VALIDATE_EMAIL)) {
                // Add post ID along with associated meta values to the post_data array
                $post_data[$result->post_id] = array(
                    'submitted_user_name' => $post_title,
                    'submitted_user_email' => $legacy_submitted_user_email
                );
            }
        }
    }


    // Send emails to users
    foreach ($post_data as $post_id => $meta_values) {

        $to = $meta_values['submitted_user_email'];
        // print_r($to);
        $subject = $subject;
        $message = 'Dear ' . $meta_values['submitted_user_name'] . ',<br><br>';
        $message .= $message_status . "<br><br>";
        $message .= 'Thank you for your submission.<br><br>';
        // Additional message content or formatting can be added here

        // Set headers for HTML email
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: Wordpress<wordpress@mysite.com>';

        // Send email
        $email_to_recipients =  wp_mail($to, $subject, $message, $headers);
    }

    // return ($post_data);
}





function send_custom_email($recipient_name, $recipient_email, $email_subject, $email_body)
{
    // Check if recipient name and email are set
    if (isset($recipient_name, $recipient_email)) {
        // Compose email
        $to = $recipient_email;
        $subject = $email_subject;
        $message = "Dear $recipient_name,<br><br>";
        $message .= "$email_body<br><br>";
        $message .= 'Thank you.<br><br>';
        $headers = array('Content-Type: text/html; charset=UTF-8');

        // Send email
        $sent = wp_mail($to, $subject, $message, $headers);

        if ($sent) {
            echo "Email sent successfully to $to.\n";
        } else {
            echo "Failed to send email to $to.\n";
        }
    } else {
        echo "Recipient name or email is not set. Skipping...\n";
    }
}




function get_parent_local_admin_id() {
    $user_info = wp_get_current_user();
    $current_user_id = get_current_user_id();

    if (in_array('local_admin', $user_info->roles)) {
        // If the current user is an administrator, return their own ID
        return $current_user_id;
    } elseif (in_array('admin_assistant', $user_info->roles)) {
        // If the current user is an admin assistant, get the ID of the local admin who created them
        $admin_assistant_id = $current_user_id;
        $parent_local_admin_id = get_user_meta($admin_assistant_id, '_created_by', true);
        return $parent_local_admin_id;
    } elseif(in_array('administrator', $user_info->roles)) {
        return $current_user_id;
        // return $parent_local_admin_id;
    }
}




function get_single_post_id_by_user_id($user_id, $post_type) {


        // User found, now get one post by that user
        $args = array(
            'author'         => $user_id,
            'posts_per_page' => 1, // Get only one post
            'post_type'      => $post_type, // Post type
            'post_status'    => 'publish', // Get only published posts
        );

        $posts = get_posts($args);

        // Check if any posts are found
        if ($posts) {
            // Get the ID of the first (and only) post in the array
            $post_id = $posts[0]->ID;
            return $post_id;
        } else {
            return false; // No posts found by author and type
        }
    
}
