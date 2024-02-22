<?php

$legacy_video_per_page = get_option('legacy_video_per_page', 3);

// Get the current post ID
$post_id = get_the_ID();



$args = array(
    'post_type'      => 'legacy-videos',
    'post_status'    => 'publish',
    'meta_query'     => array(
        array(
            'key'     => 'legacy_dashboard_id',
            'value'   => $post_id,
            'compare' => '=',
        ),
    ),
    'posts_per_page' => $legacy_video_per_page, // Initially load 2 videos

);

$published_legacy_video_posts = new WP_Query($args);





?>

<div class="container my-5">
    <h1 class="text-center mb-5">Legacy video</h1>
    <input id="legacy-video-id" type="hidden" value="<?php echo $post_id; ?>">

    <?php
    if ($published_legacy_video_posts->have_posts()) :
    ?>

        <!-- Video -->
        <div class="row g-4 legacy-videos">
            <?php while ($published_legacy_video_posts->have_posts()) : $published_legacy_video_posts->the_post(); ?>
                <div class="col-md-3">
                    <div class="card">
                        <?php
                        // Get the YouTube link from the ACF field
                        $youtube_link = get_field('legacy_video_submitted_yotube_link', get_the_ID(), true);

                        // Extract video ID from the YouTube link
                        $video_id = get_youtube_video_id($youtube_link);

                        // Embed the video using <iframe>
                        if ($video_id) {
                            $embed_code = '<iframe width="100%" height="200" src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allowfullscreen></iframe>';
                            echo $embed_code;
                        } else {
                            echo 'Invalid YouTube link.';
                        }
                        ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="center-button text-center mt-5 no-more-video-text">


        </div>

       
    <?php
    else :
        echo 'No Videos found.';
    endif;
    ?>
     <!-- Load More Button -->
     <div class="center-button text-center mt-3">
                <?php
                // Get the current URL and append the necessary parameters for adding a new photo
                $current_url = home_url();
                $new_url = $current_url . '/upload-legacy-gallery/';
                ?>
            
            <button id="load-more-videos" class="btn btn-primary text-white mb-3" data-page="1">Load More</button>
            <a href="<?php echo $new_url; ?>?add-legacy-parts&action=video&post-id=<?php echo $post_id; ?>" class="btn btn-primary text-white mb-3">Add New video</a>

        </div>


    <?php wp_reset_postdata(); ?>
</div>
