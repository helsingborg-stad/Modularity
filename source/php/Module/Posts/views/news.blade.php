@include('partials.post-filters')

<div class="grid">
    @if (!$hideTitle && !empty($post_title))
    <div class="grid-xs-12">
        <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    </div>
    @endif

    @foreach ($posts as $post)
    <div class="grid-xs-12">
        <a href="{{ $posts_data_source === 'input' ? $post->permalink : get_permalink($post->ID) }}" class="{{ $classes }}">
            @if ($hasImages)
                <div class="box-image-container">
                    <?php if (isset($taxonomyDisplay['top'])) : foreach ($taxonomyDisplay['top'] as $taxonomy => $placement) : $terms = wp_get_post_terms($post->ID, $taxonomy); if (count($terms) > 0) : ?>
                        <ul class="tags-<?php echo $taxonomy; ?> pos-absolute-<?php echo $placement; ?>">
                            <?php foreach ($terms as $term) : ?>
                                <li class="tag tag-<?php echo $term->taxonomy; ?> tag-<?php echo $term->slug; ?>"><?php echo $term->name; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; endforeach; endif; ?>

                    @if ($post->thumbnail && in_array('image', $posts_fields))
                    <img src="{{ $post->thumbnail[0] }}" alt="{{ $post->post_title }}" class="box-image">
                    @else
                    <figure class="image-placeholder"></figure>
                    @endif
                </div>
            @endif

            <div class="box-content">
                @if (in_array('title', $posts_fields))
                <h3 class="text-highlight">{!! apply_filters('the_title', $post->post_title) !!}</h3>
                @endif

                @if (in_array('excerpt', $posts_fields))
                {!! isset(get_extended($post->post_content)['main']) ? apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(strip_shortcodes(get_extended($post->post_content)['main'])), 30, null)) : '' !!}
                @endif

                <p><span class="link-item"><?php _e('Read more', 'modularity'); ?></span></p>

                @if (isset($taxonomyDisplay['below']))
                    <div class="gutter gutter-top">
                        <?php foreach ($taxonomyDisplay['below'] as $taxonomy => $placement) : $terms = wp_get_post_terms($post->ID, $taxonomy); if (count($terms) > 0) : ?>
                        <ul class="tags tags-<?php echo $taxonomy; ?>">
                            <?php foreach ($terms as $term) : ?>
                                <li class="tag tag-<?php echo $term->taxonomy; ?> tag-<?php echo $term->slug; ?>"><?php echo $term->name; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; endforeach; ?>
                    </div>
                @endif
            </div>
        </a>
    </div>
    @endforeach


    @if ($posts_data_source !== 'input' && isset($archive_link) && $archive_link && $archive_link_url)
    <div class="grid-lg-12">
        <a class="read-more" href="{{ $archive_link_url }}?{{ http_build_query($filters) }}"><?php _e('Show more', 'modularity'); ?></a>
    </div>
    @endif
</div>
