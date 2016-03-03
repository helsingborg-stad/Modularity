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
<div class="grid">
    <?php foreach ($news as $item) : $item = $item['news_item']; ?>
        <?php $thumbnail_image = get_thumbnail_source($item->ID); ?>
        <div class="grid-lg-12">
            <a href="<?php echo get_permalink($item->ID); ?>" class="box box-news box-news-horizontal">
                <?php if ($hasImages) : ?>
                    <div class="box-image-container">
                        <?php if (get_thumbnail_source($item->ID)) : ?>
                        <img src="<?php echo get_thumbnail_source($item->ID); ?>" alt="<?php echo $item->post_title; ?>">
                        <?php else : ?>
                        <figure class="image-placeholder"></figure>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="box-content">
                    <h3 class="text-highlight"><?php echo apply_filters('the_title', $item->post_title); ?></h3>
                    <p><?php echo isset(get_extended($item->post_content)['main']) ? get_extended($item->post_content)['main'] : ''; ?></p>
                    <p><span class="link-item">Read more</span></p>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
