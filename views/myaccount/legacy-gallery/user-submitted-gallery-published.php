<?php


// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["trash_marked_images"])) {
        $published_galleries_images = get_post_meta($post_id, 'legacy_gallery_published', true);
        $pending_galleries_images = get_post_meta($post_id, 'pending_galleries_images', true);
        $marked_images_ids_arr = isset($_POST['marked_images']) ? $_POST['marked_images'] : array();

        //published galleries
        $original_published_array = !empty($published_galleries_images) ? explode(',', $published_galleries_images) : [];
        $new_published_array = array_diff($original_published_array, $marked_images_ids_arr);
        $new_comma_separated_published_galleries_ids = ltrim(implode(',', $new_published_array), ',');

        // pending galleries
        $original_pending_array = !empty($pending_galleries_images) ? explode(',', $pending_galleries_images) : [];
        $new_pending_array = array_diff($original_pending_array, $marked_images_ids_arr);
        $new_merged_pending_array = array_merge($new_pending_array, $marked_images_ids_arr);
        // Remove any leading comma from the string
        $new_comma_separated_pending_galleries_ids = ltrim(implode(',', $new_merged_pending_array), ',');



        update_post_meta($post_id, 'legacy_gallery_published', $new_comma_separated_published_galleries_ids);
        update_post_meta($post_id, 'pending_galleries_images', $new_comma_separated_pending_galleries_ids);



        // Check if new_pending_galleries_images is not empty and display success message
        if (!empty($marked_images_ids_arr)) {
            // Add Bootstrap alert for successful deletion
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Gallery images published successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        }
    }
}





$published_galleries_images = get_post_meta($post_id, 'legacy_gallery_published', true);


if ($published_galleries_images) {
    $attachment_ids = explode(',', $published_galleries_images);
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
        echo '<button type="submit" name="trash_marked_images" class="btn btn-primary">Move To Pending List</button>';
        echo '</div>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
    } else {
        echo 'No images found.';
    }
} else {
    echo 'No pending galleries images found.';
}
