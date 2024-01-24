<?php


$user_id = get_current_user_id();
$args = array(
    'post_type' => 'legacy-funeral',
    'post_status' => 'publish',
    'author' => $user_id,
    'posts_per_page' => 1,
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

    $person_image = $_FILES["persons_image"];
    $charity_image = $_FILES["charity_image"];

    $upload_dir = wp_upload_dir();

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

        echo '<div class="alert alert-success" role="alert">Funeral post created successfully!</div>';
    }
} else {
    echo '<div class="alert alert-warning" role="alert">You have already added a funeral personality. You can edit it here.</div>';
}


// Check if it's an edit operation
$is_edit = isset($_GET['edit-funeral-personality']);
$edit_post_id = $is_edit ? intval($_GET['edit-funeral-personality']) : 0;
$edit_post = get_post($edit_post_id);

?>

<div class="add-funeral-personality">
    <div class="container">
        <h2><?php echo $is_edit ? 'Edit' : 'Add'; ?> Funeral Personality</h2>
        <form method="post" action="" enctype="multipart/form-data">

            <?php if ($is_edit && $edit_post) : ?>
                <input type="text" name="funeral_edit_id" value="<?php echo esc_attr($edit_post_id); ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="personsName" class="form-label">Person's Name</label>
                <input type="text" class="form-control" id="personsName" name="persons_name" value="<?php echo $is_edit && $edit_post ? esc_attr(get_the_title($edit_post)) : ''; ?>">
            </div>

            <div class="mb-3">
                <label for="personsImage" class="form-label">Person's Image</label>
                <input type="file" class="form-control" id="personsImage" name="persons_image">
            </div>

            <div class="mb-3">
                <label for="shortDescription" class="form-label">Short Description <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="shortDescription" name="short_description" required>
            </div>

            <div class="mb-3">
                <label for="obituary" class="form-label">Obituary <span class="text-danger">*</span></label>
                <textarea class="form-control" id="obituary" name="obituary" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="musicLink" class="form-label">Music Link <span class="text-danger">*</span></label>
                <input type="url" class="form-control" id="musicLink" name="music_link" required>
            </div>

            <div class="mb-3">
                <label for="serviceDate" class="form-label">Funeral Service Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="serviceDate" name="service_date" required>
            </div>

            <div class="mb-3">
                <label for="serviceTime" class="form-label">Funeral Service Time <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="serviceTime" name="service_time" required>
            </div>

            <div class="mb-3">
                <label for="serviceLocation" class="form-label">Funeral Service Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="serviceLocation" name="service_location" required>
            </div>

            <div class="mb-3">
                <label for="servicePhoneNumber" class="form-label">Funeral Service Phone Number <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="servicePhoneNumber" name="service_phoneNumber" required>
            </div>

            <div class="mb-3">
                <label for="dressAttire" class="form-label">Funeral Service Dress Attire <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="dressAttire" name="dress_attire" required>
            </div>

            <div class="mb-3">
                <label for="favoriteFlowers" class="form-label">Funeral Service Favorite Flowers <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="favoriteFlowers" name="favorite_flowers" required>
            </div>

            <div class="mb-3">
                <label for="religionObserved" class="form-label">Funeral Service Religion Observed <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="religionObserved" name="religion_observed" required>
            </div>

            <div class="mb-3">
                <label for="liveStreamed" class="form-label"> Funeral Service Live Streamed Details <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="liveStreamed" name="live_streamed_link" required>
            </div>

            <div class="mb-3">
                <label for="charityName" class="form-label">Charity Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="charityName" name="charity_name" required>
            </div>

            <div class="mb-3">
                <label for="charityDetails" class="form-label">Charity Details</label>
                <textarea class="form-control" id="charityDetails" name="charity_details" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label for="charityImage" class="form-label">Charity Image - Image</label>
                <input type="file" class="form-control" id="charityImage" name="charity_image">
            </div>

            <div class="mb-3">
                <label for="charityLink" class="form-label">Charity Link <span class="text-danger">*</span></label>
                <input type="url" class="form-control" id="charityLink" name="charity_link" required>
            </div>

            <button type="submit" name="funeral_submit_button" class="btn btn-primary"><?php echo $is_edit ? 'Update' : 'Submit'; ?></button>
        </form>
    </div>
</div>