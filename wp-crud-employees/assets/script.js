jQuery(document).ready(function(){
    console.log("Welcome to CRUD Plugin of Employees");

    //copy the form if in MyEmployees.php frm_add_employee
    // Add Form Validation
    jQuery("#frm_add_employee").validate();
    // remember this validation available if we link the jquery.validate.min.js into a plugin file

    // Form Submit
    jQuery("#frm_add_employee").on("submit", function(event){
        //id ng form #frm_add_employee

        event.preventDefault(); // means prevent the default behavior of a form submittion

        
        // Perform validation // gpt if they submit for required input fields
        let isValid = true; // Flag to check if all fields are valid
        jQuery("#frm_add_employee input, #frm_add_employee select, #frm_add_employee textarea").each(function() {
            if (jQuery(this).prop('required') && jQuery(this).val().trim() === "") {
                isValid = false;
                jQuery(this).addClass('error'); // Optional: Add a class to highlight invalid fields
            } else {
                jQuery(this).removeClass('error');
            }
        });

        if (!isValid) {
           
            return; // Stop execution if validation fails
        }



        //create form data variables
        var formdata = new FormData(this); //we need this kasi may hidden input tayo un ung process ajax

        jQuery.ajax({
            //pass the property
            url: wce_object.ajax_url, // first value we need is url. copy the object name "wce_object" then . copy the property "ajax_url". kaya naging "wce_object.ajax_url".
            data: formdata, // data copy the formdata sa taas ung var
            method: "POST", // we want to submit our data using post type
            dataType: "json", // after response we want in json format
            contentType: false, //kasi may form data tayo
            processData: false, //kasi may form data tayo
            success: function(response){
                if(response.status){
                    alert(response.message);
                    // window.location.reload(); // Reload the page
                    loadEmployeesData(); // Reload the List table function data
                    // Or pwede ka mag settimeout
                    setTimeout(function(){
                        location.reload();
                    }, 1500); //1.5 seconds

                }
            }

            // Note: add another input type hidden sa form sa taas
            //<input type="hidden" name="action" value="wce_add_employee">
            //we need this hidden field because we want to capture this ajax request inside wp and process all those data > now copy the name value > the go to main file add for process ajax req
        })
    });

    //Render Employees
    loadEmployeesData();


    // Delete Function
    jQuery(document).on("click", ".btn-delete-employee", function(){ //btn-delete-employee > class for delete
        console.log("click");
        
        var employeeId = jQuery(this).data("id");
        var empProfileImage = jQuery(this).data("profile_image");
        //means the btn what we are clicking on the basis of that we are getting its data id attribute value
        
        // first add for confirmation
        if(confirm("Are you sure you want to delete?")){ //TRUE
            jQuery.ajax({
                url: wce_object.ajax_url,
                data: {
                    //create an object
                    action: "wce_delete_employee",
                    empId: employeeId,
                    empImage: empProfileImage
                },
                method: "GET",
                dataType: "json",
                success: function(response){
                    if(response){
                        alert(response.message);
                        loadEmployeesData(); 
                        setTimeout(function(){
                            location.reload;
                        }, 1500)
                    }
                }
                // then add action atrribute value ng data which is wce_delete_employee(copy) sa main file
                // also copy the empId ilalagay sa public function
            })
        }
    });

    //Open Add employee Form
    jQuery(document).on("click", "#btn_open_add_employee_form", function(){
        //now we have to remove hide_element class for add employee form if we click this button
        //first add class elementb for add employee form as add_employee_form
        jQuery(".add_employee_form").toggleClass("hide_element");

        //also hide itself when the add employee form show up  > add class hide_element
        jQuery(this).addClass("hide_element")
    });

    //Close Add employee form
    jQuery(document).on("click", "#btn_close_add_employee_form", function(){
        // the id is for btn close form inside add_employee_form
        jQuery(".add_employee_form").toggleClass("hide_element");

        //remove class element for button Add employee wwhen it close the form
        jQuery("#btn_open_add_employee_form").removeClass("hide_element")
    

    });

    //Open Edit Layout
    jQuery(document).on("click", ".btn-edit-employee", function(){
        // the id of button edit > #btn-edit-employee
        //now copy the form id for edit employee layout  >.edit_employee_form
        jQuery(".edit_employee_form").removeClass("hide_element");
        jQuery("#btn_open_add_employee_form").addClass("hide_element")
        

        // Get existing data of an Employee by Employee ID
        var employeeId = jQuery(this).data("id"); // or either jQuery(this).attr("data-id");
        // means it will return the current click employee id.

        //ajax request     -     copy the syntax method ajax for delete
        jQuery.ajax({
            url: wce_object.ajax_url,
            data: {
                //create an object
                action: "wce_get_employee_data",
                empId: employeeId
            },
            method: "GET",
            dataType: "json",
            success: function(response){
                // console.log(response) instead of console.log > 
                // cpopy the id of input that you want to put the name value
                jQuery("#employee_name").val(response?.data?.name);
                // this syntax (response?.data?.name) means that inside our response variable we have data key if it exist also we are checking that if name property exist
                // repeat the process for email, designation and profile image 
                jQuery("#employee_email").val(response?.data?.email);
                jQuery("#employee_designation").val(response?.data?.designation);
                jQuery("#employee_id").val(response?.data?.id);
                jQuery("#file_edit").val("");
                //now go and add jquery of submit edit function > copy the form if of edit layout
                //for image add img tag sa profile image na may id of "employee_profile_icon" and also set mo ung height ng 100px and width of 100px > then copy mo id and lagay mo dito but we are using attr kasi kukunin nation ung src 
                jQuery("#employee_edit_profile_icon").attr("src", response?.data?.profile_image);
                //now check if the image is lumalabas pag ing edit mo
                console.log(response.data.profile_image);

            }
            //copy the action value wce_get_employee_data then add_action for proces the ajax
        })
    });
    
    //Close Edit Layout
    jQuery(document).on("click", "#btn_close_edit_employee_form", function(){

        const form = jQuery(".edit_employee_form"); // Select the form element
        form.toggleClass("hide_element"); // Toggle the hide_element class

        if (form.hasClass("hide_element")) {
            form.find("#file_edit").val(""); // Clear all input fields within the form
            // form.find("select").prop("selectedIndex", 0); // Reset all dropdowns to the first option
        }
        // jQuery(".edit_employee_form").toggleClass("hide_element");

        //remove class element for button Add employee wwhen it close the form
        jQuery("#btn_open_add_employee_form").removeClass("hide_element")

    });

    //Submit Edit Form
    jQuery(document).on("submit", "#frm_edit_employee", function(event){
        event.preventDefault();
        //copy the ajax request form delete and change sometings
        var formdata = new FormData(this);
        jQuery.ajax({
            url: wce_object.ajax_url,
            data: formdata,
            method: "POST",
            contentType: false,
            processData: false,   // this 2 will be added because we are uisng the formdata method
            dataType: "json",
            success: function(response){
               
                if(response){
                    alert(response?.message);
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                }
                
            }
            // copy the hidded input value then un ung ilalagay mo sa add_action
            // after that add public function
        })

    })

    // JavaScript to preview the uploaded image FOR ADD
    document.getElementById('file').addEventListener('change', function(event) {
        const file = event.target.files[0]; // Get the selected file
        const preview = document.getElementById('employee_profile_icon'); // Get the <img> element

        if (file) {
            const reader = new FileReader(); // Create a FileReader to read the file    

            // When the file is read, set it as the src for the <img> element
            reader.onload = function(e) {
                preview.src = e.target.result; // Set the image src to the file data
            };
            console.log(preview.src);

            reader.readAsDataURL(file); // Read the file as a data URL
        } 
        
        else {
            preview.src = 'http://my-custom-plugins.test/wp-content/plugins/wp-crud-employees/template/no-photo.png';
            // Clear the preview if no file is selected
        }
    });

    // JavaScript to preview the uploaded image FOR EDIT
    document.getElementById('file_edit').addEventListener('change', function(event) {
        const file = event.target.files[0]; // Get the selected file
        const preview = document.getElementById('employee_edit_profile_icon'); // Get the <img> element

        if (file) {
            const reader = new FileReader(); // Create a FileReader to read the file    

            // When the file is read, set it as the src for the <img> element
            reader.onload = function(e) {
                preview.src = e.target.result; // Set the image src to the file data
            };
            console.log(preview.src);

            reader.readAsDataURL(file); // Read the file as a data URL
        } 
        
        else {
            preview.src = 'http://my-custom-plugins.test/wp-content/plugins/wp-crud-employees/template/no-photo.png';
            // Clear the preview if no file is selected
        }
    });


}) 

// Load All employees from DB Table
function loadEmployeesData(){
    jQuery.ajax({
        url:  wce_object.ajax_url,
        data: {
            action: "wce_load_employees_data"
        },
        method: "GET",
        dataType: "json",
        success: function(response){
            // instead of console.log(response); go use about each method of jquery 
            // by using that we have to iterate each data of employees array 

            var employeesDataHTML = "";
            jQuery.each(response.employees, function(index, employee){
                //ung .employees makikita mo sa status or ung sa public function nya na "employees" -> $mployee
                
                //for image_profile
                let employeeProfileImage = "--";
                if(employee.profile_image){
                    employeeProfileImage =  `<img src="${employee.profile_image}" height="80px" width="80px">`;
                }

                //for tbody form .html
                employeesDataHTML += `
                    <tr>
                        <td class="d-none">${employee.id}</td>
                        <td>${employee.name}</td>
                        <td>${employee.email}</td>
                        <td>${employee.designation}</td>
                        <td>${employeeProfileImage}<td>
                            <button data-id="${employee.id}" class="btn-edit-employee btn btn-primary btn-sm">Edit</button>
                            <button data-id="${employee.id}" data-profile_image="${employee.profile_image}" class="btn-delete-employee btn btn-primary btn-sm">Delete</button>
                        </td>
                    </tr>
                `
                
            });
            // now copy the tbody id employees_data_tbody

            //BIND DATA WITH TABLE
            jQuery("#employees_data_tbody").html(employeesDataHTML);
        }
    })

    // render the function name inside
    // copy the action value name "wce_load_employees_data" then add_action and public function


    // after getting all those data we have to bind this data to our table 
    //COPY THE <tr> Tag in html form and add id sa tbody "employees_data"

} 




