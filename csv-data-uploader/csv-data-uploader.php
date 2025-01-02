<?php


/*
 * Plugin Name:	CSV Data Uploader
 * Description: This  is my third Plugin will upload csv data to db table.
 * Author: John Smith
 * Version: 1.0
 * Author URI: https://author.example.com/
 * Plugin URI: https://example.com/plugins/the-basics/
 */


 define("CDU_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));

 add_shortcode("csv-data-uploader", "cdu_display_uploader_form");

 function cdu_display_uploader_form(){
    // Start PHP Buffer
    ob_start();
    include_once CDU_PLUGIN_DIR_PATH. "/template/cdu_form.php"; // put all the content into buffer
    // Read buffer
    $template = ob_get_contents();
    //clean buffer
    ob_end_clean();
    return $template;
 }

// we have to use action hook of wordpress by using a wordpress function

//DB table on plugin Activation
// and the function name is something called register activation hook
register_activation_hook(__FILE__, "cdu_create_table");

function cdu_create_table(){
    // we use global

    global $wpdb;
    $table_prefix = $wpdb->prefix; // wp_
    $table_name = $table_prefix ."students_data";   // this is the name for your created table dynamic wp_students_data

    $table_collate = $wpdb->get_charset_collate();

    $sql_command = "
    CREATE TABLE `".$table_name."` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(50) DEFAULT NULL,
    `email` varchar(50) DEFAULT NULL,
    `age` int DEFAULT NULL,
    `phone` varchar(30) DEFAULT NULL,
    `photo` varchar(120) DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ".$table_collate."
    
    ";   // now we have create table command to create a dynamic table inside database now the this is that how to execute to execute we have to call a function that will be a wordpress function called DB delta.
    // AND THIS FUNCTION will comes from a file called upgrade.php include
    //So basically we need to access that file 

    //using this
    require_once(ABSPATH."/wp-admin/includes/upgrade.php");
    //ABSPATH means Absolute Path
    //Now once we include this file simply we can now call the function call dbDelta
    dbDelta($sql_command);

    //Now if you deactivate the plugin you'll see 12 database in the phpAdmin but when you activate the plugin it'll have 13 database because it added the dynamic database that you created the wp_students_data
}

// to add script file there are two things first all about action hook and second a wordpress function
add_action("wp_enqueue_scripts", "cdu_add_script_file");

function cdu_add_script_file(){
    // we have to use one more WP function called wp_enqueue_script eto walang s 
    // note this a function name wp_enqueue_script ung sa taas action hook ung with s wp_enqueue_scripts

    wp_enqueue_script("cdu-script-js", plugin_dir_url(__FILE__) . "assets/script.js", array("jquery")); //ung unang paramerter is uniQUE id lang ha, the ung second source file path.  ung last naman meaning ung script day ay nakadepende lang sa jquery. Jquery is going to by default privided by wordpress

    // now check mo sa view source code kung nandun ung unique id mo na cdu-script-js
    // this is the link source for scripts http://myfirstplugincustom.test/wp-content/plugins/csv-data-uploader/assets/script.js?ver=6.7.1

    // note plugin_dir_url(__FILE__) returns the http://myfirstplugincustom.test/wp-content/plugins/csv-data-uploader/
    // while the "assets/script.js" return the "assets/script.js" to access the script.js

    wp_localize_script("cdu-script-js", "cdu_object", array( // TO GET THE URL
        "ajax_url" => admin_url("admin-ajax.php")
    ));
}

//Capture Ajax request
add_action("wp_ajax_cdu_submit_form_data", "cdu_ajax_handler"); // When user log in

//this will be the action name nag add ako sa form ng isang input na hidden
// NOTE nag add ako ng action att sa form if i submit that i cacapture nito "wp_ajax_cdu_submit_form_data", "cdu_ajax_handler"
// and also by the help of that you need to handle that usisng callback "cdu_ajax_handler"
// Means if we have any ajax request with action att simply the callback function will work.
// BUT THIS ACTION HAVE ISSUE IT WILL WORK ONLY IF THE USER IS LOGGED IN BUT IF THE USER LOGGEDOUT AND IT TRY TO UPLOAD IT'LL NOT GOING TO WORK. TO FIX THE ISSUE. HERE

add_action("wp_ajax_nopriv_cdu_submit_form_data", "cdu_ajax_handler"); // When user logged out

// now create a function for handler 

// function cdu_ajax_handler(){
//     if($_FILES['csv_data_file']){

//         $csvFile = $_FILES['csv_data_file']['tmp_name'];

//         if ($_FILES['csv_data_file']['error'] !== UPLOAD_ERR_OK) {
//             echo json_encode(array(
//                 "status" => 0,
//                 "message" => "Error uploading file."
//             ));
//             exit;
//         }

//         $handle = fopen($csvFile, "r");

//         global $wpdb;
//         $table_name = $wpdb->prefix."students_data";

//         if ($handle){
            
//             $row = 0;
//             while(($data = fgetcsv($handle, 1000, ",")) !==FALSE){

//                 if($row == 0){
//                     $row++;
//                     continue;
//                 }

//                 //Insert data into table
//                 $wpdb->insert($table_name, array(
//                     "name" => $data[1],
//                     "email" => $data[2] ,
//                     "age" => $data[3],
//                     "phone" => $data[4],
//                     "photo" => $data[5]

//                 ));
//             }

//             fclose($handle);

//             echo json_encode([
//                 "status" => 1,
//                 "message" => "Data uploaded Successfully!"
//             ]);
//         }
//     }else{

//         echo json_encode(array(
//             "status" => 0,
//             "message" => "Hello form CSV Data Uploader"
            
//         ));

//     }
//     exit;
// }

// Another alternation function cdu_ajax_handler
function cdu_ajax_handler() {
    if (!empty($_FILES['csv_data_file'])) {
        $csvFile = $_FILES['csv_data_file']['tmp_name'];

        if ($_FILES['csv_data_file']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(array(
                "status" => 0,
                "message" => "Error uploading file."
            ));
            exit;
        }

        $handle = fopen($csvFile, "r");
        if (!$handle) {
            echo json_encode(array(
                "status" => 0,
                "message" => "Failed to open CSV file."
            ));
            exit;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . "students_data";

        $row = 0;
        $errors = [];
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            if ($row == 0) {
                $row++;
                continue;
            }

            $result = $wpdb->insert(
                $table_name,
                array(
                    "name" => sanitize_text_field($data[1]),
                    "email" => sanitize_email($data[2]),
                    "age" => intval($data[3]),
                    "phone" => sanitize_text_field($data[4]),
                    "photo" => esc_url_raw($data[5])
                )   
                // Data Sanitization: Sanitization ensures data integrity and prevents potential security vulnerabilities.
            );

            if (false === $result) {
                $errors[] = "Row $row: " . $wpdb->last_error;
            }

            $row++;
        }

        fclose($handle);

        if (!empty($errors)) {
            echo json_encode([
                "status" => 0,
                "message" => "Some rows failed to insert.",
                "errors" => $errors
            ]);
        } else {
            echo json_encode([
                "status" => 1,
                "message" => "Data uploaded successfully!"
            ]);
        }
    } else {
        echo json_encode(array(
            "status" => 0,
            "message" => "No file uploaded."
        ));
    }
    exit;
}
