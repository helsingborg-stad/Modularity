@include('partials.post-filters')

<div class="o-grid">

    @typography([
        'element' => "h4",
        'classList' => ['box-title']
    ])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography

    @foreach ($posts as $post)
        <div class="<?php echo apply_filters('Municipio/Controller/Archive/GridColumnClass', $posts_columns, ''); ?>">

                @if ($hasImages)
                    <div class="box-image-container">

                        @if (isset($taxonomyDisplay['top']))
                            @tags([
                                'tags' => (new Modularity\Module\Posts\Helper\Tag)->getTags($post->ID, $taxonomyDisplay['top'])
                            ])
                            @endtags
                        @endif

                        @if ($post->thumbnail && in_array('image', $posts_fields))

                            @link([
                                'href' => $posts_data_source === 'input' ? $post->permalink : get_permalink ($post->ID),
                                'classList' => $classes
                            ])

                                @image([
                                    'src'=> $post->thumbnail[0],
                                    'alt' => $post->post_title,
                                    'classList' => ['box-image'],
                                ])
                                @endimage

                            @endlink

                        @endif
                    </div>
                @endif

                <div class="box-content">
                    @if (in_array('title', $posts_fields))
                        @link([
                            'href' => $posts_data_source === 'input' ? $post->permalink : get_permalink ($post->ID),
                            'classList' => $classes
                        ])

                            @typography([
                                'element' => "h3"
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
