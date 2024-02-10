<?php
// Add a custom dashboard page to the admin menu
function funeral_dashboard_menu_page()
{
    add_menu_page(
        'Funeral Dashboard',
        'Funeral Dashboard',
        'manage_options',
        'funeral_dashboard_page',
        'render_funeral_dashboard_page'
    );
}
add_action('admin_menu', 'funeral_dashboard_menu_page');

// Render the custom dashboard page
function render_funeral_dashboard_page()
{
?>
    <div class="wrap">
        <h2>Funeral Dashboard</h2>
    </div>
<?php
}


// Add custom post type: Legacy Funeral
function create_legacy_funeral_post_type()
{
    $labels = array(
        'name'               => _x('Legacy Funeral', 'post type general name', 'your-text-domain'),
        'singular_name'      => _x('Legacy Funeral', 'post type singular name', 'your-text-domain'),
        'menu_name'          => _x('Legacy Funeral', 'admin menu', 'your-text-domain'),
        'name_admin_bar'     => _x('Legacy Funeral', 'add new on admin bar', 'your-text-domain'),
        'add_new'            => _x('Add New', 'legacy funeral', 'your-text-domain'),
        'add_new_item'       => __('Add New Legacy Funeral', 'your-text-domain'),
        'new_item'           => __('New Legacy Funeral', 'your-text-domain'),
        'edit_item'          => __('Edit Legacy Funeral', 'your-text-domain'),
        'view_item'          => __('View Legacy Funeral', 'your-text-domain'),
        'all_items'          => __('All Legacy Funerals', 'your-text-domain'),
        'search_items'       => __('Search Legacy Funerals', 'your-text-domain'),
        'parent_item_colon'  => __('Parent Legacy Funerals:', 'your-text-domain'),
        'not_found'          => __('No legacy funerals found.', 'your-text-domain'),
        'not_found_in_trash' => __('No legacy funerals found in Trash.', 'your-text-domain'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'legacy-funeral'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'thumbnail'),
    );

    register_post_type('legacy-funeral', $args);
}

add_action('init', 'create_legacy_funeral_post_type');




// Add custom post type
function create_legacy_wall_post_type()
{
    $labels = array(
        'name'               => _x('Legacy Wall', 'post type general name', 'your-text-domain'),
        'singular_name'      => _x('Legacy Wall', 'post type singular name', 'your-text-domain'),
        'menu_name'          => _x('Legacy Wall', 'admin menu', 'your-text-domain'),
        'name_admin_bar'     => _x('Legacy Wall', 'add new on admin bar', 'your-text-domain'),
        'add_new'            => _x('Add New', 'legacy wall', 'your-text-domain'),
        'add_new_item'       => __('Add New Legacy Wall', 'your-text-domain'),
        'new_item'           => __('New Legacy Wall', 'your-text-domain'),
        'edit_item'          => __('Edit Legacy Wall', 'your-text-domain'),
        'view_item'          => __('View Legacy Wall', 'your-text-domain'),
        'all_items'          => __('All Legacy Walls', 'your-text-domain'),
        'search_items'       => __('Search Legacy Walls', 'your-text-domain'),
        'parent_item_colon'  => __('Parent Legacy Walls:', 'your-text-domain'),
        'not_found'          => __('No legacy walls found.', 'your-text-domain'),
        'not_found_in_trash' => __('No legacy walls found in Trash.', 'your-text-domain')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'legacy-wall'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor'),
    );

    register_post_type('legacy-wall', $args);
}

add_action('init', 'create_legacy_wall_post_type');










// Add custom post type: Legacy Photos
function create_legacy_photos_post_type()
{
    $labels = array(
        'name'               => _x('Legacy Photos', 'post type general name', 'your-text-domain'),
        'singular_name'      => _x('Legacy Photo', 'post type singular name', 'your-text-domain'),
        'menu_name'          => _x('Legacy Photos', 'admin menu', 'your-text-domain'),
        'name_admin_bar'     => _x('Legacy Photos', 'add new on admin bar', 'your-text-domain'),
        'add_new'            => _x('Add New', 'legacy photo', 'your-text-domain'),
        'add_new_item'       => __('Add New Legacy Photo', 'your-text-domain'),
        'new_item'           => __('New Legacy Photo', 'your-text-domain'),
        'edit_item'          => __('Edit Legacy Photo', 'your-text-domain'),
        'view_item'          => __('View Legacy Photo', 'your-text-domain'),
        'all_items'          => __('All Legacy Photos', 'your-text-domain'),
        'search_items'       => __('Search Legacy Photos', 'your-text-domain'),
        'parent_item_colon'  => __('Parent Legacy Photos:', 'your-text-domain'),
        'not_found'          => __('No legacy photos found.', 'your-text-domain'),
        'not_found_in_trash' => __('No legacy photos found in Trash.', 'your-text-domain'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'legacy-photos'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'thumbnail'),
    );

    register_post_type('legacy-photos', $args);
}

add_action('init', 'create_legacy_photos_post_type');







// Add custom post type: Legacy Videos
function create_legacy_videos_post_type()
{
    $labels = array(
        'name'               => _x('Legacy Videos', 'post type general name', 'your-text-domain'),
        'singular_name'      => _x('Legacy Video', 'post type singular name', 'your-text-domain'),
        'menu_name'          => _x('Legacy Videos', 'admin menu', 'your-text-domain'),
        'name_admin_bar'     => _x('Legacy Videos', 'add new on admin bar', 'your-text-domain'),
        'add_new'            => _x('Add New', 'legacy video', 'your-text-domain'),
        'add_new_item'       => __('Add New Legacy Video', 'your-text-domain'),
        'new_item'           => __('New Legacy Video', 'your-text-domain'),
        'edit_item'          => __('Edit Legacy Video', 'your-text-domain'),
        'view_item'          => __('View Legacy Video', 'your-text-domain'),
        'all_items'          => __('All Legacy Videos', 'your-text-domain'),
        'search_items'       => __('Search Legacy Videos', 'your-text-domain'),
        'parent_item_colon'  => __('Parent Legacy Videos:', 'your-text-domain'),
        'not_found'          => __('No legacy videos found.', 'your-text-domain'),
        'not_found_in_trash' => __('No legacy videos found in Trash.', 'your-text-domain'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'legacy-videos'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title','thumbnail',),
    );

    register_post_type('legacy-videos', $args);
}




add_action('init', 'create_legacy_videos_post_type');
