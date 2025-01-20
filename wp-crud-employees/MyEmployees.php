<?php
    class MyEmployees{
        private $wpdb; //this is the variable of class MyEmployees
        private $table_name;
        private $table_prefix;

        // Constructor method
        public function __construct()
        {
            global $wpdb; //this is the global variable of wordpress
            // so we want to initialize our private variable of private $wpdb with the global object of wp.
            // to do that simply
            $this->wpdb = $wpdb; // it means that this is the variable of class MyEmployees which private $wpdb. // also we want a table name add sa top ng private table_name;
            $this->table_prefix = $this->wpdb->prefix; // wp_
            $this->table_name = $this->wpdb->prefix . "employees_table"; // wp_employees_table
        }


        //Create DB Table + Wordpress page
        public function callPluginActivationFunctions(){
            
            $collate = $this->wpdb->get_charset_collate();
            //means kinukuha mo ung collate value unf db mo "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci"

            $createCommand = "
            CREATE TABLE `".$this->table_name."` (
            `id` int NOT NULL AUTO_INCREMENT,
            `name` varchar(50) NOT NULL,
            `email` varchar(50) DEFAULT NULL,
            `designation` varchar(50) DEFAULT NULL,
            `profile_image` varchar(220) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ".$collate."
            ";

            // Now inside this command we want to replace some values, replace table prefix ung wp_ ,Then table name , and collat value
            // to do that add sa private and public.  private $table_name; private $table_prefix;
            // $this->$wpdb->prefix; -------> this means kinukuha mo ung prefix ng db which is wp_
            // after that replace mo na.

            // Then we want to execute this create statement we have to include upgrade.php which is andun ung dbDelta na mag eexecute.
            
            // Include the upgrade library
            require_once (ABSPATH . "wp-admin/includes/upgrade.php");
            // once we linked then // Execute the SQL
            dbDelta($createCommand); 

            // after this linked this file into main file




            //Wp page
            $page_title = "Employee CRUD System";
            $page_content = "[wp-employee-form]"; //note ung content is for shortcode

            if(!get_page_by_title($page_title)){ //means if ung "Employee CRUD System" di pa nag eexist then go.
                wp_insert_post(array(
                    "post_title" => $page_title,
                    "post_content" => $page_content,
                    // note this the column name of database post_title and post_content. sa wp_posts
                    "post_type" => "page",
                    "post_status" => "publish",

                    //this is the overall code to create dynamic page to this content [wp-employee-form].
                ));
            }else{
                echo "The page '{$page_title}' already exists."; // kung nag exist naman na yan lang lalabs
            }
        }


        //Drop Table
        public function dropEmployeesTable(){
            $delete_command = "DROP TABLE IF EXISTS {$this->table_name}";
            $this->wpdb->query($delete_command);
        }
        //A public function to delete db table once we deactivated the plugin


        // Render Employee Form Layout
        public function createEmployeesForm(){
           ob_start();
           include_once WCE_DIR_PATH . "template/employee_form.php";
           $template = ob_get_contents();
           ob_end_clean();
           return $template;
        }

        //Add CSS/JS File
        public function addAssetsToPlugin(){
           
            
            // also look a file for validation query
            //- Search validate.min.js (https://jqueryvalidation.org/) > choose for demo	View source code > look for script jquery.validate.js ("../dist/jquery.validate.js) > the llink about is the CDN link of that library file (https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js)
            // Instead of linking, download the file> right click and save file sa assets. And call inside our plugin file. THEN CALL THE FILE

            // Enqueue Bootstrap CSS
            wp_enqueue_style(
                'bootstrap-css', 
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', 
                array(), 
                '5.3.0'
            );

             //Style
             wp_enqueue_style("employee-crud-css", WCE_DIR_URL . "assets/style.css");

            // Enqueue Bootstrap JS
            wp_enqueue_script(
                'bootstrap-js', 
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', 
                array('jquery'), 
                '5.3.0', 
                true
            );


            //Validation 
            wp_enqueue_script("wce-validation", WCE_DIR_URL. "assets/jquery.validate.min.js", array("jquery"));

            //JS
            wp_enqueue_script("employee-crud-js", WCE_DIR_URL . "assets/script.js", array("jquery"), "3.0");
            

            //Ajax url  >using wp funtion wp_localize_script
            wp_localize_script("employee-crud-js", "wce_object", array(
                "ajax_url" => admin_url("admin-ajax.php") // eto ung file na pag sinabmit mo
            ));
            // handle id copy the custom script file id which is the "employee-crud-js" and use it.
        }

        //Process Ajax Request: Add Employee form
        public function handleAddEmployeeFormData(){

            // to save data value into database table
            $name = sanitize_text_field($_POST['name']); // un ung key name ng input name.
            $email = sanitize_text_field($_POST['email']); 
            $designation = sanitize_text_field($_POST['designation']); 


            //create a variable fo profile image url
            $profile_url = "";

            //CHECK FOR FILE (for profile image upload)
            if(isset($_FILES['profile_image']['name'])){ //  The original name of the uploaded file.
                //name attribute value of profile image whic is "profile_image", then ung [name] means un ung att sya which is name = "profile_image". or the original name of the uploaded file.


                //para mabago ung file name after iupload
                $UploadFile = $_FILES['profile_image']; // employee-1.jpg
                //$UploadFile['name'] > this will print employee-1.jpg

                //Original File Name
                $originalFileName = pathinfo($UploadFile['name'], PATHINFO_FILENAME); // employee-1 because we are only getting the file name

                //File Extension
                $file_extension = pathinfo($UploadFile['name'], PATHINFO_EXTENSION); // jpg > it return the last value

                //Now we want to add employee-1 something like employee-1-182735 to be unique after the extension

                //New Image Name
                $newImageName = $originalFileName."_".time().".".$file_extension; // employee-1_674762.jpg it return like this > so basically contatination lang sya
                
                $_FILES['profile_image']['name'] = $newImageName; // to execute the $newImageName 
                //copy the whole process to change the image name and paste thin sa pang update ng image


                $fileUploaded = wp_handle_upload($_FILES['profile_image'], array("test_form" => false));
                    // meaning after getting the false value it understand that we have to get an image only and upload inside upload directory
                    // so once it upload it returns the path of that
                $profile_url = $fileUploaded["url"];

                /**
                 * the meaning of this $fileUploaded = wp_handle_upload($_FILES['profile_image'], array("test_form" => false));
                 * 
                 * array("test_form" => false) ->  wp_handle_upload is not going to check any file attributes or even file submission
                 * 
                 * array("test_form" => true) ->  wp_handle_upload will validate form request , nonce value and other form parameters.
                 * 
                 */

                
            }

                //note na meron na tayong inasign na table name so cacall nalang natin un.
            $this->wpdb->insert($this->table_name, [ 
                //we have to pass the table column para masave sa kanya kanya\
                "name" => $name,
                "email" => $email,
                "designation" => $designation,
                "profile_image" => $profile_url
            ]);

            //insert id
            $employee_id = $this->wpdb->insert_id;

            if($employee_id > 0){ // means successfully
                echo json_encode([
                    "status" => 1,
                    "message" => "Sucessfully, Employee data created!"
                ]);
            }else{
                echo json_encode([
                    "status" => 0,
                    "message" => "Failed to save data employee",
                    "data" => $_POST
                ]);
            }
            die;
        }

        //Load DB Table Employees
        public function handleLoadEmloyeesData(){
            
            $employees = $this->wpdb->get_results(
                //pass our query // select all data from table
                "SELECT * FROM {$this->table_name}", // all data should be associative array format so simply
                ARRAY_A 
            );

            // Now we have to return all data in JSON format but instead of json format we'll use wp function
            return wp_send_json([
                "status" => true,
                "message" => "Employees Data",
                "employees" => $employees
            ]);
        }


        //Delete Employee Data
        public function handleDeleteEmloyeesData(){
            $employee_id = $_GET["empId"]; // simply by getting employee id we have to delete that

            // Retrieve the employee's data to get the profile image URL.
            $employeeData = $this->getEmployeeData($employee_id);

            if (!empty($employeeData)) {
                $saved_profile_image = $employeeData['profile_image']; // Get the profile image URL.

                // Check if the image exists and delete it.
                if (!empty($saved_profile_image)) {
                    $wp_site_url = get_site_url(); // Get the site URL.
                    $file_path = str_replace($wp_site_url."/", "", $saved_profile_image); // Get the relative file path.

                    if (file_exists(ABSPATH . $file_path)) {
                        unlink(ABSPATH . $file_path); // Delete the file from the uploads folder.
                    }
                }

                // Delete the employee data from the database.
                $this->wpdb->delete($this->table_name, [
                    "id" => $employee_id
                ]);

                // Return success response.
                return wp_send_json([
                    "status" => true,
                    "message" => "Employee and associated image deleted successfully."
                ]);
            } else {
                // Return error if no employee data found.
                return wp_send_json([
                    "status" => false,
                    "message" => "No Employee Found with this ID."
                ]);
            }


            //OLD CODE
            // // Check if the image exists and delete it.
            // if(!empty($profile_image_url)){
            //     //if not empty means we have some link here 
            //     // copy sample link sa profile_image
            //     //http://mycustomplugin.test/wp-content/uploads/2025/01/IMG_20240101_194556_100.jpg
            //     //first we have to remove this path this is wp project path http://mycustomplugin.test
            //     //the get this path http://mycustomplugin.test /wp-content/uploads/2025/01/IMG_20240101_194556_100.jpg and delete by using a php function called unlink

            //     $wp_site_url = get_site_url(); // it return this value http://mycustomplugin.test
            //     $file_path = str_replace($wp_site_url."/", "", $profile_image_url); //wp-content/uploads/2025/01/IMG_20240101_194556_100.jpg
            //     // sa unang parameter nad pangalawa means we replace $wp_site_url to a blank value

            //     if(file_exists(ABSPATH . $file_path)){
            //         //remove that file from upload folder
            //         unlink(ABSPATH . $file_path);
            //         // Now it means SUCCESSFULLY we are going to remove our existing image from upload folder 
            //     }
            // }
            
            // //now call wpdb delete method
            // $this->wpdb->delete($this->table_name, [ // first argument is the table name and pass the condition
            //     "id" => $employee_id
            // ]);

            // //when it will be deleted 
            // return wp_send_json([
            //     "status" => true,
            //     "message" => "Employee Deleted Successfully"
            // ]);

            // // dont include exit because we are returning wp_send_json
            // //text if it'll deleted 
        }


        //Read single Employee Data
        public function handleToGetSingleEmloyeesData(){
            $employee_id = $_GET['empId'];
            
            if($employee_id > 0){
                $employeeData = $this->wpdb->get_row(
                    "SELECT * FROM {$this->table_name} WHERE id = {$employee_id}", ARRAY_A
                );

                return wp_send_json([
                    "status" => true,
                    "message" => "Employee Data Found",
                    "data" => $employeeData
                ]);
            }else{
                return wp_send_json([
                    "status" => false,
                    "message" => "Please pass employee ID"
                ]);
            }
        }

        //Update Employee Data
        public function handleUpdateEmployeeData(){
            // to update data value into database table
            $name = sanitize_text_field($_POST['employee_name']); // un ung key name ng input name ng idit layout form
            $email = sanitize_text_field($_POST['employee_email']); 
            $designation = sanitize_text_field($_POST['employee_designation']); 
            $id = sanitize_text_field($_POST['employee_id']); 


            $employeeData = $this->getEmployeeData($id);

            $profile_image_url = "";

            if(!empty($employeeData)){

                //if image is not going to upload new simply this value $employeeData['profile_image'] will be save inside our db table.
                //EXISTING PROFILE IMAGE
                $profile_image_url = $employeeData['profile_image'];


                // NOW To update profile image
                // 2 task we have to done
                // first we have to delete the existing profile image sa  wp content folder upload  second we have to upload a new file and then go inside database and save that
                //Now copy the name value of profile_image sa edit form layout.
                //NEW FILE IMAGE OBJECT
                $profile_file_image = isset($_FILES['employee_profile_image']['name']) ? $_FILES['employee_profile_image']['name'] : "";
                //Check image Exist
                if(!empty($profile_file_image)){
                    //Profile Image
                    //but before using wp handle upload first we have to check that out existing file exist inside upload folder 
                    // CREATE a private function  // Get employee data 
                    //after mo ma create copy mo ung private function > getEmployeeData
                    /*$employeeData = $this->getEmployeeData($id); */ // pa the $id because on the basis of this id we'll get our existing informations // now instead of this copy the method and lagay mo sa taas

                    if(!empty($profile_image_url)){
                        //if not empty means we have some link here 
                        // copy sample link sa profile_image
                        //http://mycustomplugin.test/wp-content/uploads/2025/01/IMG_20240101_194556_100.jpg
                        //first we have to remove this path this is wp project path http://mycustomplugin.test
                        //the get this path http://mycustomplugin.test /wp-content/uploads/2025/01/IMG_20240101_194556_100.jpg and delete by using a php function called unlink

                        $wp_site_url = get_site_url(); // it return this value http://mycustomplugin.test
                        $file_path = str_replace($wp_site_url."/", "", $profile_image_url); //wp-content/uploads/2025/01/IMG_20240101_194556_100.jpg
                        // sa unang parameter nad pangalawa means we replace $wp_site_url to a blank value

                        if(file_exists(ABSPATH . $file_path)){
                            //remove that file from upload folder
                            unlink(ABSPATH . $file_path);
                            // Now it means SUCCESSFULLY we are going to remove our existing image from upload folder 
                            //Next lets upload our new existing file 
                        }
                    }
                    

                    //para mabago ung file name after iupload
                    $UploadFile = $_FILES['employee_profile_image']; // employee-1.jpg
                    //$UploadFile['name'] > this will print employee-1.jpg
                    // employee_profile_image > name ng input na type file ung pang uploadan

                    //Original File Name
                    $originalFileName = pathinfo($UploadFile['name'], PATHINFO_FILENAME); // employee-1 because we are only getting the file name

                    //File Extension
                    $file_extension = pathinfo($UploadFile['name'], PATHINFO_EXTENSION); // jpg > it return the last value

                    //Now we want to add employee-1 something like employee-1-182735 to be unique after the extension

                    //New Image Name
                     $newImageName = $originalFileName."_".time().".".$file_extension; // employee-1_674762.jpg it return like this > so basically contatination lang sya
                        
                    $_FILES['employee_profile_image']['name'] = $newImageName; // to execute the $newImageName 
                        // employee_profile_image > name ng input na type file ung pang uploadan

                    //Upload New Image 
                    $fileUploaded = wp_handle_upload($_FILES['employee_profile_image'], array("test_form" => false));

                    $profile_image_url = $fileUploaded["url"]; 
                }
                
                //after getting all this values use the concept of update method wp
                $this->wpdb->update($this->table_name, [
                    "name" => $name,
                    "email"=> $email,
                    "designation" => $designation,
                    "profile_image" => $profile_image_url

                    // one more thing on the basis of id we have to update our employee so also we need the employee id to this form submitted
                    // add hidden input sa edit form layout with type of hidden and name and id as employee_id
                ], [
                    //dito mo ilalagay ung id mo sa third argument of this method
                    "id" => $id

                    //now by the help of this code we are going to update our existing employee info on the basis of this ID value 
                ]);
                //then return 
                return wp_send_json([
                    "status" => true,
                    "message" => "Employee Updated Successfully",
                ]);
            }else{

                return wp_send_json([
                    "status" => false,
                    "message" => "No Employee Found with this ID"
                ]);
            }

            

            
        }

        // Get employee data 
        private function getEmployeeData($employee_id){ // while calling this function pass the employee id
            // on the basis of this employee id first we have to retrive all existing information
            $employeeData = $this->wpdb->get_row(
                "SELECT * FROM {$this->table_name} WHERE id = {$employee_id}", ARRAY_A
            );
            //after getting the data return
            return $employeeData;
            //copy the function getEmployeeData and call mo sya sa pang update
        }   
    }

    
?>