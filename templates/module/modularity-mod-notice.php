<?php $fields = get_fields($module->ID); ?>
<div class="notice <?php echo $fields['notice_type']; ?> <?php echo $fields['notice_size']; ?>">
    <div class="container">
        <div class="grid-table grid-va-middle">
            <?php if (!empty($fields['notice_icon'])) : ?>
            <div class="grid-fit-content" style="padding-right:10px;"><i class="fa <?php echo $fields['notice_icon']; ?>"></i></div>
            <?php endif; ?>

            <div class="grid-auto">
                <?php if (!$module->hideTitle) : ?>
                    <strong><?php echo $module->post_title; ?></strong>
                <?php endif; ?>
                <?php echo $fields['notice_text']; ?>
            </div>
        </div>
    </div>
</div>
