



<?php



// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["publish_marked_images"])) {
        // Include WordPress database access
        global $wpdb;

        $marked_images = isset($_POST['marked_images']) ? $_POST['marked_images'] : array();

        $pending_galleries_images = get_post_meta($post_id, 'pending_galleries_images', true);
        $publsished_galleries_images = get_post_meta($post_id, 'legacy_gallery_published', true);


        $check_if_marked_images_is_in_pending_meta = (check_all_exist_in_string($marked_images, $pending_galleries_images));


        if (!$check_if_marked_images_is_in_pending_meta) {

            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Images already published. <a href="' . $_SERVER['REQUEST_URI'] . '">Refresh Page</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>';

            return;
        }

        $pending_galleries_images = get_post_meta($post_id, 'pending_galleries_images', true);

        // pending gallery
        $original_array = explode(',', $pending_galleries_images);
        $new_array = array_diff($original_array, $marked_images);
        $new_pending_galleries_images = ltrim(implode(',', $new_array), ',');
        update_post_meta($post_id, 'pending_galleries_images', $new_pending_galleries_images);


        $publsished_galleries_images = get_post_meta($post_id, 'legacy_gallery_published', true);

        // published gallery
        $original_array = explode(',', $publsished_galleries_images);
        $new_array = array_diff($original_array, $marked_images);
        $new_array_merged = array_merge($new_array, $marked_images);
        $new_published_galleries_images = ltrim(implode(',', $new_array_merged), ',');
        update_post_meta($post_id, 'legacy_gallery_published', $new_published_galleries_images);

        // Add Bootstrap alert for successful deletion
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Gallery images published successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';



     

        $subject = 'Your gallery is published';
        $message_status = 'Your gallery is successfully published';


        get_post_data_for_marked_images($marked_images, $subject, $message_status);


        // $pending_galleries_images = get_post_meta($post_id, 'legacy_gallery_published', true);
    } elseif (isset($_POST["delete_marked_images"])) {
        // pending
        $marked_images = isset($_POST['marked_images']) ? $_POST['marked_images'] : array();
        $pending_galleries_images = get_post_meta($post_id, 'pending_galleries_images', true);
        $original_array = explode(',', $pending_galleries_images);
        $new_array = array_diff($original_array, $marked_images);
        $new_pending_galleries_images = implode(',', $new_array);


        update_post_meta($post_id, 'pending_galleries_images', $new_pending_galleries_images);

        // Check if new_pending_galleries_images is not empty and display success message
        if (!empty($marked_images)) {
            // Add Bootstrap alert for successful deletion
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Images deleted successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
    }

    unset($_POST);
}



// $pending_galleries_images = get_post_meta($post_id, 'legacy_gallery_published', true);
$pending_galleries_images = get_post_meta($post_id, 'pending_galleries_images', true);



if ($pending_galleries_images) {
    $attachment_ids = explode(',', $pending_galleries_images);

    if (!empty($attachment_ids)) {
        echo '<div class="container mt-4">';
        echo '<form method="post" action="">';

        echo '<div class="row">';
        foreach ($attachment_ids as $attachment_id) {
            $attachment_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');

            if ($attachment_url) {
                echo '<div class="mb-3 col-md-3 legacy-photo">';
                echo '<img src="' . esc_url($attachment_url) . '" class="img-thumbnail" alt="Image">';
                echo '<div class="form-check mt-2">';
                echo '<input type="checkbox" class="form-check-input" id="image_' . esc_attr($attachment_id) . '" name="marked_images[]" value="' . esc_attr($attachment_id) . '">';
                echo '<label class="form-check-label" for="image_' . esc_attr($attachment_id) . '">Mark</label>';
                echo '</div>';
                echo '</div>';
            }
        }

        echo '<div class="center-button text-center mt-5">';
        echo '<button type="submit" name="publish_marked_images" class="btn btn-primary me-2">Publish Marked Images</button>';
        echo '<button type="submit" name="delete_marked_images" class="btn btn-primary">Delete Permanently</button>';
        echo '</div>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
    } else {
        echo 'No images found.';
    }
} else {



    echo 'No pending galleriess images found.';
}



?>
