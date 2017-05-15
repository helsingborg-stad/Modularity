<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?>">

    <?php if (!$module->hideTitle && !empty($module->post_title)) : ?>
        <h4 class="box-title"><?php echo $module->post_title; ?></h4>
    <?php endif; ?>

    <?php if (isset($fields->posts_list_column_titles) && $fields->posts_list_column_titles) : ?>
    <header class="accordion-table accordion-table-head">
        <?php if (!isset($fields->posts_hide_title_column) || !$fields->posts_hide_title_column) : ?>
        <span class="column-header"><?php echo isset($fields->title_column_label) && !empty($fields->title_column_label) ? $fields->title_column_label : __('Title', 'modularity'); ?></span>
        <?php endif; ?>

        <?php foreach ($fields->posts_list_column_titles as $column) : ?>
            <span class="column-header"><?php echo $column->column_header; ?></span>
        <?php endforeach; ?>
    </header>
    <?php endif; ?>

    <div class="accordion accordion-icon accordion-list">
        <?php if (isset($fields->allow_freetext_filtering) && $fields->allow_freetext_filtering) : ?>
        <div class="accordion-search">
            <input type="text" name="accordion-search" placeholder="<?php _e('Filter on…', 'modularity'); ?>">
        </div>
        <?php endif; ?>

        <?php if (count($posts) > 0) : ?>
            <?php $i = 0; foreach ($posts as $post) : $i++; ?>
            <section class="accordion-section">
                <input type="radio" name="active-section" id="item-<?php echo $module->ID; ?>-<?php echo $post->ID; ?>">
                <label class="accordion-toggle" for="item-<?php echo $module->ID; ?>-<?php echo $post->ID; ?>">
                    <?php if (isset($fields->posts_list_column_titles) && count($fields->posts_list_column_titles) > 0) : ?>
                        <span class="accordion-table">
                        <?php
                        $column_values = array();
                        if ($fields->posts_data_source === 'input') {
                            if ($post->column_values !== false && count($post->column_values) > 0) {
                                foreach ($post->column_values as $key => $columnValue) {
                                    $column_values[sanitize_title($fields->posts_list_column_titles[$key]->column_header)] = $columnValue->value;
                                }
                            }
                        } else {
                            $column_values = get_post_meta($post->ID, 'modularity-mod-posts-expandable-list', true);
                        }
                        ?>
                        <?php if (isset($column_values) && !empty($column_values)) : ?>
                            <?php if (!isset($fields->posts_hide_title_column) || !$fields->posts_hide_title_column) : ?>
                            <span class="column-header"><?php echo apply_filters('the_title', $post->post_title); ?></span>
                            <?php endif; ?>

                            <?php if (is_array($fields->posts_list_column_titles)) : foreach ($fields->posts_list_column_titles as $column) : ?>
                                <span class="column-header"><?php echo isset($column_values[sanitize_title($column->column_header)]) ? $column_values[sanitize_title($column->column_header)] : ''; ?></span>
                            <?php endforeach; endif; ?>
                        <?php else : ?>
                            <span class="column-header"><?php echo apply_filters('the_title', $post->post_title); ?></span>
                        <?php endif; ?>

                        <?php if (isset($taxonomyDisplay['top'])) : ?>
                        <span class="column-header text-right">
                        <?php foreach ($taxonomyDisplay['top'] as $taxonomy => $placement) : $terms = wp_get_post_terms($post->ID, $taxonomy); if (count($terms) > 0) : ?>
                        <ul class="inline-block tags-<?php echo $taxonomy; ?>">
                            <?php foreach ($terms as $term) : ?>
                                <li class="tag tag-<?php echo $term->taxonomy; ?> tag-<?php echo $term->slug; ?>"><?php echo $term->name; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; endforeach; ?>
                        </span>
                        <?php endif; ?>

                        </span>
                    <?php else : ?>
                    <h2><?php echo apply_filters('the_title', $post->post_title); ?></h2>
                    <?php endif; ?>
                </label>
                <div class="accordion-content">
                    <article>
                        <?php echo apply_filters('the_content', $post->post_content); ?>
                    </article>

                    <?php if (isset($taxonomyDisplay['below'])) : ?>
                    <div class="gutter gutter-top">
                    <?php foreach ($taxonomyDisplay['below'] as $taxonomy => $placement) : $terms = wp_get_post_terms($post->ID, $taxonomy); if (count($terms) > 0) : ?>
                    <ul class="tags tags-<?php echo $taxonomy; ?>">
                        <?php foreach ($terms as $term) : ?>
                            <li class="tag tag-<?php echo $term->taxonomy; ?> tag-<?php echo $term->slug; ?>"><?php echo $term->name; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
            <?php endforeach; ?>
        <?php else : ?>
            <section class="accordion-section">
                <?php _e('Nothing to display…', 'modularity'); ?>
            </section>
        <?php endif; ?>
    </div>
</div>
