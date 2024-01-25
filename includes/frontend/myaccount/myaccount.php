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

    // if (in_array('local_admin', $user->roles)) {
    // Create an array for the first new menu item - Add Funeral Personality
    $new_item_funeral_list = array('legacy-dashboard' => __('Legacy Dashboard', 'your-text-domain'));

    $items = array_slice($items, 0, -1, true) +  $new_item_funeral_list + array_slice($items, -1, null, true);


    // Create an array for the second new menu item - Add Admin Assistant
    $new_item_another = array('add-admin-assistant' => __('Add Admin Assistant', 'your-text-domain'));
    // Insert the second new menu item before the 'Log out' item
    $items = array_slice($items, 0, -1, true) + $new_item_another + array_slice($items, -1, null, true);
    // }

    return $items;
}

add_filter('woocommerce_account_menu_items', 'flw_add_custom_menu_items');


/**
 * Register new endpoints
 */
function add_custom_endpoints()
{


    add_rewrite_endpoint('legacy-dashboard', EP_PAGES);
    add_rewrite_endpoint('add-admin-assistant', EP_PAGES);
}
add_action('init', 'add_custom_endpoints');



function funeral_personality_list()
{

    if (isset($_GET['trashed-funeral-dashboard'])) {
        require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-dashboard-trashed.php';
    } elseif (isset($_GET['published-funeral-dashboard']) && isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'delete':
                if (isset($_GET['post_id'])) {
                    require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-dashboard-published.php';
                } else {
                    // Handle the case where 'post_id' is not set for editing
                    echo 'Invalid request for trashing. Post ID is missing.';
                }
                break;
            case 'edit':
                // Assuming you have 'post_id' set when editing an existing record
                if (isset($_GET['post_id'])) {
                    require_once FLW_PLUGINS_PATH . 'views/myaccount/edit-legacy-dashboard.php';
                } else {
                    // Handle the case where 'post_id' is not set for editing
                    echo 'Invalid request for editing. Post ID is missing.';
                }
                break;
            case 'add':
                require_once FLW_PLUGINS_PATH . 'views/myaccount/add-legacy-dashboard.php';
                break;

            case 'add-admin-assistant':
                require_once FLW_PLUGINS_PATH . 'views/myaccount/add-admin-assistant.php';
                break;

            case 'all-admin-assistants':
                require_once FLW_PLUGINS_PATH . 'views/myaccount/all-admin-assistants.php';
                break;


            default:
                // If action is none of 'delete', 'edit', nor 'add', fall back to the default view
                require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-dashboard-published.php';
                break;
        }
    } else {
        require_once FLW_PLUGINS_PATH . 'views/myaccount/legacy-dashboard-published.php';
    }
}



add_action('woocommerce_account_legacy-dashboard_endpoint', 'funeral_personality_list');



function add_admin_assistant_content()
{
    // Your page content goes here
    echo '<h1>Add Admin Assistant</h1>';
    echo '<p>This is the content of the "Add Admin Assistant" page.</p>';
}
add_action('woocommerce_account_add-admin-assistant_endpoint', 'add_admin_assistant_content');
