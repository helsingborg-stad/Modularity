<?php
    $fields = json_decode(json_encode(get_fields($module->ID)));

    if (isset($fields->contacts) && count($fields->contacts) > 0) {
        $displayMode = 'cards';
        if (isset($fields->display_mode) && !empty($fields->display_mode)) {
            $displayMode = $fields->display_mode;
        }

        switch ($displayMode) {
            case 'list':
                include \Modularity\Helper\Wp::getTemplate($module->post_type . '-list', 'module/modularity-mod-contacts', false);
                break;

            default:
                include \Modularity\Helper\Wp::getTemplate($module->post_type . '-cards', 'module/modularity-mod-contacts', false);
                break;
        }
    } else {
?>

<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-card'), $module->post_type, $args)); ?>" itemscope="person" itemtype="http://schema.org/Organization">

    <?php
    if (isset($fields->picture) && !empty($fields->picture)) {
        $image = wp_get_attachment_image_src(
            $fields->picture->id,
            apply_filters('modularity/image/contact',
                municipio_to_aspect_ratio('16:9', array(400, 400)),
                $args
            )
        );
    } else {
        $image = false;
    }
    ?>

    <?php if ($image !== false) : ?>
    <img class="box-image" src="<?php echo $image[0]; ?>" alt="<?php echo $fields->first_name; ?> <?php echo $fields->last_name; ?>">
    <?php endif; ?>

    <?php if (!$module->hideTitle) : ?>
    <h4 class="box-title"><?php echo $module->post_title; ?></h4>
    <?php endif; ?>

    <div class="box-content">
        <h5 itemprop="name"><?php echo $fields->first_name; ?> <?php echo isset($fields->last_name) && !empty($fields->last_name) ? $fields->last_name : ''; ?></h5>
        <ul>
            <?php if ((isset($fields->title) && !empty($fields->title)) || (isset($fields->organization) && !empty($fields->organization))) : ?>
                <li class="card-title">
                    <span itemprop="jobTitle"><?php echo (isset($fields->title) && !empty($fields->title)) ? $fields->title : ''; ?></span>
                    <span itemprop="department"><?php echo (isset($fields->organization) && !empty($fields->organization)) ? $fields->organization : ''; ?></span>
                </li>
            <?php endif; ?>
            <?php if (isset($fields->phone_number) && !empty($fields->phone_number)) : ?><li><a itemprop="telephone" class="link-item" href="tel:<?php echo $fields->phone_number; ?>"><?php echo $fields->phone_number; ?></a></li><?php endif; ?>
            <?php if (isset($fields->email) && !empty($fields->email)) : ?><li><a itemprop="email" class="link-item truncate" href="mailto:<?php echo $fields->email; ?>"><?php echo $fields->email; ?></a></li><?php endif; ?>
            <?php if (!empty($module->post_content)) : ?><li class="small description"><?php echo apply_filters('the_content', $module->post_content); ?></li><?php endif; ?>
       </ul>
    </div>
</div>
<?php } ?>
