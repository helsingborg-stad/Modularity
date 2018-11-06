@include('partials.post-filters')

<div class="{{ $classes }}">

    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    @if (isset($posts_list_column_titles) && $posts_list_column_titles)
    <header class="accordion-table accordion-table-head">
        @if ($posts_hide_title_column)
        <span class="column-header">{{ isset($title_column_label) && !empty($title_column_label) ? $title_column_label : __('Title', 'modularity') }}</span>
        @endif

        @foreach ($posts_list_column_titles as $column)
            <span class="column-header">{{ $column->column_header }}</span>
        @endforeach
    </header>
    @endif

    <div class="accordion accordion-icon accordion-list">
        @if (!isset($allow_freetext_filtering) || $allow_freetext_filtering)
        <div class="accordion-search">
            <input type="text" name="accordion-search" placeholder="<?php _e('Filter on…', 'modularity'); ?>">
        </div>
        @endif

        @if (count($posts) > 0)
        @foreach ($posts as $post)

        <section class="accordion-section">
            <label tabindex="0" class="accordion-toggle" for="item-{{ $ID }}-{{ $post->ID }}">
                @if (!empty($posts_list_column_titles))
                    <span class="accordion-table">
                    @if (isset($post->column_values) && !empty($post->column_values))
                        @if ($posts_hide_title_column)
                        <span class="column-header">{{ apply_filters('the_title', $post->post_title) }}</span>
                        @endif

                        @if (is_array($posts_list_column_titles))
                        @foreach ($posts_list_column_titles as $column)

                            <span class="column-header">{{ isset($post->column_values[sanitize_title($column->column_header)]) ? $post->column_values[sanitize_title($column->column_header)] : '' }}</span>
                        @endforeach
                        @endif
                    @else
                        <span class="column-header"><?php echo apply_filters('the_title', $post->post_title); ?></span>
                    @endif

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
                @else
                    <h4><?php echo apply_filters('the_title', $post->post_title); ?></h4>
                @endif
            </label>
            <div class="accordion-content">
                <noscript>
                    <style type="text/css">
                        .accordion-content { display: block; }
                    </style>
                </noscript>
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

        @endforeach
        @else
        <section class="accordion-section">
            <?php _e('Nothing to display…', 'modularity'); ?>
        </section>
        @endif
    </div>
</div>
