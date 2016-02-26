<?php
$fields = json_decode(json_encode(get_fields($module->ID)));

$sort = explode('_', $fields->sorted_by);

$posts = get_posts(array(
    'post_type' => $fields->post_type,
    'posts_per_page' => $fields->number_of_posts,
    'orderby' => $sort[0],
    'order' => $sort[1]
));

if ($fields->{'mod-latest-type'} == 'list') :
?>

<div class="box box-panel">
    <h4 class="box-title"><?php echo $module->post_title; ?></h4>
    <ul>
        <?php foreach ($posts as $post) : ?>
            <li>
                <a class="link-item" href="<?php echo get_permalink($post->ID); ?>">
                <?php echo (in_array('title', $fields->show)) ? apply_filters('the_title', $post->post_title) : ''; ?>
                    <?php if (in_array('date', $fields->show)) : ?>
                    <time class="date pull-right text-sm text-dark-gray"><?php echo date('Y-m-d', strtotime(get_the_time('Y-m-d H:i', $post->ID))); ?></time>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php else : ?>

<div class="grid">
<?php
foreach ($posts as $post) :
    $image = get_post_thumbnail_id($post->ID);
    $image = wp_get_attachment_url($image);
?>
<div class="grid-md-3">
    <a href="<?php echo get_permalink($post->ID); ?>" class="box box-news">
        <?php if ($image && in_array('picture', $fields->show)) : ?>
        <img src="<?php echo $image; ?>">
        <?php endif; ?>
        <div class="box-content">
            <?php if (in_array('title', $fields->show)) : ?>
            <h5 class="link-item link-item-light"><?php echo apply_filters('the_title', $post->post_title); ?></h5>
            <?php endif; ?>

            <?php if (in_array('date', $fields->show)) : ?>
            <p><?php echo get_the_time('Y-m-d H:i', $post->ID); ?></p>
            <?php endif; ?>

            <?php if (in_array('excerpt', $fields->show)) : ?>
            <p><?php echo isset(get_extended($post->post_content)['main']) ? get_extended($post->post_content)['main'] : ''; ?></p>
            <?php endif; ?>
        </div>
    </a>
</div>
<?php endforeach; ?>
</div>

<?php endif; ?>
