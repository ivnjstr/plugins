jQuery(document).ready(function(){
    console.log("TEST");
    
    
    jQuery(".mcw_dd_options").on("change", function(){

        let mcw_option_value = jQuery(this).val(); // mcw_display_recent_posts, mcw_display_static_message

        // after you get the id for both add kana condition
        if(mcw_option_value == "recent_post"){ // eto ung value ng mga options
    
            jQuery("p#mcw_display_recent_posts").removeClass("hide_element");
            jQuery("p#mcw_display_static_message").addClass("hide_element");
        } else if(mcw_option_value == "static_message"){ // eto ung value ng mga options
         
            jQuery("p#mcw_display_recent_posts").addClass("hide_element");
            jQuery("p#mcw_display_static_message").removeClass("hide_element");
        }
    })

});   // A FORMAT TO START JQUERY >  jQuery(document).ready(function(){ console.log("Welcome To Custom Widgetsss"); });  