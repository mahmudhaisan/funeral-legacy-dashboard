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
