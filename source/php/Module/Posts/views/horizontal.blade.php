@include('partials.post-filters')

<div class="{{ $classes }} posts-{{ $posts_display_as }}">
    @includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
    @includeWhen($preamble, 'partials.preamble')

    @if (count($posts) > 0)
        <div class="o-grid grid--columns js-mod-posts-{{ $ID }} {{ $stretch ? 'o-grid--stretch' : '' }}">
            @foreach ($posts as $post)
                @if ($loop->first && get_field('posts_highlight', $ID))
                    <div class="o-grid-12@xs">

                        @link([
                            'href' => apply_filters('Modularity/Module/Posts/Permalink', get_permalink($post), $post)
                        ])
                            <article class="full u-mb-2">
                                @if (in_array('image', $posts_fields) && !empty($post->thumbnail[0]))
                                    @image([
                                        'src' => $post->thumbnail[0],
                                        'alt' => $post->post_title,
                                        'clasList' => ['u-mb-3', 'u-w-100', 'u-pb-0']
                                    ])
                                    @endimage
                                @endif

                                @if (in_array('title', $posts_fields))
                                    @typography([
                                        'element' => 'h2',
                                        'variant' => 'h2'
                                    ])
                                        {{ $post->post_title }}
                                    @endtypography
                                @endif

                                @if (in_array('date', $posts_fields))
                                    <time
                                        datetime="{{ get_the_time(get_option('date_format'), $post->ID) . ' ' . get_the_time(get_option('time_format'), $post->ID) }}">
                                        @if ($post->humanReadableTime)
                                            {{ $post->humanReadableTime }}
                                        @else
                                            {{ apply_filters('Modularity/Module/Posts/Date', get_the_time(get_option('date_format'), $post->ID) . ' ' . get_the_time(get_option('time_format'), $post->ID), $post->ID, $post->post_type, $posts_display_as) }}
                                        @endif
                                    </time>
                                @endif

                                @if (is_array($post->terms) && !empty($post->terms))
                                    @foreach ($post->terms as $term)
                                        @typography([
                                            'element' => 'span'
                                        ])
                                            - {{ $term->name }}
                                        @endtypography
                                    @endforeach
                                @endif

                                {!! isset(get_extended($post->post_content)['main'])
                                    ? apply_filters(
                                        'the_excerpt',
                                        wp_trim_words(wp_strip_all_tags(strip_shortcodes(get_extended($post->post_content)['main'])), 45, null),
                                    )
                                    : '' !!}

                            </article>
                        @endlink

                    </div>
                @else
                    @include('partials.post.post-horizontal')
                @endif
            @endforeach
        </div>

        @if (get_field('posts_count', $ID) > 0)
            <div class="o-grid">
                <div class="go-rid-12@xs text-center u-py-2">

                    @button([
                        'text' => 'Secondary',
                        'color' => 'default',
                        'style' => 'filled',
                        'attributeList' => [
                            'data-mod-posts-load-more' => base64_encode($loadMorePostsAttributes),
                        ],
                        'classList' => ['js-mod-posts-load-more'],
                    ])
                    {{ $loadMoreButtonText }}
                    @endbutton

                </div>
            </div>
        @endif
    @endif
</div>
