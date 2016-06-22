<div class="box no-padding">
<div class="accordion accordion-icon accordion-list">
<?php
$contacts = $fields->contacts;

$i = 0;
foreach ($contacts as $contact) {
    $i++;

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
            'phone'               => $contact->phone,
            'address'             => $contact->address,
            'visiting_address'    => $contact->visiting_address,
            'opening_hours'       => $contact->opening_hours
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
            array(400, 400),
            $args
        )
    );
} else {
    $image = false;
}
?>
    <section class="accordion-section" itemscope="person" itemtype="http://schema.org/Organization">
        <input type="checkbox" name="active-section" id="accordion-section-<?php echo $i; ?>">
        <label class="accordion-toggle" for="accordion-section-<?php echo $i; ?>">
            <h3 itemprop="name"><?php echo $info['first_name']; ?> <?php echo $info['last_name']; ?></h3>
        </label>
        <div class="accordion-content">
            <ul>
                <?php if ((isset($info['work_title']) && !empty($info['work_title'])) || (isset($info['administration_unit']) && !empty($info['administration_unit']))) : ?>
                    <li class="card-title">
                        <span itemprop="jobTitle"><?php echo (isset($info['work_title']) && !empty($info['work_title'])) ? $info['work_title'] : ''; ?></span>
                        <span itemprop="department"><?php echo (isset($info['administration_unit']) && !empty($info['administration_unit'])) ? $info['administration_unit'] : ''; ?></span>
                    </li>
                <?php endif; ?>
                <?php if (isset($info['phone']) && !empty($info['phone'])) : ?><li><a itemprop="telephone" class="link-item" href="tel:<?php echo $info['phone']; ?>"><?php echo $info['phone']; ?></a></li><?php endif; ?>
                <?php if (isset($info['email']) && !empty($info['email'])) : ?><li><a itemprop="email" class="link-item" href="mailto:<?php echo $info['email']; ?>"><?php echo $info['email']; ?></a></li><?php endif; ?>
                <?php if (!empty($module->post_content)) : ?><li class="small description"><?php echo apply_filters('the_content', $module->post_content); ?></li><?php endif; ?>
           </ul>
        </div>
    </section>

<?php
}
?>
</div>
</div>
