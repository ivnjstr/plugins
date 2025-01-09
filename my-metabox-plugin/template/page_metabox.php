<?php
    $post_id = isset($post->ID) ? $post->ID : ""; // to remain value sa input pag ni reload
    $title = get_post_meta($post_id, "pmeta_title", true); // to remain value sa input pag ni reload
    $description = get_post_meta($post_id, "pmeta_description", true); // to remain value sa input pag ni reload

    wp_nonce_field("mmp_save_metabox_data", "mmp_save_pmetabox_nonce");
?>
<p>
    <label for="pmeta_title">Meta Title</label>
    <input type="text" name="pmeta_title" placeholder="Meta Title...." id="pmeta_title" value="<?php echo $title; ?>"> <!-- add value to remain value sa input pag ni reload -->
<p>
    <label for="pmeta_description">Meta Description</label>
    <input type="text" name="pmeta_description" placeholder="Meta Description...." id="pmeta_description" value="<?php echo $description; ?>"> <!-- add value to remain value sa input pag ni reload -->
</p>

