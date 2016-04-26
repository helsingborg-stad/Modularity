<div class="accordion accordion-icon accordion-list">
    <?php if (isset($fields->posts_list_column_titles) && count($fields->posts_list_column_titles) > 0) : ?>
    <header class="accordion-table accordion-table-head">
        <span class="column-header"><?php _e('Title', 'modularity'); ?></span>
        <?php foreach ($fields->posts_list_column_titles as $column) : ?>
            <span class="column-header"><?php echo $column->column_header; ?></span>
        <?php endforeach; ?>
    </header>
    <?php endif; ?>

    <?php if (count($posts) > 0) : ?>
    <?php $i = 0; foreach ($posts as $post) : $i++; ?>
    <section class="accordion-section">
        <input type="radio" name="active-section" id="<?php echo $post->post_name; ?>">
        <label class="accordion-toggle" for="<?php echo $post->post_name; ?>">
            <?php if (isset($fields->posts_list_column_titles) && count($fields->posts_list_column_titles) > 0) : ?>
                <span class="accordion-table">
                <?php $column_values = get_post_meta($post->ID, 'modularity-mod-posts-expandable-list', true); ?>
                <?php if (isset($column_values) && !empty($column_values)) : ?>
                    <span class="column-header"><?php echo apply_filters('the_title', $post->post_title); ?></span>
                    <?php foreach ($fields->posts_list_column_titles as $column) : ?>
                        <span class="column-header"><?php echo isset($column_values[sanitize_title($column->column_header)]) ? $column_values[sanitize_title($column->column_header)] : ''; ?></span>
                    <?php endforeach; ?>
                <?php else : ?>
                    <span class="column-header"><?php echo apply_filters('the_title', $post->post_title); ?></span>
                <?php endif; ?>
                </span>
            <?php else : ?>
            <h2><?php echo apply_filters('the_title', $post->post_title); ?></h2>
            <?php endif; ?>
        </label>
        <div class="accordion-content">
            <?php echo apply_filters('the_content', $post->post_content); ?>
        </div>
    </section>
    <?php endforeach; ?>
    <?php else : ?>
        <section class="accordion-section">
            <?php _e('Nothing to display…', 'modularity'); ?>
        </section>
    <?php endif; ?>
</div>
