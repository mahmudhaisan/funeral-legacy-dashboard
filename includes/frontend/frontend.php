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






include_once FLW_PLUGINS_PATH . '/includes/frontend/myaccount/myaccount.php';