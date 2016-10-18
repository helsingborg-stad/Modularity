<div class="grid">
<?php
$contacts = $fields->contacts;

foreach ($contacts as $contact) {

$info = array(
    'image' => null,
    'first_name' => null,
    'last_name' => null,
    'work_title' => null,
    'administration_unit' => null,
    'email' => null,
    'phone' => null,
    'address' => null,
    'visiting_address' => null,
    'opening_hours' => null
);

switch ($contact->acf_fc_layout) {
    case 'custom':
        $info = apply_filters('Modularity/mod-contacts/contact-info', array(
            'image'               => $contact->image,
            'first_name'          => $contact->first_name,
            'last_name'           => $contact->last_name,
            'work_title'          => $contact->work_title,
            'administration_unit' => $contact->administration_unit,
            'email'               => strtolower($contact->email),
            'phone'               => $contact->phone_numbers,
            'address'             => $contact->address,
            'visiting_address'    => $contact->visiting_address,
            'opening_hours'       => $contact->opening_hours,
            'other'               => $contact->other
        ), $contact);
        break;

    case 'user':
        $info = apply_filters('Modularity/mod-contacts/contact-info', array(
            'image'               => null,
            'first_name'          => $contact->user->user_firstname,
            'last_name'           => $contact->user->user_lastname,
            'work_title'          => null,
            'administration_unit' => null,
            'email'               => strtolower($contact->user->user_email),
            'phone'               => null,
            'address'             => null,
            'visiting_address'    => null,
            'opening_hours'       => null
        ), $contact);
        break;
}

if (isset($info['image']) && !empty($info['image'])) {
    $image = wp_get_attachment_image_src(
        $info['image']->id,
        apply_filters('Modularity/image/contact',
            municipio_to_aspect_ratio('16:9', array(400, 400)),
            $args
        )
    );
} else {
    $image = false;
}
?>

<div class="<?php echo isset($fields->columns) && !empty($fields->columns) ? $fields->columns : 'grid-md-12'; ?>">
    <div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-card'), $module->post_type, $args)); ?>" itemscope itemtype="http://schema.org/Person">
        <?php if ($image !== false) : ?>
        <img class="box-image" src="<?php echo $image[0]; ?>" alt="<?php echo $info['first_name']; ?> <?php echo isset($info['last_name']) && !empty($info['last_name']) ? $info['last_name'] : ''; ?>">
        <?php endif; ?>

        <?php if (!$module->hideTitle) : ?>
        <h4 class="box-title"><?php echo $module->post_title; ?></h4>
        <?php endif; ?>

        <div class="box-content">
            <h5 itemprop="name"><?php echo $info['first_name']; ?> <?php echo isset($info['last_name']) && !empty($info['last_name']) ? $info['last_name'] : ''; ?></h5>

            <ul>
            <?php if ((isset($info['work_title']) && !empty($info['work_title'])) || (isset($info['administration_unit']) && !empty($info['administration_unit']))) : ?>
            <li class="card-title">
                <span itemprop="jobTitle"><?php echo (isset($info['work_title']) && !empty($info['work_title'])) ? $info['work_title'] : ''; ?></span>
                <span itemprop="department"><?php echo (isset($info['administration_unit']) && !empty($info['administration_unit'])) ? $info['administration_unit'] : ''; ?></span>
            </li>
            <?php endif; ?>

            <?php if (isset($info['phone']) && !empty($info['phone'])) : foreach ($info['phone'] as $phone) : ?>
                <li><a itemprop="telephone" class="link-item" href="tel:<?php echo $phone->number; ?>"><?php echo $phone->number; ?></a></li>
            <?php endforeach; endif; ?>

            <?php if (isset($info['email']) && !empty($info['email'])) : ?><li><a itemprop="email" class="link-item truncate" href="mailto:<?php echo $info['email']; ?>"><?php echo $info['email']; ?></a></li><?php endif; ?>
            <?php if (!empty($module->post_content)) : ?><li class="small description"><?php echo apply_filters('the_content', $module->post_content); ?></li><?php endif; ?>
            </ul>

            <?php if (isset($info['address']) && !empty($info['address'])) : ?>
            <div class="gutter gutter-top small">
                <?php if (isset($info['visiting_address']) && !empty($info['visiting_address'])) : ?>
                    <strong><?php _e('Postal address', 'modularity'); ?></strong>
                <?php endif; ?>
                <?php echo $info['address']; ?>
            </div>
            <?php endif; ?>

            <?php if (isset($info['visiting_address']) && !empty($info['visiting_address'])) : ?>
            <div class="gutter gutter-top small">
                <?php if (isset($info['address']) && !empty($info['address'])) : ?>
                    <strong><?php _e('Visiting address', 'modularity'); ?></strong>
                <?php endif; ?>
                <?php echo $info['visiting_address']; ?>
            </div>
            <?php endif; ?>

            <?php if (isset($info['other']) && !empty($info['other'])) : ?>
            <div class="gutter gutter-top small">
                <?php echo $info['other']; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
}
?>
</div>
