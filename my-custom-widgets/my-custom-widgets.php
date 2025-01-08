<?php
/*
 * Plugin Name:	My Custom Widgets
 * Description: This widgets will provide options to display a static message as well as recent post over website
 * Author: John Smith
 * Version: 1.0
 * Author URI: https://author.example.com/
 * Plugin URI: https://example.com/plugins/the-basics/
 */

 if(!defined("ABSPATH")){
    exit;
 } // means this php file will only get executed if this constant will be available. and this is a wp constant make sure this is instlalled otherwise it will not execute

 //Next add action hook

 add_action("widgets_init", "mcw_register_widget"); 

 include_once plugin_dir_path(__FILE__). "/My_Custom_Widgets.php";// ilagay ang path (important)


 function mcw_register_widget(){
    // here use wp function called register widgets
    register_widget("My_Custom_Widgets"); // inside this function we need to pass the class name (unique) bt the help of that well create a widgets and manage each and everything like how to display, save and display to frontend

    // Now create another file My_Custom_Widgets.php
 }

 // Add admin panel script
 add_action("admin_enqueue_scripts", "mcw_add_admin_script");
// this action is useful when we want to attach our JS file to frontend but this time we need to attach our script.js file it means JS FIle to admin panel
// simply remove wp and replace admin. from wp_enqueue_scripts to admin_enqueue_scripts
 function mcw_add_admin_script(){
   //CSS
   wp_enqueue_style("mcw_style", plugin_dir_url(__FILE__)."/style.css");
   //JS
   wp_enqueue_script("admin-script", plugin_dir_url(__FILE__)."/script.js", array("jquery"), null, true);
 }