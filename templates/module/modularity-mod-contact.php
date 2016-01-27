<?php
    $fields = json_decode(json_encode(get_fields($module->ID)));
?>

<div class="box box-card">
    <?php if (isset($fields->picture->sizes->large)) : ?>
    <img class="box-image" src="<?php echo $fields->picture->sizes->large; ?>">
    <?php endif; ?>

    <div class="box-content">
        <h5><?php echo $fields->first_name; ?> <?php echo $fields->last_name; ?></h5>
        <ul>
            <?php if ((isset($fields->title) && !empty($fields->title)) || (isset($fields->organization) && !empty($fields->organization))) : ?>
                <li class="card-title">
                    <span><?php echo (isset($fields->title) && !empty($fields->title)) ? $fields->title : ''; ?></span>
                    <span><?php echo (isset($fields->organization) && !empty($fields->organization)) ? $fields->organization : ''; ?></span>
                </li>
            <?php endif; ?>
            <?php if (isset($fields->phone_number) && !empty($fields->phone_number)) : ?><li><a class="link-item" href="tel:<?php echo $fields->phone_number; ?>"><?php echo $fields->phone_number; ?></a></li><?php endif; ?>
            <?php if (isset($fields->email) && !empty($fields->email)) : ?><li><a class="link-item" href="mailto:<?php echo $fields->email; ?>"><?php echo $fields->email; ?></a></li><?php endif; ?>
            <?php if (!empty($module->post_content)) : ?><li class="small description"><?php echo apply_filters('the_content', $module->post_content); ?></li><?php endif; ?>
       </ul>
    </div>
</div>
