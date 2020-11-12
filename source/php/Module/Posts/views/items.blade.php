@if (!$hideTitle && !empty($post_title))
    @typography([
        'element' => "h4",
        'classList' => ['module-title']
    ])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif

@include('partials.post-filters')

<div class="o-grid">
    @if (count($posts) > 0)

         @foreach ($posts as $post)

            <div class="{{ $posts_columns }}">

                @if (!empty($taxonomyDisplay['top']))
                    @tags([
                        'tags' => (new Modularity\Module\Posts\Helper\Tag)->getTags($post->ID, $taxonomyDisplay['top'])
                    ])
                    @endtags
                @endif

                @link([
                    'href' => $posts_data_source === 'input' ? $post->permalink : get_permalink ($post->ID),
                    'classList' => $classes
                ])

                    @if ($post->thumbnail && in_array('image', $posts_fields))

                        @image([
                            'src'=> $post->thumbnail[0],
                            'alt' => $post->post_title,
                            'classList' => ['box-image']
                        ])
                        @endimage

                    @else
                        <figure class="image-placeholder"></figure>
                    @endif

                @endlink


                    <article>

                        @if (in_array('title', $posts_fields))

                            @link([
                                'href' => $posts_data_source === 'input' ? $post->permalink : get_permalink ($post->ID),
                                'classList' => $classes
                            ])

                                @typography([
                                    'element' => "h5",
                                    'classList' => ['link-item', 'link-item-light']
                                ])
                                    {!! apply_filters('the_title', $post->post_title) !!}
                                @endtypography

                            @endlink

                        @endif

                        @if (in_array('date', $posts_fields) && $posts_data_source !== 'input')

                                @typography([
                                        'element' => "p"
                                ])

                                    @typography([
                                        'element' => "time"
                                    ])
                                        {!! apply_filters('Modularity/Module/Posts/Date',
                                        get_the_time('Y-m-d H:i', $post->ID), $post->ID, $post->post_type) !!}
                                    @endtypography

                                @endtypography

                        @endif

                        @if (in_array('excerpt', $posts_fields))
                            @if ($posts_data_source === 'input')
                                {!! $post->post_content !!}
                            @else
                                    @typography([
                                        'element' => "time"
                                    ])
                                        {!! isset(get_extended($post->post_content)['main']) ? apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(get_extended($post->post_content)['main']), 30, null)) : '' !!}
                                    @endtypography

                            @endif
                        @endif

                        @if(isset($taxonomyDisplay['below']))

                             @tags([
                                'tags' => (new Modularity\Module\Posts\Helper\Tag)->getTags($post->ID, $taxonomyDisplay['below'])
                            ])
                            @endtags
                            
                        @endif
                    </article>

            </div>
        @endforeach

    @else

        <div>

            @typography([
                'element' => "p"
            ])
                {!! _e('No posts to show…', 'modularity') !!}}
            @endtypography

        </div>

    @endif

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
