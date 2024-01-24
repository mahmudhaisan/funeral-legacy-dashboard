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
    if (in_array('local_admin', $user->roles)) {
        // Create an array for the first new menu item - Add Funeral Personality
        $new_item_funeral = array('add-funeral-personality' => __('Add Funeral Personality', 'your-text-domain'));
        // Insert the first new menu item before the 'Log out' item
        $items = array_slice($items, 0, -1, true) + $new_item_funeral + array_slice($items, -1, null, true);
        // Create an array for the second new menu item - Add Admin Assistant
        $new_item_another = array('add-admin-assistant' => __('Add Admin Assistant', 'your-text-domain'));
        // Insert the second new menu item before the 'Log out' item
        $items = array_slice($items, 0, -1, true) + $new_item_another + array_slice($items, -1, null, true);
    }

    return $items;
}

add_filter('woocommerce_account_menu_items', 'flw_add_custom_menu_items');


/**
 * Register new endpoints
 */
function add_custom_endpoints()
{
    add_rewrite_endpoint('add-funeral-personality', EP_PAGES);
    add_rewrite_endpoint('add-admin-assistant', EP_PAGES);
}
add_action('init', 'add_custom_endpoints');



/**
 * Add content to the new endpoints
 */
function add_funeral_personality_content()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['funeral_submit_button'])) {
        // Check if the current user has any published posts of the specified post type
        $user_id = get_current_user_id();
        $args = array(
            'post_type' => 'legacy-funeral',
            'post_status' => 'publish',
            'author' => $user_id,
            'posts_per_page' => 1, // Retrieve only one post
        );

        $existing_posts = get_posts($args);

        if (empty($existing_posts)) {
          
            $person_name = sanitize_text_field($_POST["persons_name"]);
            $short_description = sanitize_text_field($_POST["short_description"]);
            $obituary = sanitize_textarea_field($_POST["obituary"]);
            $music_link = esc_url($_POST["music_link"]);

            $service_date = sanitize_text_field($_POST["service_date"]);
            $service_time = sanitize_text_field($_POST["service_time"]);
            $service_location = sanitize_text_field($_POST["service_location"]);
            $service_phone_number = sanitize_text_field($_POST["service_phoneNumber"]);
            $dress_attire = sanitize_text_field($_POST["dress_attire"]);
            $favorite_flowers = sanitize_text_field($_POST["favorite_flowers"]);
            $religion_observed = sanitize_text_field($_POST["religion_observed"]);
            $live_streamed_link = sanitize_text_field($_POST["live_streamed_link"]);

            $charity_name = sanitize_text_field($_POST["charity_name"]);
            $charity_details = sanitize_textarea_field($_POST["charity_details"]);
            $charity_link = esc_url($_POST["charity_link"]);


            // Person's Image
            $person_image = $_FILES["persons_image"];


            // Charity Image
            $charity_image = $_FILES["charity_image"];

            // Handle uploaded images and add to media library
            $upload_dir = wp_upload_dir();

            // Handle Person's Image
            $person_image_attachment_id = upload_image_to_media_library($person_image, $upload_dir);

            $new_post = array(
                'post_title'   => $person_name,
                'post_status'  => 'publish',
                'post_type'    => 'legacy-funeral',
            );

            $post_id = wp_insert_post($new_post);


            if ($post_id) {



                set_post_thumbnail($post_id, $person_image_attachment_id);


                // person info
                update_field('funeral_person_short_description', $short_description, $post_id);
                update_field('funeral_obituary', $obituary, $post_id);
                update_field('funeral_music_link', $music_link, $post_id);

                // funeral service
                update_field('funeral_service_date', $service_date, $post_id);
                update_field('funeral_service_time', $service_time, $post_id);
                update_field('funeral_service_location', $service_location, $post_id);
                update_field('funeral_service_phone_number', $service_phone_number, $post_id);
                update_field('funeral_service_dress_attire', $dress_attire, $post_id);
                update_field('funeral_service_favorite_flowers', $favorite_flowers, $post_id);
                update_field('funeral_service_religion_observed', $religion_observed, $post_id);
                update_field('funeral_service_live_streamed_details',  $live_streamed_link, $post_id);

                //charity
                update_field('charity_name', $charity_name, $post_id);
                update_field('charity_details', $charity_details, $post_id);
                update_field('charity_link', $charity_link, $post_id);

                // Post created successfully
                echo "Funeral post created successfully!";
            }
        } else {
            echo 'You have already a funeral personality added. You can edit it here.';
        }
    }

    require_once FLW_PLUGINS_PATH . 'templates/views/add-funeral-personality.php';
}



add_action('woocommerce_account_add-funeral-personality_endpoint', 'add_funeral_personality_content');



function upload_image_to_media_library($image_data, $upload_dir)
{
    // Set the file name and path in the uploads directory
    $target_file = $upload_dir['path'] . '/' . basename($image_data["name"]);



    print_r($image_data);

    // Move the uploaded image to the uploads directory
    $upload_result = wp_upload_bits($image_data["name"], null, file_get_contents($image_data["tmp_name"]));

    // Check if the image upload was successful
    if (!$upload_result['error']) {

        // Set up the attachment data
        $attachment = array(
            'post_title'     => sanitize_file_name($image_data['name']),
            'post_mime_type' => $image_data['type'],
            'post_status'    => 'inherit',
        );

        // Insert the attachment into the media library
        $attachment_id = wp_insert_attachment($attachment, $target_file);

        return $attachment_id;
    } else {
        // Handle the case when the image upload fails
        echo "Error uploading image: " . $upload_result['error'] . "<br>";
        return ''; // Return an empty string or handle the error as needed
    }
}



function add_admin_assistant_content()
{
    // Your page content goes here
    echo '<h1>Add Admin Assistant</h1>';
    echo '<p>This is the content of the "Add Admin Assistant" page.</p>';
}
add_action('woocommerce_account_add-admin-assistant_endpoint', 'add_admin_assistant_content');
