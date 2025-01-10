<form action="options.php" method="post">
    <?php
        settings_fields("wlc_login_page_settings_fields_group"); // custom group page name
        do_settings_sections("wp-login-page-customizer"); // page/menu slug ng submenu page mo sa main file
        submit_button("Save Settings");
        //Now create some settings field of this group wlc_login_page_settings_fields_group
        // > create inside the main file    
    ?>
</form>