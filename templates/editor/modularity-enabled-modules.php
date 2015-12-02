<div class="modularity-modules">

    <?php foreach ($modules as $moduleId => $module) : ?>
    <div class="modularity-module modularity-js-draggable" data-module-id="<?php echo $moduleId; ?>">
        <span class="modularity-module-icon">
            <?php echo modularity_decode_icon($module['menu_icon']); ?>
        </span>
        <span class="modularity-module-name"><?php echo $module['labels']['name']; ?></span>
    </div>
    <?php endforeach; ?>

</div>
