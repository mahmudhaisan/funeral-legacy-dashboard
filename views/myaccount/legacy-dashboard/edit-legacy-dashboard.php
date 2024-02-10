<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['funeral_update_button'])) {

    $user_id = get_current_user_id();
    $post_type = 'legacy-funeral';
    $user_post_count = count_user_posts(get_current_user_id(), $post_type, true);

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
        $person_image_attachment_id = upload_image_to_media_library($person_image, $upload_dir);


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


             // Get the permalink for the post
            $post_permalink = get_permalink($post_id);

            // Get My Account page URL
            $my_account_url = wc_get_account_endpoint_url('');
            // Append 'funeral-list' endpoint to My Account URL
            $funeral_list_url = trailingslashit($my_account_url) . 'legacy-dashboard';



            // Post created successfully
            echo '<div class="container">';
            echo '<div class="d-flex justify-content-between align-items-center alert alert-success">';
            echo '<div class="" role="alert">Legacy Dashboard Updated Successfully!</div>';
            echo '<div class="d-inline-block ml-2"><a class="bg-primary me-2 text-white p-2 text-decoration-none" href="' . esc_url($funeral_list_url) . '">See List</a><a class="bg-primary me-2 text-white p-2 text-decoration-none" href="'.esc_url($post_permalink) .'">View Dashboard</a></div>';
            echo '</div>';
        }
    } else {
        echo '<div class="container">';
        echo '<div class="alert alert-warning" role="alert">You have already added a funeral personality. You can edit it here.</div>';
        echo '</div>';
    }
}

// Retrieve all values in variables with checks for emptiness
$post_id = $_GET['post_id'];
// Get the post_id from the URL parameter
$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

// Check if the post_id is valid
if ($post_id > 0) {
    // Get the updated post
    $updated_post = get_post($post_id);

    // Retrieve the person's name
    $person_name = !empty($updated_post->post_title) ? $updated_post->post_title : '';
    $short_description = !empty(get_field('funeral_person_short_description', $post_id)) ? get_field('funeral_person_short_description', $post_id) : '';
    $obituary = !empty(get_field('funeral_obituary', $post_id)) ? get_field('funeral_obituary', $post_id) : '';
    $music_link = !empty(get_field('funeral_music_link', $post_id)) ? get_field('funeral_music_link', $post_id) : '';
    $service_date = !empty(get_field('funeral_service_date', $post_id)) ? get_field('funeral_service_date', $post_id) : '';
    $service_time = !empty(get_field('funeral_service_time', $post_id)) ? get_field('funeral_service_time', $post_id) : '';
    $service_location = !empty(get_field('funeral_service_location', $post_id)) ? get_field('funeral_service_location', $post_id) : '';
    $service_phone_number = !empty(get_field('funeral_service_phone_number', $post_id)) ? get_field('funeral_service_phone_number', $post_id) : '';
    $dress_attire = !empty(get_field('funeral_service_dress_attire', $post_id)) ? get_field('funeral_service_dress_attire', $post_id) : '';
    $favorite_flowers = !empty(get_field('funeral_service_favorite_flowers', $post_id)) ? get_field('funeral_service_favorite_flowers', $post_id) : '';
    $religion_observed = !empty(get_field('funeral_service_religion_observed', $post_id)) ? get_field('funeral_service_religion_observed', $post_id) : '';
    $live_streamed_link = !empty(get_field('funeral_service_live_streamed_details', $post_id)) ? get_field('funeral_service_live_streamed_details', $post_id) : '';
    $charity_name = !empty(get_field('charity_name', $post_id)) ? get_field('charity_name', $post_id) : '';
    $charity_details = !empty(get_field('charity_details', $post_id)) ? get_field('charity_details', $post_id) : '';
    $charity_link = !empty(get_field('charity_link', $post_id)) ? get_field('charity_link', $post_id) : '';
}


?>
<div class="add-funeral-personality">
    <div class="container">
        <h2>Update Legacy Dashboard</h2>
        <form method="post" action="" enctype="multipart/form-data">

            <!-- Person's Name -->
            <div class="mb-3">
                <label for="personsName" class="form-label">Person's Name</label>
                <input type="text" class="form-control" id="personsName" name="persons_name" value="<?php echo esc_attr($person_name); ?>">
            </div>

            <!-- Person's Image -->
            <div class="mb-3">
                <label for="personsImage" class="form-label">Person's Image</label>
                <input type="file" class="form-control" id="personsImage" name="persons_image">
            </div>

            <!-- Short Description -->
            <div class="mb-3">
                <label for="shortDescription" class="form-label">Short Description <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="shortDescription" name="short_description" value="<?php echo esc_attr($short_description); ?>" requireds>
            </div>

            <!-- Obituary -->
            <div class="mb-3">
                <label for="obituary" class="form-label">Obituary <span class="text-danger">*</span></label>
                <textarea class="form-control" id="obituary" name="obituary" rows="4" requireds><?php echo esc_textarea($obituary); ?></textarea>
            </div>

            <!-- Music Link -->
            <div class="mb-3">
                <label for="musicLink" class="form-label">Music Link <span class="text-danger">*</span></label>
                <input type="url" class="form-control" id="musicLink" name="music_link" value="<?php echo esc_url($music_link); ?>" requireds>
            </div>

            <!-- Funeral Service Date -->
            <div class="mb-3">
                <label for="serviceDate" class="form-label">Funeral Service Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="serviceDate" name="service_date" value="<?php echo esc_attr($service_date); ?>" requireds>
            </div>

            <!-- Funeral Service Time -->
            <div class="mb-3">
                <label for="serviceTime" class="form-label">Funeral Service Time <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="serviceTime" name="service_time" value="<?php echo esc_attr($service_time); ?>" requireds>
            </div>

            <!-- Funeral Service Location -->
            <div class="mb-3">
                <label for="serviceLocation" class="form-label">Funeral Service Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="serviceLocation" name="service_location" value="<?php echo esc_attr($service_location); ?>" requireds>
            </div>

            <!-- Funeral Service Phone Number -->
            <div class="mb-3">
                <label for="servicePhoneNumber" class="form-label">Funeral Service Phone Number <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="servicePhoneNumber" name="service_phoneNumber" value="<?php echo esc_attr($service_phone_number); ?>" requireds>
            </div>

            <!-- Funeral Service Dress Attire -->
            <div class="mb-3">
                <label for="dressAttire" class="form-label">Funeral Service Dress Attire <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="dressAttire" name="dress_attire" value="<?php echo esc_attr($dress_attire); ?>" requireds>
            </div>

            <!-- Funeral Service Favorite Flowers -->
            <div class="mb-3">
                <label for="favoriteFlowers" class="form-label">Funeral Service Favorite Flowers <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="favoriteFlowers" name="favorite_flowers" value="<?php echo esc_attr($favorite_flowers); ?>" requireds>
            </div>

            <!-- Funeral Service Religion Observed -->
            <div class="mb-3">
                <label for="religionObserved" class="form-label">Funeral Service Religion Observed <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="religionObserved" name="religion_observed" value="<?php echo esc_attr($religion_observed); ?>" requireds>
            </div>

            <!-- Funeral Service Live Streamed Details -->
            <div class="mb-3">
                <label for="liveStreamed" class="form-label"> Funeral Service Live Streamed Details <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="liveStreamed" name="live_streamed_link" value="<?php echo esc_url($live_streamed_link); ?>" requireds>
            </div>

            <!-- Charity Name -->
            <div class="mb-3">
                <label for="charityName" class="form-label">Charity Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="charityName" name="charity_name" value="<?php echo esc_attr($charity_name); ?>" requireds>
            </div>

            <!-- Charity Details -->
            <div class="mb-3">
                <label for="charityDetails" class="form-label">Charity Details</label>
                <textarea class="form-control" id="charityDetails" name="charity_details" rows="4"><?php echo esc_textarea($charity_details); ?></textarea>
            </div>

            <!-- Charity Image - Image -->
            <div class="mb-3">
                <label for="charityImage" class="form-label">Charity Image - Image</label>
                <input type="file" class="form-control" id="charityImage" name="charity_image">
            </div>

            <!-- Charity Link -->
            <div class="mb-3">
                <label for="charityLink" class="form-label">Charity Link <span class="text-danger">*</span></label>
                <input type="url" class="form-control" id="charityLink" name="charity_link" value="<?php echo esc_url($charity_link); ?>" requireds>
            </div>

            <button type="submit" name="funeral_update_button" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>