@include('partials.post-filters')

<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <ul>
        @if (count($posts) > 0)
            @foreach ($posts as $post)
            <li>
                @if (!empty($post->post_type) && $post->post_type == 'attachment')
                    <a href="{{ wp_get_attachment_url($post->ID) }}" target="_blank">
                @else
                    <a href="{{ $posts_data_source === 'input' ? $post->permalink : get_permalink($post->ID) }}">
                @endif
                    @if (in_array('title', $posts_fields))
                        <span class="link-item title">{!! apply_filters('the_title', $post->post_title) !!}</span>
                    @endif

                    @if (in_array('date', $posts_fields) && $posts_data_source !== 'input')
                    <time class="date text-sm text-dark-gray">{{ apply_filters('Modularity/Module/Posts/Date', get_the_time('Y-m-d', $post->ID), $post->ID, $post->post_type)  }}</time>
                    @endif
                </a>
            </li>
            @endforeach
        @else
            <li><?php _e('No posts to show…', 'modularity'); ?></li>
        @endif

        @if ($posts_data_source !== 'input' && isset($archive_link) && $archive_link && $archive_link_url)
        <li><a class="read-more" href="{{ $archive_link_url }}?{{ http_build_query($filters) }}"><?php _e('Show more', 'modularity'); ?></a></li>
        @endif
    </ul>
</div>
