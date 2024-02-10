<?php 




$pending_galleries_images = get_post_meta($post_id, 'trashed_gallery_images', true);

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
        echo '<button type="submit" name="submit_marked_images" class="btn btn-primary">publish Marked Images</button>';
        echo '</div>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
    } else {
        echo 'No images found.';
    }
} else {
    echo 'No trashed galleries images found.';
}

