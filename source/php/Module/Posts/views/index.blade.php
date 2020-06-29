@include('partials.post-filters')

<div class="grid" data-equal-container>

    @if (!$hideTitle && !empty($post_title))

        @typography([
            'element' => "h4",
            'classList' => ['box-title']
        ])
            {!! apply_filters('the_title', $post_title) !!}
        @endtypography

    @endif

    @foreach ($posts as $post)
        <div class="{{ $posts_columns }}">
            @if ($post->thumbnail && in_array('image', $posts_fields))

                <div class="box-image-container">

                    @tags([
                        'tags' => (new Modularity\Module\Posts\Helper\Tag)->getTags($post->ID, $taxonomyDisplay['top'])
                    ])
                    @endtags

                    @link([
                        'href' => $posts_data_source === 'input' ? $post->permalink : get_permalink ($post->ID),
                        'classList' => $classes
                    ])

                        @if ($post->thumbnail && in_array('image', $posts_fields))

                            @image([
                                'src'=> $post->thumbnail[0],
                                'alt' => $post->post_title,
                                'classList' => ['box-image'],
                            ])
                            @endimage

                        @else
                            <figure class="image-placeholder"></figure>
                        @endif
                    @endlink

                </div>
            @endif

            <div class="box-content">
                @if (in_array('title', $posts_fields))

                    @link([
                        'href' => $posts_data_source === 'input' ? $post->permalink : get_permalink ($post->ID),
                        'classList' => $classes
                    ])

                        @typography([
                            'element' => "h5",
                        'classList' => ['box-index-title', 'link-item-light']
                        ])
                            {!! apply_filters('the_title', $post->post_title) !!}
                        @endtypography

                    @endlink

                @endif

                @if (in_array('excerpt', $posts_fields))

                     @link([
                        'href' => $posts_data_source === 'input' ? $post->permalink : get_permalink ($post->ID),
                        'classList' => $classes
                    ])
                        {!! isset(get_extended($post->post_content)['main']) ? apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(strip_shortcodes(get_extended($post->post_content)['main'])), 30, null)) : '' !!}
                    @endlink

                @endif

                @if (isset($taxonomyDisplay['below']))
                    <div class="gutter gutter-top">
                        @tags([
                            'tags' => (new Modularity\Module\Posts\Helper\Tag)->getTags($post->ID, $taxonomyDisplay['below'])
                        ])
                        @endtags
                    </div>
                @endif

            </div>
        </div>
    @endforeach

    @if ($posts_data_source !== 'input' && isset($archive_link) && $archive_link && $archive_link_url)
        <div>

            @link([
                'href' => $archive_link_url ."?".http_build_query($filters) ,
                    'classList' => ['read-more']
            ])
                {{_e('Show more', 'modularity')}}
            @endlink

        </div>
    @endif

</div>
