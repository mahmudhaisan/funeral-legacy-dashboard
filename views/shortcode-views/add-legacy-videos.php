<?php

// Step 1: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_legacy_video_form'])) {
    handleLegacyVideoFormSubmission();
}

// Step 2: Display Legacy Form
displayLegacyVideoForm();

function displayLegacyVideoForm() {


    $post_id = $_GET['post-id'];
    $post_title = get_the_title($post_id);

    // Display Legacy Form
    echo '<div class="container mt-5">';
    echo '<h2>Legacy Video Submission Form</h2>';
    echo '<form action="" method="post">';
    echo '<input type="hidden" name="submit_legacy_video_form" value="1">';

    // Legacy Person Name
    echo '<div class="mb-3">';
    echo '<label for="legacyName" class="form-label">Name of the Legacy Person</label>';
    echo '<input type="text" class="form-control" id="legacyName" name="legacy_dashboard_person_name" value="' . $post_title . '" required readonly>';
    echo '</div>';

    // Your Name
    echo '<div class="mb-3">';
    echo '<label for="name" class="form-label">Your Name</label>';
    echo '<input type="text" class="form-control" id="submittedUsername" name="submitted_user_name" required>';
    echo '</div>';

    // Your Email Address
    echo '<div class="mb-3">';
    echo '<label for="email" class="form-label">Your Email Address</label>';
    echo '<input type="email" class="form-control" id="email" name="submitted_user_email" required>';
    echo '</div>';

    // Write Wall Title
    echo '<div class="mb-3">';
    echo '<label for="writeShort" class="form-label">Video Title</label>';
    echo '<input type="text" class="form-control" id="writeShortTitle" name="submitted_user_legacy_video_title" required>';
    echo '</div>';

    // Youtube Video Link
    echo '<div class="mb-3">';
    echo '<label for="writeShort" class="form-label">Youtube Video Link</label>';
    echo '<input type="url" class="form-control" id="addYoutubeLink" name="submitted_user_legacy_youtube_link" required>';
    echo '</div>';

    // Submit Button
    echo '<button type="submit" class="btn btn-primary">Submit Legacy Video </button>';
    echo '</form>';
    echo '</div>';
}
?>
