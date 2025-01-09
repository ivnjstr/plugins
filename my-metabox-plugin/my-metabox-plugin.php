<?php
/*
 * Plugin Name:	My Custom Metabox
 * Description: This will be the metabox for wordpress pages
 * Version: 1.0
 * Author URI: https://author.example.com/
 * Plugin URI: https://example.com/plugins/the-basics/
 */

if(!defined("ABSPATH")){
    exit;
}

// Register Metabox
add_action("add_meta_boxes", "mmp_register_page_metabox");
function mmp_register_page_metabox(){
    add_meta_box("mmp_metabox_id", "My Custom Metabox - SEO", "mmp_create_page_metabox", "page");
}

function mmp_create_page_metabox($post){ //pass $post here para ma remain ung value sa page pag nireload

    // instead of creating all html layout inside this function create separated files
    // then include template files
    ob_start();
    include_once plugin_dir_path(__FILE__). "/template/page_metabox.php";
    $template = ob_get_contents();
    ob_end_clean();
    echo $template;
}

// Save Data of Custom Metabox
add_action("save_post", "mmp_save_metabox_data");

function mmp_save_metabox_data($post_id){
        // it will take a value called $post_id means this metabox only visible inside pages module so post ID is nothing it will be page ID.

        //Check and verify Nonce Value
                    //Nonce value, inside of wp nonce means number of used ones means that we'll generate a unique number for each action and that number should be validated with request inside that callback function -> SO before saving this values first we'll check that nonce value if it will be varified
            //first add wp_nonce_field in page_metabox.php
        if(!wp_verify_nonce($_POST["mmp_save_pmetabox_nonce"], "mmp_save_metabox_data")){
            return;
        }

        //Check and verify Auto save of Wordpress
                    // wp also support autosave functionality it means that > saving draft automatically
        if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE){
            return;
        }

    // to save value sa database
    if(isset($_POST['pmeta_title'])){
        update_post_meta($post_id, "pmeta_title", $_POST["pmeta_title"]);
    }
        // meta key should be unique. we can pass any name but ill choose name att of metatitle ung sa pagematabox ung name copy mo
        // Once we enter dun sa name att by using it  simply call $_POST["pmeta_title];
        //Ganun din sa Description para ma save sya
    if(isset($_POST['pmeta_description'])){
        update_post_meta($post_id, "pmeta_description", $_POST["pmeta_description"]);
    } 
        // makikita mo ung value  ng meta mo sa database wp_postmeta
}



// Add Meta tags in Head tag
add_action("wp_head", "mmp_add_head_meta_tags");// means by the callback those code will bw added inside our head tag


function mmp_add_head_meta_tags(){
    // now first check if its a page or post
    // so we want to display this meta title and descriptions only in CASE of Page

    if(is_page()){
        global $post;
        $post_id = $post->ID;
        $title = get_post_meta($post_id, "pmeta_title", true);
        $description = get_post_meta($post_id, "pmeta_description", true);
        //after getting title and description. now add meta tags
        if(!empty($title)){
            echo '<meta name="title" content="'.$title.'" />';
        }
        if(!empty($description)){
            echo '<meta name="title" content="'.$description.'" />';
        }
    }
}