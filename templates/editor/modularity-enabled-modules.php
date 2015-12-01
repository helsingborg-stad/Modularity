<div class="modularity-modules">

    <?php foreach ($modules as $module) : ?>
    <div class="modularity-module">
        <span class="modularity-module-icon">
            <?php echo modularity_decode_icon($module['menu_icon']); ?>
        </span>
        <span class="modularity-module-name"><?php echo $module['labels']['name']; ?></span>
    </div>
    <?php endforeach; ?>

</div>
