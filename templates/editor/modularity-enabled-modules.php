<div class="modularity-modules">
    <?php foreach ($modules as $moduleId => $module) : ?>
    <div class="modularity-module modularity-js-draggable" data-module-id="<?php echo $moduleId; ?>" data-sidebar-compability="<?php echo is_array($module['sidebar_compability']) ? implode(",", $module['sidebar_compability']) : ''; ?>">
        <span class="modularity-module-icon">
            <?php echo modularity_decode_icon($module); ?>
        </span>
        <span class="modularity-module-name"><?php echo $module['labels']['name']; ?></span>
    </div>
    <?php endforeach; ?>
</div>
