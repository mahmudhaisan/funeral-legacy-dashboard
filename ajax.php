<?php

add_action('wp_ajax_load_more_images', 'load_more_images');
add_action('wp_ajax_nopriv_load_more_images', 'load_more_images');

function load_more_images()
{
    $offset = $_POST['offset'];
    $perPage = $_POST['perPage'];
    $postId = $_POST['postId'];

    $published_gallery_images = get_post_meta($postId, 'legacy_gallery_published', true);
    $attachment_ids = explode(',', $published_gallery_images);
    $displayed_images = 0;

    $output = '';

    foreach ($attachment_ids as $attachment_id) {
        if ($displayed_images >= $offset + $perPage) {
            break;
        }
        if ($displayed_images >= $offset) {
            $output .= '<div class="col-md-3 legacy-photo">';
            $output .= wp_get_attachment_image($attachment_id, 'thumbnail'); // Adjust 'thumbnail' to your desired image size
            $output .= '</div>';
        }
        $displayed_images++;
    }

    echo $output;
    wp_die();
}





add_action('wp_ajax_load_more_videos', 'load_more_videos');
add_action('wp_ajax_nopriv_load_more_videos', 'load_more_videos');


function load_more_videos()
{
    $page = $_POST['page'];
    $post_id = $_POST['post_id'];
    $offset = $_POST['offset'];
    $posts_per_page = $_POST['postsPerPage'];

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
        'posts_per_page' => $posts_per_page,
        'offset'         => $offset,
    );

    $published_legacy_video_posts = new WP_Query($args);

    if ($published_legacy_video_posts->have_posts()) {
        while ($published_legacy_video_posts->have_posts()) {
            $published_legacy_video_posts->the_post();
            // Display each legacy video post here as per your current display logic
            // Example:
            echo '<div class="col-md-3">';
            echo '<div class="card">';

            $youtube_link = get_field('legacy_video_submitted_yotube_link', get_the_ID(), true);
            $video_id = get_youtube_video_id($youtube_link);

            if ($video_id) {
                $embed_code = '<iframe width="100%" height="200" src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allowfullscreen></iframe>';
                echo $embed_code;
            } else {
                echo 'Invalid YouTube link.';
            }

            echo '</div>';
            echo '</div>';
        }

        $total_posts = $published_legacy_video_posts->found_posts;
        $remaining_posts = $total_posts - ($page * $posts_per_page);

        // Send flag indicating whether there are more posts to load
        if ($remaining_posts <= 0) {
            echo '<div class="no-more-posts">No more posts to load.</div>';
        }
    }

    wp_die();
}



add_action('wp_ajax_load_more_walls', 'load_more_walls');
add_action('wp_ajax_nopriv_load_more_walls', 'load_more_walls');


function load_more_walls()
{
    $page = $_POST['page'];
    $post_id = $_POST['post_id'];
    $offset = $_POST['offset'];
    $posts_per_page = $_POST['postsPerPage'];

    $args = array(
        'post_type'      => 'legacy-wall',
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => 'legacy_dashboard_id',
                'value'   => $post_id,
                'compare' => '=',
            ),
        ),
        'posts_per_page' => $posts_per_page,
        'offset'         => $offset,
    );

    $published_legacy_video_posts = new WP_Query($args);

    if ($published_legacy_video_posts->have_posts()) {
        while ($published_legacy_video_posts->have_posts()) {
            $published_legacy_video_posts->the_post();
            // Display each legacy video post here as per your current display logic
            // Example:
            echo '<div class="col-md-3">';
            echo '<div class="card h-100">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . esc_html(get_the_title()) . '</h5>';
            echo '<p class="card-text">' . mb_substr(wp_strip_all_tags(get_the_content()), 0, 100) . '...</p>';
            echo '<a href="' . esc_url(get_permalink()) . '" class="btn btn-primary">Read More</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        $total_posts = $published_legacy_video_posts->found_posts;
        $remaining_posts = $total_posts - ($page * $posts_per_page);

        // Send flag indicating whether there are more posts to load
        if ($remaining_posts <= 0) {
            echo '<div class="no-more-posts">No more posts to load.</div>';
        }
    }


    wp_die();
}



add_action('wp_ajax_update_wall_post_content', 'update_post_content');
function update_post_content()
{
    if (isset($_POST['post_id']) && isset($_POST['new_content'])) {
        $post_id = intval($_POST['post_id']);
        $new_content = wp_kses_post($_POST['new_content']);

        // Update post content
        wp_update_post(array(
            'ID' => $post_id,
            'post_content' => $new_content
        ));

        // Get updated post object
        $post = get_post($post_id);

        // Output updated post content
        echo '<textarea class="form-control" id="postContent_' . $post_id . '" rows="8">' . esc_textarea($post->post_content) . '</textarea>';
    }
    wp_die(); // Always include this line to terminate the script
}
