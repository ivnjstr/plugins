<?php

/*
 * Plugin Name:       Hello world
 * Description:       This  is my First Plugin which create some information widgets to admin dashboards
 * Author:            John Smith
 * Version:           1.0
 * Author URI:        https://author.example.com/
 * Plugin URI:        https://example.com/plugins/the-basics/
 */


// Two basic concept of plugin 
 // 1. Admin Notices
add_action("admin_notices", "hw_show_success_message");
add_action("admin_notices", "hw_show_error_message");

function hw_show_success_message() {
    echo '<div class="notice notice-success is-dismissible">
        <p>Data Saved! Hello World!</p>
    </div>';
}
function hw_show_error_message() {
    echo '<div class="notice notice-error is-dismissible">
        <p>Data Saved Unsuccessfully! Hello World!</p>
    </div>';
}

// 2. Admin Dashboard
add_action("wp_dashboard_setup", "hw_hellow_world_dashboard_widgets");
function hw_hellow_world_dashboard_widgets(){
    wp_add_dashboard_widget("hw_hellow_world", "HW - Hello world Widgets", "hw_custom_admin_widgets");
}
function hw_custom_admin_widgets(){
    echo 'This is Hello World Custom admin Widgets';
}
   
 