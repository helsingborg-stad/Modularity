<div class="grid">
    <?php if (!$module->hideTitle && !empty($module->post_title)) : ?>
        <div class="grid-xs-12">
            <h4 class="box-title"><?php echo $module->post_title; ?></h4>
        </div>
    <?php endif; ?>

<?php
$hasImages = false;
foreach ($posts as $post) {
    if ($fields->posts_data_source === 'input') {
        if ($post->image) {
            $hasImages = true;
        }
    } else {
        if (get_thumbnail_source($post->ID) !== false) {
            $hasImages = true;
        }
    }
}
?>

<?php foreach ($posts as $post) :  ?>
    <?php
        $image_dimensions = array(400, 300);
        $image = null;

        if ($fields->posts_data_source !== 'input') {
            $image = wp_get_attachment_image_src(
                get_post_thumbnail_id($post->ID),
                apply_filters('modularity/image/posts/news',
                    municipio_to_aspect_ratio('16:9', $image_dimensions),
                    $args
                )
            );
        } else {
            if ($post->image) {
                $image = wp_get_attachment_image_src(
                    $post->image->ID,
                    apply_filters('modularity/image/posts/news',
                        municipio_to_aspect_ratio('16:9', $image_dimensions),
                        $args
                    )
                );
            }
        }
    ?>
    <div class="grid-xs-12">
        <a href="<?php echo $fields->posts_data_source === 'input' ? $post->permalink : get_permalink($post->ID); ?>" class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-news', 'box-news-horizontal'), $module->post_type, $args)); ?>">
            <?php if ($hasImages) : ?>
                <div class="box-image-container">
                    <?php if (isset($taxonomyDisplay['top'])) : foreach ($taxonomyDisplay['top'] as $taxonomy => $placement) : $terms = wp_get_post_terms($post->ID, $taxonomy); if (count($terms) > 0) : ?>
                        <ul class="tags-<?php echo $taxonomy; ?> pos-absolute-<?php echo $placement; ?>">
                            <?php foreach ($terms as $term) : ?>
                                <li class="tag tag-<?php echo $term->taxonomy; ?> tag-<?php echo $term->slug; ?>"><?php echo $term->name; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; endforeach; endif; ?>

                    <?php if ($image && in_array('image', $fields->posts_fields)) : ?>
                    <img src="<?php echo $image[0]; ?>" alt="<?php echo $post->post_title; ?>" class="box-image">
                    <?php else : ?>
                    <figure class="image-placeholder"></figure>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="box-content">
                <?php if (in_array('title', $fields->posts_fields)) : ?>
                <h3 class="text-highlight"><?php echo apply_filters('the_title', $post->post_title); ?></h3>
                <?php endif; ?>

                <?php if (in_array('excerpt', $fields->posts_fields)) : ?>
                <?php echo isset(get_extended($post->post_content)['main']) ? apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(strip_shortcodes(get_extended($post->post_content)['main'])), 30, null)) : ''; ?>
                <?php endif; ?>

                <p><span class="link-item"><?php _e('Read more', 'modularity'); ?></span></p>

                <?php if (isset($taxonomyDisplay['below'])) : ?>
                    <div class="gutter gutter-top">
                    <?php foreach ($taxonomyDisplay['below'] as $taxonomy => $placement) : $terms = wp_get_post_terms($post->ID, $taxonomy); if (count($terms) > 0) : ?>
                    <ul class="tags tags-<?php echo $taxonomy; ?>">
                        <?php foreach ($terms as $term) : ?>
                            <li class="tag tag-<?php echo $term->taxonomy; ?> tag-<?php echo $term->slug; ?>"><?php echo $term->name; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </a>
    </div>
<?php endforeach; ?>

<?php if ($fields->posts_data_source !== 'input' && isset($fields->archive_link) && $fields->archive_link) : ?>
<div class="grid-lg-12">
    <a class="read-more" href="<?php echo get_post_type_archive_link($fields->posts_data_post_type); echo '?' . http_build_query($filters);  ?>"><?php _e('Show more', 'modularity'); ?></a>
</div>
<?php endif; ?>
</div>
