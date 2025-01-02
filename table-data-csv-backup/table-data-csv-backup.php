<?php


/*
 * Plugin Name:	CSV Data Backup
 * Description: It will export table data into .csv file
 * Author: John Smith
 * Version: 1.0
 * Author URI: https://author.example.com/
 * Plugin URI: https://example.com/plugins/the-basics/
 */

// Things todo
// Create Plugin menu in admin
// Create a page a Button Export 
// Export all table data into .csv file


// Create Plugin menu in admin
add_action("admin_menu", "tdcb_create_admin_menu");
// Admin Menu
function tdcb_create_admin_menu(){ // callback function
    // use wp function to create admin menu
    add_menu_page("CSV Data Backup Plugin", "CSV Data Backup", "manage_options", "csv-data-backup", "tdcb_export_form", "dashicons-database-export", 8);
    // ($page_title, $menu_title, $capability, $menu_slug, $callback = '', $icon_url = '', $position = null)
    // the icon link Search "Dashicon wp"
    // for more refference specially for position search  "add_menu_page wp" look for Menu structure
}

// Form Layout
function tdcb_export_form(){
    // para sa laman ng Menu.
    ob_start();
    include_once plugin_dir_path(__FILE__) . "/template/table-data-backup.php"; // put all the content into buffer
    // Read buffer
    $layout = ob_get_contents();
    //clean buffer
    ob_end_clean();
    echo $layout;
}

add_action("admin_init", "tdcb_handle_form_export");

function tdcb_handle_form_export(){
    if(isset($_POST['tdcb_export_button'])){
        global $wpdb;
        $table_name = $wpdb->prefix ."students_data";

        $students = $wpdb->get_results(
            "SELECT * FROM {$table_name}", ARRAY_A
        );
        
        if(empty($students)){
            // Error messsage
        }

        // echo "<pre>";
        // print_r($students);die;
        // print_r(array_keys($students[0]));die;  
        // BY THE HELP OF THIS WE ARE READING ALL OF THE KEYS


        //Now first lets create a file name 
        $filename = "list_data_".time().".csv";

        //Now we have a file name.
        //Used some php header so that once we write all those content inside this file also we have to download that

        header("Content-type: text/csv; charset= utf-8;");
        header("Content-Disposition: attachment; filename=".$filename);

        // we want the first row inside the csv file once we export the( id, name, email, phone etc..)
        
        $output = fopen("php://output", "w"); //second parameter meant it will be open or write mode.
        fputcsv($output, array_keys($students[0]));

        //when you print using this the resuts only the "id, name, email, phone etc."

        //Now lets get all the contents. to get...
        foreach($students as $student){
            fputcsv($output, $student);
        }
        fclose($output);

        //after getting all of those keys simply exit
        exit;
    }
}

   
