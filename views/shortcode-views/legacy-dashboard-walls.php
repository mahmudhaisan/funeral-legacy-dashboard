<?php

// Get the current post ID
$post_id = get_the_ID();

$args = array(
    'post_type'      => 'legacy-wall',
    'post_status'    => 'publish', // Change to 'publish' to get all published posts
    'meta_query'     => array(
        array(
            'key'     => 'legacy_dashboard_id',
            'value'   => $post_id,
            'compare' => '=',
        ),
    ),
    'posts_per_page' => 3, // Initially load 2 videos

);

$published_legacy_wall_posts = get_posts($args);


?>

<div class="container my-5">
    <h1 class="text-center mb-5">Legacy Wall</h1>
    <input id="legacy-wall-id" type="hidden" value="<?php echo $post_id; ?>">

    <!-- Image Gallery -->
    <div class="row g-4 legacy-walls">

        <?php foreach ($published_legacy_wall_posts as $post) : setup_postdata($post); ?>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo esc_html($post->post_title); ?></h5>
                        <p class="card-text"><?php echo mb_substr(wp_strip_all_tags($post->post_content), 0, 100) . '...'; ?></p>
                        <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php wp_reset_postdata(); ?>


    </div>


    <div class="center-button text-center mt-5 no-more-wall-post">


    </div>
    <!-- Centered Button -->
    <div class="center-button text-center mt-5">
        <?php
        // Get the current URL and append the necessary parameters for adding a new photo
        $current_url = home_url();
        $new_url = $current_url . '/upload-legacy-gallery/';
        $post_id = get_the_ID();
        ?>

        <button id="load-more-walls" class="btn btn-primary text-white mb-3" data-page="1">Load More</button>
        <a href="<?php echo $new_url; ?>?add-legacy-parts&action=wall&post-id=<?php echo $post_id; ?>" class="btn btn-primary text-white mb-3">Add New Wall</a>
    </div>
</div>