<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <ul>
        @if (count($posts) > 0)
            @foreach ($posts as $post)
            <li>
                <a href="{{ $posts_data_source === 'input' ? $post->permalink : get_permalink($post->ID) }}">
                    @if (in_array('title', $posts_fields))
                        <span class="link-item title">{{ apply_filters('the_title', $post->post_title) }}</span>
                    @endif

                    @if (in_array('date', $posts_fields) && $posts_data_source !== 'input')
                    <time class="date text-sm text-dark-gray">{{ get_the_time('Y-m-d', $post->ID) }}</time>
                    @endif
                </a>
            </li>
            @endforeach
        @else
            <li><?php _e('No posts to show…', 'modularity'); ?></li>
        @endif

        @if ($posts_data_source !== 'input' && isset($archive_link) && $archive_link)
        <li><a class="read-more" href="{{ get_post_type_archive_link($fields->posts_data_post_type) }}?{{ http_build_query($filters) }}"><?php _e('Show more', 'modularity'); ?></a></li>
        @endif
    </ul>
</div>
