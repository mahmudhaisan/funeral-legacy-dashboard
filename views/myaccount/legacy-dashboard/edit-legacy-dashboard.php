<?php


update_legacy_dashboard();

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

    $charity_image = get_field('charity_image', $post_id);

    $charity_details = !empty(get_field('charity_details', $post_id)) ? get_field('charity_details', $post_id) : '';
    $charity_link = !empty(get_field('charity_link', $post_id)) ? get_field('charity_link', $post_id) : '';


    $service_date = date('Y-m-d', strtotime(str_replace('/', '-', $service_date)));
    $service_time = DateTime::createFromFormat('h:i A', $service_time)->format('H:i');
}


?>
<div class="add-funeral-personality">
    <div class="container">
        <h2>Update Legacy Dashboard</h2>
        <form method="post" action="" enctype="multipart/form-data">

            <!-- Person's Name -->
            <div class="mb-3">
                <label for="personsName" class="form-label">Person's Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="personsName" name="persons_name" value="<?php echo esc_attr($person_name); ?>">
            </div>

            <!-- Person's Image -->
            <div class="mb-3">
                <label for="personsImage" class="form-label">Person's Image <span class="text-danger">*</span></label>

                <div class="mt-3 mb-3">
                    <?php if (has_post_thumbnail($post_id)) : ?>
                        <?php $featured_image_url = get_the_post_thumbnail_url($post_id); ?>
                        <img width="200" height="200" src="<?php echo esc_url($featured_image_url); ?>" alt="Featured Image">
                    <?php endif; ?>
                </div>
                <input type="file" class="form-control" id="personsImage" name="persons_image" required>
            </div>

            <!-- Short Description -->
            <div class="mb-3">
                <label for="shortDescription" class="form-label">Short Description <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="shortDescription" name="short_description" value="<?php echo esc_attr($short_description); ?>" required>
            </div>

            <!-- Obituary -->
            <div class="mb-3">
                <label for="obituary" class="form-label">Obituary <span class="text-danger">*</span></label>
                <textarea class="form-control" id="obituary" name="obituary" rows="4" required><?php echo esc_textarea($obituary); ?></textarea>
            </div>

            <!-- Music Link -->
            <div class="mb-3">
                <label for="musicLink" class="form-label">Music Link <span class="text-danger">*</span></label>
                <input type="url" class="form-control" id="musicLink" name="music_link" value="<?php echo esc_url($music_link); ?>" required>
            </div>

            <!-- Funeral Service Date -->
            <div class="mb-3">
                <label for="serviceDate" class="form-label">Funeral Service Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="serviceDate" name="service_date" value="<?php echo $service_date; ?>" required>
            </div>

            <!-- Funeral Service Time -->
            <div class="mb-3">
                <label for="serviceTime" class="form-label">Funeral Service Time <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="serviceTime" name="service_time" value="<?php echo esc_attr($service_time); ?>" required>
            </div>

            <!-- Funeral Service Location -->
            <div class="mb-3">
                <label for="serviceLocation" class="form-label">Funeral Service Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="serviceLocation" name="service_location" value="<?php echo esc_attr($service_location); ?>" required>
            </div>

            <!-- Funeral Service Phone Number -->
            <div class="mb-3">
                <label for="servicePhoneNumber" class="form-label">Funeral Service Phone Number <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="servicePhoneNumber" name="service_phoneNumber" value="<?php echo esc_attr($service_phone_number); ?>" required>
            </div>

            <!-- Funeral Service Dress Attire -->
            <div class="mb-3">
                <label for="dressAttire" class="form-label">Funeral Service Dress Attire <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="dressAttire" name="dress_attire" value="<?php echo esc_attr($dress_attire); ?>" required>
            </div>

            <!-- Funeral Service Favorite Flowers -->
            <div class="mb-3">
                <label for="favoriteFlowers" class="form-label">Funeral Service Favorite Flowers <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="favoriteFlowers" name="favorite_flowers" value="<?php echo esc_attr($favorite_flowers); ?>" required>
            </div>

            <!-- Funeral Service Religion Observed -->
            <div class="mb-3">
                <label for="religionObserved" class="form-label">Funeral Service Religion Observed <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="religionObserved" name="religion_observed" value="<?php echo esc_attr($religion_observed); ?>" required>
            </div>

            <!-- Funeral Service Live Streamed Details -->
            <div class="mb-3">
                <label for="liveStreamed" class="form-label"> Funeral Service Live Streamed Details <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="liveStreamed" name="live_streamed_link" value="<?php echo esc_url($live_streamed_link); ?>" required>
            </div>

            <!-- Charity Name -->
            <div class="mb-3">
                <label for="charityName" class="form-label">Charity Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="charityName" name="charity_name" value="<?php echo esc_attr($charity_name); ?>" required>
            </div>

            <!-- Charity Details -->
            <div class="mb-3">
                <label for="charityDetails" class="form-label">Charity Details <span class="text-danger">*</span></label>
                <textarea class="form-control" id="charityDetails" name="charity_details" rows="4"><?php echo esc_textarea($charity_details); ?></textarea>
            </div>

            <!-- Charity Image - Image -->
            <div class="mb-3">
                <label for="charityImage" class="form-label">Charity Image <span class="text-danger">*</span></label>
                <div class="mt-3 mb-3">

                    <?php if (isset($charity_image)) : ?>
                        <img width="200" height="200" src="<?php echo esc_url($charity_image); ?>" alt="Featured Image">
                    <?php endif; ?>
                </div>
                <input type="file" class="form-control" id="charityImage" name="charity_image">
            </div>

            <!-- Charity Link -->
            <div class="mb-3">
                <label for="charityLink" class="form-label">Charity Link <span class="text-danger">*</span></label>
                <input type="url" class="form-control" id="charityLink" name="charity_link" value="<?php echo esc_url($charity_link); ?>" required>
            </div>

            <button type="submit" name="funeral_update_button" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>