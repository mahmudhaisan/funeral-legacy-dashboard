<?php



// Hook function to create roles when the plugin is activated
function flw_create_custom_roles_on_plugin_activation()
{

    if (!get_role('local_admin')) {
        add_role(
            'local_admin',
            __('Local Admin'),
            array(
                'read' => true,
            )
        );
    }

    if (!get_role('admin_assistant')) {
        add_role(
            'admin_assistant',
            __('Admin Assistant'),
            array(
                'read' => true,
            )
        );
    }
}

add_action('init', 'flw_create_custom_roles_on_plugin_activation');



/**
 * Remove tabs from My Account page
 */
function flw_remove_my_account_tabs($tabs)
{
    unset($tabs['orders']);
    unset($tabs['downloads']);
    unset($tabs['edit-address']);
    return $tabs;
}
add_filter('woocommerce_account_menu_items', 'flw_remove_my_account_tabs', 999);

/**
 * Add menu items to My Account menu for the 'local_admin' role
 */
function flw_add_custom_menu_items($items)
{
    // Check if the current user has the 'local_admin' role
    $user = wp_get_current_user();
    $current_user_id = get_current_user_id();
    $post_type = 'legacy-funeral';

    $user_post_count = intval(count_user_posts($current_user_id, $post_type, true));


    // Create an array for the first new menu item - Add Funeral Personality
    $new_item_funeral_list = array('legacy-dashboard' => __('Legacy Dashboard', 'your-text-domain'));
    $items = array_slice($items, 0, -1, true) +  $new_item_funeral_list + array_slice($items, -1, null, true);




    if (in_array('local_admin', $user->roles) || in_array('administrator', $user->roles)) {

        $new_item_funeral_list = array('legacy-settings' => __('Leagcy Settings', 'your-text-domain'));
        $items = array_slice($items, 0, -1, true) +  $new_item_funeral_list + array_slice($items, -1, null, true);



        $new_item_funeral_list = array('legacy-admin-assistant' => __('Admin Assistant', 'your-text-domain'));
        $items = array_slice($items, 0, -1, true) +  $new_item_funeral_list + array_slice($items, -1, null, true);
    }

    // Create an array for the second new menu item - Add Admin Assistant
    $new_item_another = array('legacy-gallery' => __('Legacy Gallery', 'your-text-domain'));
    // Insert the second new menu item before the 'Log out' item
    $items = array_slice($items, 0, -1, true) + $new_item_another + array_slice($items, -1, null, true);
    // Create an array for the second new menu item - Add Admin Assistant
    $new_item_another = array('legacy-video' => __('Legacy Video', 'your-text-domain'));
    // Insert the second new menu item before the 'Log out' item
    $items = array_slice($items, 0, -1, true) + $new_item_another + array_slice($items, -1, null, true);

    // Create an array for the second new menu item - Add Admin Assistant
    $new_item_another = array('legacy-wall' => __('Legacy Wall', 'your-text-domain'));
    // Insert the second new menu item before the 'Log out' item
    $items = array_slice($items, 0, -1, true) + $new_item_another + array_slice($items, -1, null, true);

    return $items;
}

add_filter('woocommerce_account_menu_items', 'flw_add_custom_menu_items');


/**
 * Register new endpoints
 */
function add_custom_endpoints()
{
    add_rewrite_endpoint('legacy-dashboard', EP_PAGES);
    add_rewrite_endpoint('legacy-settings', EP_PAGES);
    add_rewrite_endpoint('legacy-admin-assistant', EP_PAGES);
    add_rewrite_endpoint('legacy-gallery', EP_PAGES);
    add_rewrite_endpoint('legacy-video', EP_PAGES);
    add_rewrite_endpoint('legacy-wall', EP_PAGES);
}
add_action('init', 'add_custom_endpoints');



function funeral_personality_list()
{

    if (isset($_GET['trashed-funeral-dashboard'])) {
        require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-dashboard/legacy-dashboard-trashed.php';
    } elseif (isset($_GET['published-funeral-dashboard']) && isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'delete':
                if (isset($_GET['post_id'])) {
                    require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-dashboard/legacy-dashboard-published.php';
                } else {
                    // Handle the case where 'post_id' is not set for editing
                    echo 'Invalid request for trashing. Post ID is missing.';
                }
                break;
            case 'edit':
                // Assuming you have 'post_id' set when editing an existing record
                if (isset($_GET['post_id'])) {
                    require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-dashboard/edit-legacy-dashboard.php';
                } else {
                    // Handle the case where 'post_id' is not set for editing
                    echo 'Invalid request for editing. Post ID is missing.';
                }
                break;
            case 'add':
                require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-dashboard/add-legacy-dashboard.php';
                break;



            default:
                // If action is none of 'delete', 'edit', nor 'add', fall back to the default view
                require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-dashboard/legacy-dashboard-published.php';
                break;
        }
    } else {
        require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-dashboard/legacy-dashboard-published.php';
    }
}

add_action('woocommerce_account_legacy-dashboard_endpoint', 'funeral_personality_list');


function flw_legacy_settings()

{ 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        update_option('auto_gallery_publish', isset($_POST['auto_gallery_publish']));
        update_option('auto_video_publish', isset($_POST['auto_video_publish']));
        update_option('auto_wall_publish', isset($_POST['auto_wall_publish']));
    }

    
    // Retrieve existing option values
    $auto_gallery_publish = get_option('auto_gallery_publish', false);
    $auto_video_publish = get_option('auto_video_publish', false);
    $auto_wall_publish = get_option('auto_wall_publish', false);
?>
    <div class="container">
        <h2>Toggle </h2>
        <form method="post" action="">
            <div class="mb-3 row">
                <label for="auto_gallery_publish" class="col-sm-6 col-form-label">Enable/Disable auto gallery publish </label>
                <div class="col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="auto_gallery_publish" name="auto_gallery_publish" <?php echo $auto_gallery_publish ? 'checked' : ''; ?>>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="auto_video_publish" class="col-sm-6 col-form-label">Enable/Disable auto video publish </label>
                <div class="col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="auto_video_publish" name="auto_video_publish" <?php echo $auto_video_publish ? 'checked' : ''; ?>>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="auto_wall_publish" class="col-sm-6 col-form-label">Enable/Disable auto wall publish </label>
                <div class="col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="auto_wall_publish" name="auto_wall_publish" <?php echo $auto_wall_publish ? 'checked' : ''; ?>>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
<?php 
}

add_action('woocommerce_account_legacy-settings_endpoint', 'flw_legacy_settings');




function add_legacy_gallery_content()
{

    // Get My Account page URL
    $my_account_url = wc_get_account_endpoint_url('');
    // Append 'funeral-list' endpoint to My Account URL
    $legacy_gallery_url = trailingslashit($my_account_url) . 'legacy-gallery';

    echo '<div class="container mt-4">';
    // Retrieve the success message from the URL parameter
    if (isset($_GET['success_msg'])) {
        $success_message = urldecode($_GET['success_msg']);
        echo '<div class="alert alert-success" id="successMessage">' . esc_html($success_message) . '</div>';
        // Add JavaScript to remove query parameters after they've been displayed
        echo '<script>
            // Wait for the DOM to be fully loaded
            document.addEventListener("DOMContentLoaded", function() {
                // Remove query parameters from the URL without reloading the page
                window.history.replaceState({}, document.title, window.location.pathname);
            });
          </script>';
    }


    echo '<div class="d-flex justify-content-between">';
    echo '<h3 class="mb-4">Legacy Gallery</h3>';
    echo '<p class="d-inline-block ml-2 mb-4">';
    echo '<a class="bg-primary me-2 text-white p-2 text-decoration-none" href="' . $legacy_gallery_url . '">Published Gallery</a>';
    echo '<a class="bg-primary me-2 text-white p-2 text-decoration-none" href="?gallery-view=pending">Pending Gallery</a>';
    echo '<a class="bg-primary me-2 text-white p-2 text-decoration-none" href="?gallery-view=trash">Trash Gallery</a>';
    echo '</p>';
    echo '</div>';


    // $admin_assistant_id = get_current_user_id();
    // $parent_local_admin_id = get_user_meta($admin_assistant_id, '_created_by', true);



    $parent_local_admin_id =  get_parent_local_admin_id();






    $args = array(
        'post_status' => 'publish',
        'numberposts' => 1, // Adjust the number of posts to retrieve
        'post_type'   => 'legacy-funeral',
        'author'      => $parent_local_admin_id,
    );

    $published_post_by_parent = get_posts($args);


    // print_r($published_post_by_parent);


    // Check if there is a published post
    if (!$published_post_by_parent) {

        echo 'Currently No Published Dashboard Found';
        exit;
    }

    $published_post_id_by_parent = $published_post_by_parent[0]; // Access the first (and only) post in the array

    // Get and print the post ID
    $post_id = $published_post_id_by_parent->ID;

    $gallery_view = isset($_GET['gallery-view']) ? sanitize_text_field($_GET['gallery-view']) : '';

    switch ($gallery_view) {
        case 'trash':
            require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-gallery/user-submitted-gallery-trash.php';
            break;
        case 'pending':
            require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-gallery/user-submitted-gallery-pending.php';
            break;
        default:
            require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-gallery/user-submitted-gallery-published.php';
            break;
    }
}

add_action('woocommerce_account_legacy-gallery_endpoint', 'add_legacy_gallery_content');



function flw_add_legacy_video_content()
{

    // Get My Account page URL
    $my_account_url = wc_get_account_endpoint_url('');
    // Append 'funeral-list' endpoint to My Account URL
    $legacy_video_url = trailingslashit($my_account_url) . 'legacy-video';


    echo '<div class="container mt-4">';
    // Retrieve the success message from the URL parameter
    if (isset($_GET['success_msg'])) {
        $success_message = urldecode($_GET['success_msg']);
        echo '<div class="alert alert-success" id="successMessage">' . esc_html($success_message) . '</div>';
        // Add JavaScript to remove query parameters after they've been displayed
        echo '<script>
        // Wait for the DOM to be fully loaded
        document.addEventListener("DOMContentLoaded", function() {
            // Remove query parameters from the URL without reloading the page
            window.history.replaceState({}, document.title, window.location.pathname);
        });
      </script>';
    }



    echo '<div class="d-flex justify-content-between">';
    echo '<h3 class="mb-4">Legacy Videos</h3>';
    echo '<p class="d-inline-block ml-2 mb-4">';
    echo '<a class="bg-primary me-2 text-white p-2 text-decoration-none" href="' . $legacy_video_url . '">Published Videos</a>';
    echo '<a class="bg-primary me-2 text-white p-2 text-decoration-none" href="?video-view=pending">Pending Videos</a>';
    echo '<a class="bg-primary me-2 text-white p-2 text-decoration-none" href="?video-view=trash">Trashed Videos</a>';
    echo '</p>';
    echo '</div>';




    $parent_local_admin_id =  get_parent_local_admin_id();
    $legacy_dashboard_id =  get_single_post_id_by_user_id($parent_local_admin_id, 'legacy-funeral');
    $video_view = isset($_GET['video-view']) ? ($_GET['video-view']) : '';

    if ($video_view === 'pending') {
        include FLW_PLUGINS_PATH . 'views/myaccount/legacy-video/user-submitted-video-pending.php';
    } elseif ($video_view === 'trash') {
        require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-video/user-submitted-video-trashed.php';
    } else {
        include FLW_PLUGINS_PATH . 'views/myaccount/legacy-video/user-submitted-video-published.php';
    }
}
add_action('woocommerce_account_legacy-video_endpoint', 'flw_add_legacy_video_content');




function flw_add_legacy_wall_content()
{

    // Get My Account page URL
    $my_account_url = wc_get_account_endpoint_url('');
    // Append 'funeral-list' endpoint to My Account URL
    $legacy_wall_url = trailingslashit($my_account_url) . 'legacy-wall';

    echo '<div class="container mt-4">';
    // Retrieve the success message from the URL parameter
    if (isset($_GET['success_msg'])) {
        $success_message = urldecode($_GET['success_msg']);
        echo '<div class="alert alert-success" id="successMessage">' . esc_html($success_message) . '</div>';
        // Add JavaScript to remove query parameters after they've been displayed
        echo '<script>
            // Wait for the DOM to be fully loaded
            document.addEventListener("DOMContentLoaded", function() {
                // Remove query parameters from the URL without reloading the page
                window.history.replaceState({}, document.title, window.location.pathname);
            });
          </script>';
    }

    echo '<div class="d-flex justify-content-between">';
    echo '<h3 class="mb-4">Legacy Walls</h3>';
    echo '<p class="d-inline-block ml-2 mb-4">';
    echo '<a class="bg-primary me-2 text-white p-2 text-decoration-none" href="' . $legacy_wall_url . '">Published Walls</a>';


    echo '<a class="bg-primary me-2 text-white p-2 text-decoration-none" href="?wall-view=pending">Pending Walls</a>';
    echo '<a class="bg-primary me-2 text-white p-2 text-decoration-none" href="?wall-view=trash">Trashed Walls</a>';
    echo '</p>';
    echo '</div>';


    $parent_local_admin_id =  get_parent_local_admin_id();
    $legacy_dashboard_id =  get_single_post_id_by_user_id($parent_local_admin_id, 'legacy-funeral');


    $video_view = isset($_GET['wall-view']) ? sanitize_text_field($_GET['wall-view']) : '';

    switch ($video_view) {
        case 'pending':
            require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-wall/user-submitted-wall-pending.php';
            break;
        case 'trash':
            require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-wall/user-submitted-wall-trashed.php';
            break;
        default:
            require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-wall/user-submitted-wall-published.php';
            break;
    }
}
add_action('woocommerce_account_legacy-wall_endpoint', 'flw_add_legacy_wall_content');



function flw_legacy_admin_assistant_tab_content()
{

    if (isset($_GET['published-funeral-dashboard']) && isset($_GET['action'])) {
        switch ($_GET['action']) {

            case 'add-admin-assistant':
                require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-admin-assistant/add-admin-assistant.php';
                break;

            case 'edit-admin-assistant':
                require_once FLW_PLUGINS_PATH . 'views/myaccount//edit-admin-assistant.php';
                break;

            default:
                // If action is none of 'delete', 'edit', nor 'add', fall back to the default view
                require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-admin-assistant/admin-assistant-published.php';
                break;
        }
    } else {
        require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-admin-assistant/admin-assistant-published.php';
    }
}


add_action('woocommerce_account_legacy-admin-assistant_endpoint', 'flw_legacy_admin_assistant_tab_content');
