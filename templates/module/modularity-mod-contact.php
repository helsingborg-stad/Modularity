<?php
    $fields = json_decode(json_encode(get_fields($module->ID)));
?>

<div itemtype="http://schema.org/Organization">
    <?php if (isset($fields->picture->sizes->large)) : ?>
    <img class="box-image" src="<?php echo $fields->picture->sizes->large; ?>">
    <?php endif; ?>

    <div class="box-content">
        <h5 itemprop="name"><?php echo $fields->first_name; ?> <?php echo $fields->last_name; ?></h5>
        <ul>
            <?php if ((isset($fields->title) && !empty($fields->title)) || (isset($fields->organization) && !empty($fields->organization))) : ?>
                <li class="card-title">
                    <span itemprop="jobTitle"><?php echo (isset($fields->title) && !empty($fields->title)) ? $fields->title : ''; ?></span>
                    <span itemprop="department"><?php echo (isset($fields->organization) && !empty($fields->organization)) ? $fields->organization : ''; ?></span>
                </li>
            <?php endif; ?>
            <?php if (isset($fields->phone_number) && !empty($fields->phone_number)) : ?><li><a itemprop="telephone" class="link-item" href="tel:<?php echo $fields->phone_number; ?>"><?php echo $fields->phone_number; ?></a></li><?php endif; ?>
            <?php if (isset($fields->email) && !empty($fields->email)) : ?><li><a itemprop="email" class="link-item" href="mailto:<?php echo $fields->email; ?>"><?php echo $fields->email; ?></a></li><?php endif; ?>
            <?php if (!empty($module->post_content)) : ?><li class="small description"><?php echo apply_filters('the_content', $module->post_content); ?></li><?php endif; ?>
       </ul>
    </div>
</div>
