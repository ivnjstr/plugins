<?php

class My_Custom_Widgets extends WP_Widget{   // this is a child class My_Custom_Widgets and this is a parent class WP_Widget.
    // REMEMBER WE ARE EXTENDING AND INGERITING THIS PARENTS WE WANT TO OVERIDE FEW METHODS LIKE FORM, WIDGETS AND UPDATES methods.-

    //Constructor
    public function __construct() // this is a __construct menthod of child class
    {
        parent::__construct(  // this is a __construct menthod of parent class
            
            //first argument we have to pass is the widget if means unique ha
            "my_custom_widget",
            //then widgets title
            "My Custom Widget",
            //then array options
            array(
                "description" => "Display Recent Post and a Static"
            )
        );
    }


    // There are 3 method na i cocopy natin or i override natin sa class wp widgets
    //1. Display Widgets to Admin panel > copy the form signiture in class-wp-widgets
    public function form( $instance ){
        
        $mcw_title = !empty($instance['mcw_title']) ? $instance['mcw_title'] : "";
        // after this add mo to sa value ng input ng mcw_title like this
        // now gawin mo din sa iba 
        $mcw_display_option = !empty($instance['mcw_display_option']) ? $instance['mcw_display_option'] : "";
        $mcw_number_of_posts = !empty($instance['mcw_number_of_posts']) ? $instance['mcw_number_of_posts'] : "";
        $mcw_your_message = !empty($instance['mcw_your_message']) ? $instance['mcw_your_message'] : "";

        ?> <!-- Now create a form -->
            <p>
                <label for="<?php echo $this->get_field_name('mcw_title'); ?>">Title</label>
                <input type="text" name="<?php echo $this->get_field_name('mcw_title'); ?>" id="<?php echo $this->get_field_id('mcw_title'); ?>" class="widefat"  value="<?php echo $mcw_title; ?>"> 
                <!-- all the attributes are important -->
                <!-- Lets say that we want top generate the name and id  so simply we have to use two methods one for the name att and other for isd att THIS MWTHOD WILL BE PROVIDED BY OUR PARENT CLASS CALLED WP_Widgets which is the class_wp_widget-->
                <!-- go look or search for get_field_name and get_field_id COPY and paste here for name and id using php and echo $this->get_field_name and add ('title') kasi title field sya and dapat unique  -->

                <!-- Now fill also the name and id for the others -->
            </p>

            <p>
                <!-- copy the name att paste sa for att for label -->
                <label for="<?php echo $this->get_field_name('mcw_display_option'); ?>">Display Type</label>
                <select class="widefat mcw_dd_options" name="<?php echo $this->get_field_name('mcw_display_option'); ?>" id="<?php echo $this->get_field_id('mcw_display_option'); ?>">
                    <option <?php if($mcw_display_option == "recent_post") { echo "selected"; } ?> value="recent_post">Recent Post</option> <!-- add value -->
                    <option <?php if($mcw_display_option == "static_message") { echo "selected"; } ?>  value="static_message">Static Message</option> <!-- add value -->
                </select>
            </p>

            <p id="mcw_display_recent_posts" <?php if($mcw_display_option == "recent_post"){} else echo 'class="hide_element"'; ?> > <!-- this means once na sinelect natin ung recent post ito ulng lalabas -->
                <label for="<?php echo $this->get_field_name('mcw_number_of_posts'); ?>">Number of Post</label>
                <input type="number" name="<?php echo $this->get_field_name('mcw_number_of_posts'); ?>" id="<?php echo $this->get_field_id('mcw_number_of_post'); ?>" value="<?php echo $mcw_number_of_posts; ?>" class="widefat" >
            </p> 

            <p id="mcw_display_static_message" <?php if($mcw_display_option == "static_message"){} else echo 'class="hide_element"'; ?>> <!-- Add class element para yan una mag hide -->
                <label for="<?php echo $this->get_field_name('mcw_your_message'); ?>">Your Message</label>
                <input type="text" name="<?php echo $this->get_field_name('mcw_your_message'); ?>" id="<?php echo $this->get_field_id('mcw_your_message'); ?>" value="<?php echo $mcw_your_message; ?>" class="widefat">
            </p>
        <?php

        //NOTEEEEEEEEEEEE
        // Now notice that when you inspect the input of the calendar widgets there have an name and id attribute value. And in out custom widgets we don’t have. -	So how to get value and whats the pattern of that - This is the easiest daw na concept sa wp
        
        

        
    }

    //2. Save Widgets Settings to Wordpress > copy the update signiture in class-wp-widgets
    public function update( $new_instance, $old_instance ) {

        $instance = []; // copy and paste here all the input field name. mcw_title, mcw_display_option, mcw_number_of_posts, mcw_your_message. now we want to save all this value sa wp db

        $instance['mcw_title'] = !empty($new_instance['mcw_title']) ? strip_tags($new_instance['mcw_title']) : ""; // now repeat the same pattern for the 3 remaining

        $instance['mcw_display_option'] = !empty($new_instance['mcw_display_option']) ? sanitize_text_field($new_instance['mcw_display_option']) : ""; 

        $instance['mcw_number_of_posts'] = !empty($new_instance['mcw_number_of_posts']) ? sanitize_text_field($new_instance['mcw_number_of_posts']) : ""; 

        $instance['mcw_your_message'] = !empty($new_instance['mcw_your_message']) ? sanitize_text_field($new_instance['mcw_your_message']) : ""; 

        

        return $instance;
	
	}


    //3. Display Widget to Frontend
    public function widget( $args, $instance ) {
		$title = apply_filters("widget_title", $instance['mcw_title']);
        // $message = apply_filters("widget_message", $instance['mcw_your_message']);
        // $number_of_posts = apply_filters("widget_message", $instance['mcw_number_of_posts']);

        echo $args['before_widget'];
            echo $args['before_title'];
                echo $title;
            echo $args['after_title'];

            // Check for Display type
            if( $instance['mcw_display_option'] == "static_message"){
                echo $instance['mcw_your_message'];
            } elseif($instance['mcw_display_option'] == "recent_post"){

                //Code to display the posts blog title
                $query = new WP_Query(array(
                    "posts_per_page" => $instance['mcw_number_of_posts'],
                    "post_status" => "publish"
                ));

                if($query->have_posts()){
                    echo "<ul>";
                        while($query->have_posts()){
                            $query->the_post();
                            echo '<li> <a href="'.get_the_permalink().'">'.get_the_title().'</a> </li>';
                        }   
                    echo "</ul>";

                    wp_reset_postdata();
                }else{
                    echo "No post Found";
                }
            }
        echo $args['after_widget'];
	}
}