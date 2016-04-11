<div class="grid">
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
                array(400, 300),
                $args
            )
        );
    ?>
    <div class="grid-lg-12">
        <a href="<?php echo get_permalink($post->ID); ?>" class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-news', 'box-news-horizontal'), $module->post_type, $args)); ?>">
            <?php if ($hasImages) : ?>
                <div class="box-image-container">
                    <?php if ($image && $fields->show_picture) : ?>
                    <img src="<?php echo $image[0]; ?>" alt="<?php echo $post->post_title; ?>">
                    <?php else : ?>
                    <figure class="image-placeholder"></figure>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="box-content">
                <?php if ($fields->show_title) : ?>
                <h3 class="text-highlight"><?php echo apply_filters('the_title', $post->post_title); ?></h3>
                <?php endif; ?>


                <?php if ($fields->show_excerpt) : ?>
                <?php echo isset(get_extended($post->post_content)['main']) ? apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(get_extended($post->post_content)['main']), 30, null)) : ''; ?>
                <?php endif; ?>

                <p><span class="link-item"><?php _e('Read more', 'modularity'); ?></span></p>
            </div>
        </a>
    </div>
<?php endforeach; ?>
</div>
