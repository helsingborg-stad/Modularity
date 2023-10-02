<div class="o-grid">
    <?php if (!$module->hideTitle && !empty($module->post_title)) : ?>
        <div class="o-grid-12@xs">
            <h2><?php echo $module->post_title; ?></h2>
        </div>
    <?php endif; ?>

<?php
$hasImages = false;
foreach ($posts as $post) {
    if (get_thumbnail_source($post->ID) !== false) {
        $hasImages = true;
    }
}
?>

<?php foreach ($posts as $post) :  ?>
    <?php
        $image = wp_get_attachment_image_src(
            get_post_thumbnail_id($post->ID),
            apply_filters('modularity/image/latest/box',
                municipio_to_aspect_ratio('16:9', array(400, 300)),
                $args
            )
        );

        //Make sorted by data avabile
        if (isset($fields->meta_key_output)) {
            $meta_data = get_post_meta($post->ID, $fields->meta_key_output, true);

            //Serialize data if needed
            if (is_array($meta_data) || is_object($meta_data)) {
                $meta_data = json_encode($meta_data);
            }
        } else {
            $meta_data = "";
        }

    ?>
    <div class="o-grid-12@lg o-grid-12@xl">
        <a href="<?php echo get_permalink($post->ID); ?>" class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-news', 'box-news-horizontal'), $module->post_type, $args)); ?>" data-meta-sort-by="<?php echo $meta_data; ?>">
            <?php if ($hasImages) : ?>
                <div class="box-image-container">
                    <?php if ($image && $fields->show_picture) : ?>
                    <img src="<?php echo $image[0]; ?>" alt="<?php echo $post->post_title; ?>">
                    <?php else : ?>
                    <figure class="image-placeholder"></figure>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="box-content" data-meta-sort-by="<?php echo $meta_data; ?>">
                <?php if ($fields->show_title) : ?>
                <h3 class="text-highlight"><?php echo apply_filters('the_title', $post->post_title); ?></h3>
                <?php endif; ?>


                <?php if ($fields->show_excerpt) : ?>
                <?php echo isset(get_extended($post->post_content)['main']) ? apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(strip_shortcodes(get_extended($post->post_content)['main'])), 30, null)) : ''; ?>
                <?php endif; ?>

                <p><span class="link-item"><?php _e('Read more', 'modularity'); ?></span></p>
            </div>
        </a>
    </div>
<?php endforeach; ?>
</div>
