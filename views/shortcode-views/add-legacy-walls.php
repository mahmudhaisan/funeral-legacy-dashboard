<?php

// Step 1: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_legacy_wall_form'])) {
    handleLegacyWallFormSubmission();
}

// Step 2: Display Legacy Form
displayLegacyForm();

function displayLegacyForm() {
    $post_id = $_GET['post-id'];
    $post_title = get_the_title($post_id);

    // Display Legacy Form
    echo '<div class="container mt-5">';
    echo '<h2>Legacy Wall Submission Form</h2>';
    echo '<form action="" method="post" enctype="multipart/form-data">';
    echo '<input type="hidden" name="submit_legacy_wall_form" value="1">';

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
    echo '<label for="writeShort" class="form-label">Write Wall Title</label>';
    echo '<input type="text" class="form-control" id="writeShortTitle" name="submitted_user_legacy_wall_title" required>';
    echo '</div>';

    // Write Wall
    echo '<div class="mb-3">';
    echo '<label for="writeShort" class="form-label">Write Wall</label>';
    echo '<textarea class="form-control" id="writeShort" name="submitted_user_legacy_short" rows="6" required></textarea>';
    echo '</div>';

    // Submit Button
    echo '<button type="submit" class="btn btn-primary">Submit Legacy Wall</button>';
    echo '</form>';
    echo '</div>';
}
?>
