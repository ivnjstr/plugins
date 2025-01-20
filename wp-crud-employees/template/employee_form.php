<div id="wp_employee_crud_plugin">
    

    <!-- ADD EMPLOYEE LAYOUT -->
    <div class="form-container add_employee_form hide_element">
        <button class="btn btn-secondary" id="btn_close_add_employee_form" style="float: right;">Close Form</button>
        <h2 style="text-align: start;">Add Employee</h2>
        <form action="javascript:void(0)" id="frm_add_employee" enctype="multipart/form-data" class="employee-form row g-3 container-fluid">
        <!-- We'll submit a form using ajax code so we dont want the action attribute -->
        <!-- The action="javascript:void(0)" attribute used to prevent the default behavior of submitting a form.      This is a JavaScript expression that evaluates to undefined and does nothing.-->
        <!-- we have enctype="multipart/form-data" because we submitting a profile image file -->

            <input type="hidden" name="action" value="wce_add_employee"> <!--- for Process ajax > data sa jquery ajax -->
            <!-- we need this hidden field because we want to capture this ajax request inside wp and process all those data > now copy the name value > the go to main file add for process ajax req-->

            <div class="form-group col-md-6">
                <label for="name">Name</label>
                <input type="text" required name="name" placeholder="Enter Employee Name" id="name" >
            </div>
            <div class="form-group col-md-6">
                <label for="email">Email</label>
                <input type="email" required name="email" placeholder="Enter Employee Email" id="email" >
            </div>
            <div class="col-md-8">
                <div class="form-group col-md-12">
                    <label for="designation">Designation</label>
                    <select class="form-select" aria-label="Default select example" name="designation" id="designation" required>
                        <option selected value="">-- Choose Designation --</option>
                        <option value="PHP Developer">PHP Developer</option>
                        <option value="Full Stack Developer">Full Stack Developer</option>
                        <option value="WordPress Developer">WordPress Developer</option>
                        <option value="Java Developer">Java Developer</option>
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label class="form-label" for="file">Profile Image</label>
                    <input class="form-control" type="file" name="profile_image" id="file" accept="image/*">
                </div>
            </div>
            <div class="col-md-4">
                <!-- Show Image -->
                <img class="rounded" id="employee_profile_icon" style="height: 200px; width: 100%; margin-top: 10px;" src="http://my-custom-plugins.test/wp-content/plugins/wp-crud-employees/template/no-photo.png">
            </div>
            <div class="form-group">
                <button id="btn_save_data" type="submit" class="btn-primary">Save Data</button>
            </div>
        </form>
    </div>

    <!-- EDIT EMPLOYEE LAYOUT -->
    <div class="form-container edit_employee_form hide_element">
        <button class="btn btn-secondary" id="btn_close_edit_employee_form" style="float: right;"> Close Form </button>
        <h2 style="text-align: start;">Edit Employee</h2>
        <form action="javascript:void(0)" id="frm_edit_employee" enctype="multipart/form-data" class="employee-form row g-3 container-fluid">


            <input type="hidden" name="action" value="wce_edit_employee">
            <!-- we need this hidden field because we want to capture this ajax request inside wp and process all those data > now copy the name value > the go to main file add for process ajax req-->
            <input type="hidden" name="employee_id" id="employee_id">
            <!-- for id update > now copy the id and add mo sya sa edit sa script > like this jQuery("#employee_id").val(response?.data?.id); meanskinukuha mo ung id nya pero naka hidden -->

            <div class="form-group col-md-6">
                <label for="employee_name">Name</label>
                <input type="text" required name="employee_name" placeholder="Enter Employee Name" id="employee_name" >
            </div>
            <div class="form-group col-md-6">
                <label for="employee_email">Email</label>
                <input type="email" required name="employee_email" placeholder="Enter Employee Email" id="employee_email" >
            </div>
            <div class="col-md-8">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="employee_designation">Designation</label>
                        <select class="form-select" aria-label="Default select example" name="employee_designation" id="employee_designation" required>
                            <option value="">-- Choose Designation --</option>
                            <option value="PHP Developer">PHP Developer</option>
                            <option value="Full Stack Developer">Full Stack Developer</option>
                            <option value="WordPress Developer">WordPress Developer</option>
                            <option value="Java Developer">Java Developer</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="employee_profile_image">Profile Image</label>
                        <input class="form-control" type="file" id="file_edit" name="employee_profile_image" id="employee_file">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <img class="rounded" id="employee_edit_profile_icon" style="height: 200px; width: 100%; margin-top: 10px;">
                <!-- Copy The ID an -->
            </div>
            <div class="form-group">
                <button id="btn_update_data" type="submit" class="btn-primary">Update Employee</button>
            </div>
        </form>
    </div>

    <!-- Also we want here something a layout to list employees  -->
    <!-- simply we'll have a single page and inside that page we'll have a form -->
    <!-- LIST EMPLOYEE LAYOUT -->
    <div class="list-container">
        <button class="btn btn-secondary" id="btn_open_add_employee_form" style="float: right;">Add Employee</button>
        <h2>List Employees</h2>
        <table class="table table-dark table-striped" >
            <thead>
                <tr>
                    <th class="d-none">#ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Designation</th>
                    <th>Profile Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="employees_data_tbody">

            </tbody>
        </table>
    </div>
</div>




