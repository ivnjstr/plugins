<?php


/*
 * Plugin Name:	ShortCode Plugin
 * Description: This  is my second Plugin which create some information widgets to admin dashboards
 * Author: John Smith
 * Version: 1.0
 * Author URI: https://author.example.com/
 * Plugin URI: https://example.com/plugins/the-basics/
 */

// BASIC SHORTCODE
add_shortcode("message", "sp_show_static_message"); // [message] remember if you want to call the sortcode use this format
function sp_show_static_message(){
    return "<p style='color: red; font-size: 36px; font-weight: bold;' >This is a simple shortcode message</p>";
}


// SHORTCODE WITH PARAMETERS
add_shortcode("student", "sp_handle_student_data");
// [student name="John" email="berts@gmail.com"]
function sp_handle_student_data($attributes){
    $attributes = shortcode_atts(array(
        "name" =>  "Default Name",
        "email" => "Default Email"
    ), $attributes, "student");
    return "<h3>  Student Data: Namee - {$attributes['name']}, Email - {$attributes['email']}</h3>";
}


// SHORTCODE WITH DB OPERATIONS
add_shortcode("list-post", "sp_handle_list_post_wp_query_class");
function sp_handle_list_posts(){
    // so want to make a query insde the database
    global $wpdb; // by the help of this we can use many methods


    $table_prefix = $wpdb -> prefix; // by using this prefic property it will give wp_ what we have saved inside the database SO MEANS wp_ syaaaa
    $table_name = $table_prefix . "posts"; //  kasi kukuninn natin or papasok tayo sa wp_post SO MEEANING UNG $table_name natin is wp_post na!!!!!

    // so next we want to fetch all the post
    // GET post whose post_type = post and post_status = publish.


    $posts = $wpdb->get_results( // eto given to part sa fetching
        "SELECT post_title from {$table_name} WHERE post_type = 'post' AND post_status = 'publish'"
    );

    // return $posts; but this result is array instead of single data SOLUTION IS USE FOR EACH LOOP

    if(count($posts) > 0){
        $outputHtml = "<ul>";

            foreach ($posts as $post){
                $outputHtml .= '<li>'.$post->post_title.'</li>';
            }

        $outputHtml .= "</ul>"; // Append closing tag correctly

        return $outputHtml;
    }
    return 'No Post Found';
} // we dont want to use this concept to get all the data from database


// so alternative option to get the data set using Wordpress class called WP query
// by using wp query and some argument and parameters we can get all about our post data
function sp_handle_list_post_wp_query_class($attributes){

    $attributes = shortcode_atts(array(
        "number" => 5, // defined the default values like we want 5 total number default post
    ), $attributes, "list-post");

    // now we want to get all the post data by using WP query class

    // means its an object we can get or change any name
    $query = new WP_Query(array(
        "post_per_page" => $attributes['number'], // means how many post we want per page
        "post_status" => "publish"
    ));

    if($query->have_posts()){

        $outputHtml = "<ul>";
            while($query->have_posts()){
                $query->the_post();
                $outputHtml .= '<li class="my_class"> <a href="'.get_the_permalink().'">'.get_the_title().'</a></li>'; // if you want to add a class jsut add heheh
                // also if you want a permalink just add <a href="'.get_the_permalink().'"
            }
        $outputHtml .= "</ul>";
        return $outputHtml;
    }
    return "No Post Found";

}