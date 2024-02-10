<?php

function flw_custom_template_redirect($template)
{
    if (is_post_type_archive('legacy-funeral')) {
        // $template = FLW_PLUGINS_PATH . 'templates/archive-legacy-funeral.php';
    }
    return $template;
}
add_filter('template_include', 'flw_custom_template_redirect');



function flw_custom_single_template($single_template)
{
    global $post;
    if ($post->post_type == 'legacy-funeral') {
        // $single_template = FLW_PLUGINS_PATH . 'templates/single-legacy-funeral.php';
    }

    return $single_template;
}
add_filter('single_template', 'flw_custom_single_template');








add_shortcode('legacy_dashboard_photos', 'flw_legacy_dashboard_photos');

function flw_legacy_dashboard_photos()
{
    include_once FLW_PLUGINS_PATH . 'views/shortcode-views/legacy-dashboard-photos.php';
}
add_shortcode('add_legacy_dashboard_parts', 'flw_upload_legacy_parts');











add_shortcode('legacy_dashboard_walls', 'flw_legacy_dashboard_walls');

function flw_legacy_dashboard_walls()
{
    include_once FLW_PLUGINS_PATH . 'views/shortcode-views/legacy-dashboard-walls.php';
}






add_shortcode('legacy_dashboard_videos', 'flw_legacy_dashboard_videos');

function flw_legacy_dashboard_videos()
{
    include_once FLW_PLUGINS_PATH . 'views/shortcode-views/legacy-dashboard-videos.php';
}



add_shortcode('add_legacy_dashboard_parts', 'flw_upload_legacy_parts');

function flw_upload_legacy_parts()
{

    if (isset($_GET['add-legacy-parts']) && isset($_GET['post-id'])) {
        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'photos':
                include_once FLW_PLUGINS_PATH . 'views/shortcode-views/add-legacy-photos.php';
                break;
            case 'wall':
                include_once FLW_PLUGINS_PATH . 'views/shortcode-views/add-legacy-walls.php';
                // Add your wall-related logic here
                break;
            case 'video':
                include_once FLW_PLUGINS_PATH . 'views/shortcode-views/add-legacy-videos.php';
                // Add your wall-related logic here
                break;
            default:
                echo '<h1> Sorry, Nothing Found </h1>';
        }
    } else {
        echo '<h1> Sorry, Nothing Found </h1>';
    }
}


include_once FLW_PLUGINS_PATH . '/includes/frontend/myaccount/myaccount.php';
