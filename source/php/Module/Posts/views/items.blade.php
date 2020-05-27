@include('partials.post-filters')

<div class="grid" data-equal-container>
    @if (!$hideTitle && !empty($post_title))
    <div class="grid-xs-12">
        <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    </div>
    @endif

    @if (count($posts) > 0)
    @foreach ($posts as $post)
        <div class="{{ $posts_columns }}">
            <a href="{{ $posts_data_source === 'input' ? $post->permalink : get_permalink($post->ID) }}" class="{{ $classes }}" data-equal-item>
                @if ($post->thumbnail && in_array('image', $posts_fields))
                    <div class="box-image-container">
                        <?php if (isset($taxonomyDisplay['top'])) : foreach ($taxonomyDisplay['top'] as $taxonomy => $placement) : $terms = wp_get_post_terms($post->ID, $taxonomy); if (count($terms) > 0) : ?>
                            <ul class="tags-<?php echo $taxonomy; ?> pos-absolute-<?php echo $placement; ?>">
                                <?php foreach ($terms as $term) : ?>
                                    <li class="tag tag-<?php echo $term->taxonomy; ?> tag-<?php echo $term->slug; ?>"><?php echo $term->name; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; endforeach; endif; ?>

                        <img src="{{ $post->thumbnail[0] }}" alt="{{ $post->post_title }}" class="box-image">
                    </div>
                @endif

                <div class="box-content">
                    @if (in_array('title', $posts_fields))
                    <h5 class="link-item link-item-light">{!! apply_filters('the_title', $post->post_title) !!}</h5>
                    @endif

                    @if (in_array('date', $posts_fields) && $posts_data_source !== 'input')
                    <p><time>{{ apply_filters('Modularity/Module/Posts/Date', get_the_time('Y-m-d H:i', $post->ID), $post->ID, $post->post_type)  }}</time></p>
                    @endif

                    @if (in_array('excerpt', $posts_fields))
                        @if ($posts_data_source === 'input')
                            {!! $post->post_content !!}
                        @else
                            <p>{!! isset(get_extended($post->post_content)['main']) ? apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(get_extended($post->post_content)['main']), 30, null)) : '' !!}</p>
                        @endif
                    @endif

                    <?php if (isset($taxonomyDisplay['below'])) : foreach ($taxonomyDisplay['below'] as $taxonomy => $placement) : $terms = wp_get_post_terms($post->ID, $taxonomy); if (count($terms) > 0) : ?>
                        <ul class="tags tags-<?php echo $taxonomy; ?>">
                            <?php foreach ($terms as $term) : ?>
                                <li class="tag tag-<?php echo $term->taxonomy; ?> tag-<?php echo $term->slug; ?>"><?php echo $term->name; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; endforeach; endif; ?>
                </div>
            </a>
        </div>
    @endforeach
    @else

    <div class="grid-md-12">
        <?php _e('No posts to show…', 'modularity'); ?>
    </div>

    @endif

    @if ($posts_data_source !== 'input' && isset($archive_link) && $archive_link && $archive_link_url)
    <div class="grid-lg-12">
        <a class="read-more" href="{{ $archive_link_url }}?{{ http_build_query($filters) }}"><?php _e('Show more', 'modularity'); ?></a>
    </div>
    @endif
</div>
