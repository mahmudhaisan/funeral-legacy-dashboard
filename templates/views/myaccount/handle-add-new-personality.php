<?php 



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['funeral_submit_button'])) {


    // Check if the current user has any published posts of the specified post type
    $user_id = get_current_user_id();
    $args = array(
        'post_type' => 'legacy-funeral',
        'post_status' => 'publish',
        'author' => $user_id,
        'posts_per_page' => 1, // Retrieve only one post
    );

    $existing_posts = get_posts($args);

    if (empty($existing_posts)) {

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

        $current_user_id = get_current_user_id();

        $new_post = array(
            'post_title'   => $person_name,
            'post_status'  => 'publish',
            'post_type'    => 'legacy-funeral',
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

            // Post created successfully
            echo '<div class="alert alert-success" role="alert">Funeral post created successfully!</div>';
        }
    } else {
        echo '<div class="alert alert-warning" role="alert">You have already a funeral personality added. You can edit it here.</div>';
    }
}
