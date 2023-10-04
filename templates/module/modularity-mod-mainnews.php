<?php
    $news = get_field('main_news', $module->ID);
    if (count($news) > 0) :

    $hasImages = false;

    foreach ($news as $item) {
        $item = $item['news_item'];
        if (get_thumbnail_source($item->ID) !== false) {
            $hasImages = true;
        }
    }
?>
<div class="o-grid">
    <?php if (!$module->hideTitle && !empty($module->post_title)) : ?>
        <div class="o-grid-12@xs">
            <h2><?php echo $module->post_title; ?></h2>
        </div>
    <?php endif; ?>

    <?php foreach ($news as $item) : $item = $item['news_item']; ?>
        <?php
            $thumbnail_image = wp_get_attachment_image_src(
                get_post_thumbnail_id($item->ID),
                apply_filters('modularity/image/mainnews',
                    municipio_to_aspect_ratio('16:9', array(500, 250)),
                    $args
                )
            );
        ?>
        <div class="o-grid-12@lg o-grid-12@xl">
            <a href="<?php echo get_permalink($item->ID); ?>" class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-news', 'box-news-horizontal'), $module->post_type, $args)); ?>">
                <?php if ($hasImages) : ?>
                    <div class="box-image-container">
                        <?php if ($thumbnail_image) : ?>
                        <img src="<?php echo $thumbnail_image[0]; ?>" alt="<?php echo $item->post_title; ?>">
                        <?php else : ?>
                        <figure class="image-placeholder"></figure>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="box-content">
                    <h3 class="text-highlight"><?php echo apply_filters('the_title', $item->post_title); ?></h3>
                    <p><?php echo isset(get_extended($item->post_content)['main']) ? get_extended($item->post_content)['main'] : ''; ?></p>
                    <p><span class="link-item"><?php _e('Read more', 'modularity'); ?></span></p>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
