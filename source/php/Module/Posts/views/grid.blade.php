<div class="grid">
    @if (!$hideTitle && !empty($post_title))
    <div class="grid-xs-12">
        <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    </div>
    @endif

    @if (count($posts) > 0)
    @foreach ($posts as $post)
        <div class="{{ isset($post->column_width) ? $post->column_width : $column_width }}">
            <a href="<?php echo get_permalink($post->ID); ?>" class="box box-post-brick" {!! isset($post->column_height) && !empty($post->column_height) ? 'style="padding-bottom:0;height:' . $post->column_height . '"' : '' !!}>
                @if (isset($post->thumbnail) && is_array($post->thumbnail))
                <div class="box-image" style="background-image:url({{ $post->thumbnail[0] }});">
                    <img src="{{ $post->thumbnail[0] }}" alt="{{ $post->post_title }}">
                </div>
                @endif

                <div class="box-content">
                    @if (in_array('date', $posts_fields) && $posts_data_source !== 'input')
                    <span class="box-post-brick-date">
                        <time>
                            {{ get_the_time(get_option('date_format'), $post->ID) }}
                            {{ get_the_time(get_option('time_format'), $post->ID) }}
                        </time>
                    </span>
                    @endif

                    @if (in_array('title', $posts_fields))
                    <h3 class="post-title">{{ apply_filters('the_title', $post->post_title) }}</h3>
                    @endif
                </div>

                @if (in_array('excerpt', $posts_fields))
                <div class="box-post-brick-lead">
                    {!! isset($extended['main']) && !empty($extended['main']) ? $extended['main'] : wp_trim_words(wp_strip_all_tags(strip_shortcodes($post->post_content)), 100, '') !!}
                </div>
                @endif
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
