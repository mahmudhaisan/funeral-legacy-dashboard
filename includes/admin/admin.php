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

