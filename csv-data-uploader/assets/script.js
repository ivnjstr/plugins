// console.log("Hello World");
// Now it works
// In here How to implement our ajax concent using this script

jQuery(document).ready(function(){
    jQuery("#frm-csv-upload").on("submit", function(event){

        event.preventDefault();

        var formData = new FormData(this);

        jQuery.ajax({ // this are the parameter what we have to pass before working over this ajax method  
            url: cdu_object.ajax_url, // this url once we submit the for it will submit to this URl. TO GET URL ADD  wp_localize_script
            data: formData,
            dataType: "json",
            method: "POST", 
            processData: false,
            contentType: false,
            success: function(response){
                console.log(response);
                if(response.status){
                    jQuery("#show_upload_message").html(response.message).css({
                        color: "green" 
                    });
                }
            }
        })

    })

})