<?php

$post_id = $_GET['post-id'];
$post_title = get_the_title($post_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_legacy_form'])) {

    // Sanitize and process form data
    $name = sanitize_text_field($_POST['submitted_user_name']);
    $email = sanitize_email($_POST['submitted_user_email']);
    $legacyName = sanitize_text_field($_POST['submitted_user_legacy_name']);
    $writeShort = sanitize_textarea_field($_POST['submitted_user_legacy_short']);

    // Handle featured image upload
    if (!empty($_FILES['submitted_user_featured_image']['tmp_name'])) {
        $featured_image_id = upload_image('submitted_user_featured_image');
    }

    // Handle gallery images upload
    if (!empty($_FILES['submitted_user_legacy_gallery']['tmp_name'])) {
        $gallery_image_ids = upload_images('submitted_user_legacy_gallery');
        // You can save $gallery_image_ids to a database or perform other actions.
    }

    // Create a new post in the "legacy_photos" post type
    $post_data = array(
        'post_title'   => $name . "'s Legacy Photo", // Adjust the title as needed
        'post_content' => $writeShort,
        'post_status'  => 'publish',
        'post_author'  => get_current_user_id(), // Set the author as the current logged-in user
        'post_type'    => 'legacy-photos',
        // Add additional post meta as needed
        'meta_input'   => array(
            'legacy_name' => $legacyName,
            'legacy_email' => $email,
            // Add more meta fields as needed
        ),
    );

    $new_post_id = wp_insert_post($post_data);

    // Attach featured image to the post
    if (!is_wp_error($new_post_id) && !empty($featured_image_id)) {
        set_post_thumbnail($new_post_id, $featured_image_id);
    }

    // Attach gallery images to the post
    if (!is_wp_error($new_post_id) && !empty($gallery_image_ids)) {
        add_post_meta($new_post_id, 'submitted_user_gallery', $gallery_image_ids);
    }


    $post_id_referred = add_post_meta($new_post_id, 'legacy_dashboard_id', $post_id);

    $submitted_galleries = get_post_meta($new_post_id, 'submitted_user_gallery', true);

    // Convert the comma-separated string to an array
    $existing_pending_gallery_images = get_post_meta($post_id, 'pending_galleries_images', true);

    // Check if $existing_pending_gallery_images is not empty before concatenating
    if (!empty($existing_pending_gallery_images)) {
        // Add the new galleries to the existing array
        $combined_galleries = $existing_pending_gallery_images . ',' . $submitted_galleries;
    } else {
        // If $existing_pending_gallery_images is empty, just use $submitted_galleries
        $combined_galleries = $submitted_galleries;
    }

    // Update the post meta with the new comma-separated string
    update_post_meta($post_id, 'pending_galleries_images', $combined_galleries);


    $success_message = 'Your submission is successfull. ';

    echo '<div class="container mt-5 mb-5">';
    echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
    echo '<a href="?add-legacy-parts&action=photos&post-id=' . $post_id . '" type="button" class="btn btn-primary">Submit Again</a>';
    echo '</div>';
    exit();
}



?>


<div class="container mt-5">
    <h2>Add Legacy Image</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="submit_legacy_form" value="1">
      
         <!-- Name of the Legacy Person -->
         <div class="mb-3">
            <label for="legacyName" class="form-label">Name of the Legacy Person</label>
            <input type="text" class="form-control" id="legacyName" name="submitted_user_legacy_name" value="<?php echo $post_title; ?>" requireds readonly>
        </div>
      
      
        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="submittedUsername" name="submitted_user_name" requireds>
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Your Email Address</label>
            <input type="email" class="form-control" id="email" name="submitted_user_email" requireds>
        </div>

        <!-- Featured Image -->
        <div class="mb-3">
            <label for="featuredImage" class="form-label">Featured Image</label>
            <input type="file" class="form-control" id="featuredImage" name="submitted_user_featured_image" accept="image/*" requireds>
        </div>

        <!-- Gallery (Multiple Images) -->
        <div class="mb-3">
            <label for="gallery" class="form-label">Gallery (Multiple Images)</label>
            <input type="file" class="form-control" id="gallery" name="submitted_user_legacy_gallery[]" multiple accept="image/*">
        </div>

       

        <!-- Write Short -->
        <div class="mb-3">
            <label for="writeShort" class="form-label">Write Short</label>
            <textarea class="form-control" id="writeShort" name="submitted_user_legacy_short" rows="3" requireds></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Upload Now</button>
    </form>
</div>