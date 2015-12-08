<?php
$fields = get_fields($module->ID);
?>

<ul class="gallery">
    <?php foreach ($fields['gallery'] as $image) : ?>
    <li class="gallery-item">
        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
    </li>
    <?php endforeach; ?>
</ul>
