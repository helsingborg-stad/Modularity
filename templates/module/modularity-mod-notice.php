<?php $fields = get_fields($module->ID); ?>
<div class="notice <?php echo $fields['notice_type']; ?> <?php echo $fields['notice_size']; ?>">

    <div class="grid grid-table-md grid-va-middle no-margin no-padding">
        <?php if (!empty($fields['notice_icon'])) : ?>
        <div class="grid-fit-content"><i class="fa <?php echo $fields['notice_icon']; ?>"></i></div>
        <?php endif; ?>

        <div class="grid-auto">
            <?php echo $fields['notice_text']; ?>
        </div>
    </div>
</div>
