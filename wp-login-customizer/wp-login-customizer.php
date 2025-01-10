<?php
/*
 * Plugin Name:	WP Login Page Customizer
 * Description: This plugin will customize Logo, Text COlor and Background Color.
 * Version: 1.0
 * Author URI: https://author.example.com/
 * Plugin URI: https://example.com/plugins/the-basics/
 */

if(!defined("ABSPATH")){
    exit;
}

// when we create this plugin we want to create a menu page.
// we want to create a sub menu

add_action("admin_menu", "wlc_add_submenu");
function wlc_add_submenu(){

    //using this add submenu para maka create ka ng submenu
    add_submenu_page("options-general.php", "WP Login Page Customizer", "WP Login Page Customizer", "manage_options", "wp-login-page-customizer", "wlc_handle_login_settings_page");
    // parent slug makikita mo sa url once nag click ka ng menu
    // menu title same lang sa page title
    //capability means user label access
}

// Login page Customizer layout
function wlc_handle_login_settings_page(){
    // create separate files for layout > template  > login-settings-layout.php
    //Then call the file
    ob_start();
    include_once plugin_dir_path(__FILE__) . "/template/login-settings-layout.php";
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
}

// Register Settings for Login Page
add_action("admin_init", "wlc_login_page_settings_fields_registration");
function wlc_login_page_settings_fields_registration(){

    register_setting("wlc_login_page_settings_fields_group", "wlc_login_page_text_color"); //For Text Color
    //option group ng setting fields sa login settings layout
    //custom option name ng text color  > kahit ano 

    register_setting("wlc_login_page_settings_fields_group", "wlc_login_page_background_color"); //For BG

    register_setting("wlc_login_page_settings_fields_group", "wlc_login_page_logo"); // For Logo
    
    // Create a Section here and add Settings Field
    add_settings_section("wlc_login_page_section_id", "Login Page Customizer Settings", null, "wp-login-page-customizer");
    // Create a unique id wlc_login_page_section_id
    // title sa unahan ng layout mo Login Page Customizer Settings
    // callback null lang
    // ung Page slug ung nasa layout > ung page slug ng submenu mo or pmenu slug ng main file
    // NOW WE HAVE SECTION

    // For TEXT COLOR
    add_settings_field("wlc_login_page_text_color", "Page Text Color", "wlc_login_page_text_color_layout", "wp-login-page-customizer", "wlc_login_page_section_id");
    //ung id option name ng register settings like sa text color wlc_login_page_text_color
    //Callback name for function
    //ung page un ung page ng add_setting_section  which is wp-login-page-customizer
    //ung id sa last un ung id sa add_setting_section which is  wlc_login_page_section_id
    // then call the function of text color

    // For BACKGROUD COLOR
    add_settings_field("wlc_login_page_background_color", "Page Background Color", "wlc_login_page_background_color_layout", "wp-login-page-customizer", "wlc_login_page_section_id");

    // For LOGO
    add_settings_field("wlc_login_page_logo", "Login Page Logo", "wlc_login_logo_layout", "wp-login-page-customizer", "wlc_login_page_section_id");
}

//Text Color Settings
function wlc_login_page_text_color_layout(){
    // to get the value remain in the input once you reload the page > using get_option
    $text_color = get_option("wlc_login_page_text_color", "");
    // ung option name un ung option name sa datasbase ng text_color
    // once you get the value add value sa input
    ?>
        <input type="text" value="<?php echo $text_color; ?>" name="wlc_login_page_text_color" placeholder="Text Color">
        <!-- name ung sa option name ng register setting ng text-color -->
    <?php
}

// Background Color Setting
function wlc_login_page_background_color_layout(){
    $bg_color = get_option("wlc_login_page_background_color", "");
    ?>  
        <input type="text" value="<?php echo $bg_color; ?>" name="wlc_login_page_background_color" placeholder="Background Color" >
         <!-- name ung sa option name ng register setting ng background-color -->
    <?php
}

function wlc_login_logo_layout(){
    $page_logo = get_option("wlc_login_page_logo", "");
    ?>
        <input type="text" value="<?php echo $page_logo; ?>" name="wlc_login_page_logo" placeholder="Enter Url">
    <?php
}

//Render Custom Login Page Setting to Login Screen
add_action("login_enqueue_scripts", "wlc_login_page_customizer_settings");

function wlc_login_page_customizer_settings(){
    //first we have to get all the value inside our table
    $text_color = get_option("wlc_login_page_text_color", "");
    $bg_color = get_option("wlc_login_page_background_color", "");
    $page_logo = get_option("wlc_login_page_logo", "");

    ?>
        <style>

            /* For TEXT COLOR */
            <?php
                if(!empty($text_color)){ // if not empty the value should be rendered
                    ?>

                    /* ung login id kinuha ko sa inspect mismong page */
                    div#login,
                    a.wp-login-lost-password,
                    p#backtoblog a,
                    p#nav .wp-login-log-in{ 
                        color: <?php echo $text_color; ?> !important; /* add important para ma force*/
                    }
                    <?php 
                }
            ?>

            /* For BACKGROUND COLOR */
            <?php
                if(!empty($bg_color)){ // if not empty the value shoulf be rendered
                    ?>

                    body.login,
                    form#loginform,
                    div#login-message,
                    input.input,
                    input#rememberme,
                    div#login_error,
                    div.notice-info,
                    form#lostpasswordform{ /* Inspect the body get the class */
                        background-color: <?php echo $bg_color; ?> !important;
                    }
                    form#loginform{
                        border: none !important;
                        
                    }
                    div#login-message{
                        padding: 2px !important;
                        box-shadow: none !important;
                        border-left: none;
                        text-align: center;
                        font-size: 20px;
                    }
                    input.input{
                        border: 2px solid black !important;
                    }
                    input#rememberme{
                        border: 1px solid black !important;
                        overflow: hidden !important;
                    }
                    input#rememberme:checked::before{
                        color: black !important;
                        background-color: black !important;
                    }
                    div#login_error{
                        padding: 2px !important;
                        border-left: none !important;
                        text-align: center !important;
                        box-shadow: none !important;
                    }
                    span.dashicons-visibility{
                        color: black !important;
                    }
                    span.dashicons-hidden{
                        color: black !important;
                    }
                    input#wp-submit{
                        background-color: black !important;
                    }
                    input:-webkit-autofill {
                        box-shadow: 0 0 0px 1000px transparent inset; /* Makes the background transparent */
                        -webkit-text-fill-color: #000; /* Text color */
                        transition: background-color 5000s ease-in-out 0s; /* Prevents resetting */
                    }
                    div.notice-info{
                        border-left: none !important;
                        text-align: center !important;
                    }
                    form#lostpasswordform{
                        border: none !important;
                    }
                    <?php 
                }
            ?>

            /* For LOGO */
            <?php
                if(!empty($page_logo)){ 
                    ?>
                        h1.wp-login-logo a{ /* Inspect and get the id or class of Logo */
                            background-image: none, url(<?php echo $page_logo; ?>) !important;
                        }
                    <?php
                }
            ?>
        </style>
    <?php

}   
