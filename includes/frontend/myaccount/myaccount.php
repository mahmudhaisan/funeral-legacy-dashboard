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
    $new_item_funeral_list = array('funeral-list' => __('Funeral List', 'your-text-domain'));

    $items = array_slice($items, 0, -1, true) +  $new_item_funeral_list + array_slice($items, -1, null, true);



    $new_item_funeral = array('add-funeral-personality' => __('Add Funeral Personality', 'your-text-domain'));
    // Insert the first new menu item before the 'Log out' item
    $items = array_slice($items, 0, -1, true) + $new_item_funeral + array_slice($items, -1, null, true);


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


    add_rewrite_endpoint('funeral-list', EP_PAGES);
    add_rewrite_endpoint('add-funeral-personality', EP_PAGES);
    add_rewrite_endpoint('add-admin-assistant', EP_PAGES);
}
add_action('init', 'add_custom_endpoints');



/**
 * Add content to the new endpoints
 */
function add_funeral_personality_content()
{


    require_once FLW_PLUGINS_PATH . 'templates/views/myaccount/handle-add-new-personality.php';
    require_once FLW_PLUGINS_PATH . 'templates/views/myaccount/add-new-funeral-personality.php';
}



add_action('woocommerce_account_add-funeral-personality_endpoint', 'add_funeral_personality_content');



function funeral_personality_list()
{
    require_once FLW_PLUGINS_PATH . 'templates/views/myaccount/handle-funeral-list.php';

    if (isset($_GET['trashed-funeral-dashboard'])) {
        require_once FLW_PLUGINS_PATH . 'templates/views/myaccount/funeral-list-trashed.php';
    } else {
        if (isset($_GET['published-funeral-dashboard']) && isset($_GET['post_id']) && isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'delete':
                    require_once FLW_PLUGINS_PATH . 'templates/views/myaccount/funeral-list-published.php';
                    break;
                case 'edit':
                    require_once FLW_PLUGINS_PATH . 'templates/views/myaccount/edit-funeral-personality.php';
                    break;
                // Add more cases as needed
                default:
                    // Default action if none of the specified cases match
                    break;
            }
        } else {
            require_once FLW_PLUGINS_PATH . 'templates/views/myaccount/funeral-list-published.php';
        }
    }
}


add_action('woocommerce_account_funeral-list_endpoint', 'funeral_personality_list');



function add_admin_assistant_content()
{
    // Your page content goes here
    echo '<h1>Add Admin Assistant</h1>';
    echo '<p>This is the content of the "Add Admin Assistant" page.</p>';
}
add_action('woocommerce_account_add-admin-assistant_endpoint', 'add_admin_assistant_content');
