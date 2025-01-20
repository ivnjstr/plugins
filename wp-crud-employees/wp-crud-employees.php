<?php
/*
 * Plugin Name:	WP Employess CRUD
 * Description: This plugin performs CRUD Operations with Employees Table. Also an Activation it will create a dynamic wordpress page and it will have a shortcode
 * Version: 1.0
 * Author URI: https://author.example.com/
 * Plugin URI: https://example.com/plugins/the-basics/
 */

 // Defined plugin
 // Exit if accessed directly
 if(!defined("ABSPATH")){
    exit;
 } 

 // first we want to create a dynamic table inside our database
 // note ung ginawa natin na csv-data-uploader na plugin ung dynamic table.

 // NOTE this plugin is all about CLASS BASED APPROACH
 // Now create a file MyEmployees.php

//another defined
 define("WCE_DIR_PATH", plugin_dir_path(__FILE__)); // Filesystem path (server-side) like employee.php
 define("WCE_DIR_URL", plugin_dir_url(__FILE__)); // URL path (browser-accessible) > for CSS, JS, images

 //include MyEmployees.php class inside this plugin main file
 include_once WCE_DIR_PATH . "MyEmployees.php";

 //Create Class Object
 // Check if the class exists before creating an object
 if (class_exists('MyEmployees')) {
        $employeeObject = new MyEmployees;
 }
        //after creating object we can use this object to call any method from our class file
        //first call register activation hook means by help of this function dynamically we'll create a table inside our database

 //Create DB Table 
 register_activation_hook(__FILE__, [$employeeObject, "callPluginActivationFunctions"]);
 // ang ilalagay natin callback function is not a normal function
 // ung method na na ing create natin which is ung MyEmployees.php class > copy the callPluginActivationFunctions()
 // but instead of that us array [] inside the array we have to pass object of that class.
 // so simply [$employeeObject, "callPluginActivationFunctions"].
 // WHEN PLUGIN WILL BE ACTIVATED IT WILL CALL THIS METHOD callPluginActivationFunctions by using this OBJECT $employeeObject
 

 //Next once we deactivated the plugin we want also delete the table in Database 
 //Drop DB Table
 register_deactivation_hook(__FILE__, [$employeeObject, "dropEmployeesTable"]);


 // Register Shortcode 
 add_shortcode("wp-employee-form", [$employeeObject, "createEmployeesForm"]);


 //scripts
 add_action("wp_enqueue_scripts", [$employeeObject, "addAssetsToPlugin"]); //dito na ung iinsert mo ung css js jquery validation and etc.

 // Process ajax request     -     (only work when the User is login)
 add_action("wp_ajax_wce_add_employee", [$employeeObject, "handleAddEmployeeFormData"] );
         // "wp_ajax" is the action hook and "wce_add_employee" eto ung value name ng hidden input.
         // now add plublic function sa MyEmployee.php  
 add_action("wp_ajax_wce_load_employees_data", [$employeeObject, "handleLoadEmloyeesData"]);
         // ajax request to read the data inside the List Employee
         //  now add plublic function sa MyEmployee.php  
 add_action("wp_ajax_wce_delete_employee",[$employeeObject, "handleDeleteEmloyeesData"]);
 add_action("wp_ajax_wce_get_employee_data", [$employeeObject, "handleToGetSingleEmloyeesData"]);
        //add public function
 //for edit submit form
 add_action("wp_ajax_wce_edit_employee", [$employeeObject, "handleUpdateEmployeeData"]);

 
// // Process ajax request     -     (No login of wordpress)
//  add_action("wp_ajax_nopriv_wce_add_employee", [$employeeObject, "handleAddEmployeeFormData"] );
//  // "wp_ajax" is the action hook and "wce_add_employee" eto ung value name ng hidden input.
//  // now add plublic function sa MyEmployee.php  
// add_action("wp_ajax_nopriv_wce_load_employees_data", [$employeeObject, "handleLoadEmloyeesData"]);
//  // ajax request to read the data inside the List Employee
//  //  now add plublic function sa MyEmployee.php  
// add_action("wp_ajax_nopriv_wce_delete_employee",[$employeeObject, "handleDeleteEmloyeesData"]);
// add_action("wp_ajax_nopriv_wce_get_employee_data", [$employeeObject, "handleToGetSingleEmloyeesData"]);
// //add public function