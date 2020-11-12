
@include('partials.post-filters')

@if (!$hideTitle && !empty($post_title))
    @typography([
        'element' => 'h4', 
        'variant' => 'h2', 
        'classList' => ['module-title']
    ])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif

<div class="o-grid">
    @if (count($posts) > 0)
        @foreach ($posts as $post)
            <div class="{{ isset($post->column_width) ? $post->column_width : $column_width }}">

                    @if (isset($post->thumbnail) && is_array($post->thumbnail))
                        <div class="box-image" style="background-image:url({{ $post->thumbnail[0] }});">

                            @link([
                                'href' => $posts_data_source === 'input' ? $post->permalink : get_permalink ($post->ID),
                                'classList' => ['box', 'box-post-brick'],
                                'attributeList' => ['style' =>  isset($post->column_height) && !empty($post->column_height) ?'padding-bottom:0;height:' .$post->column_height  : '']
                            ])
                                @image([
                                    'src'=> $post->thumbnail[0],
                                    'alt' => $post->post_title
                                ])
                                @endimage

                            @endlink
                        </div>
                    @endif

                    <div class="box-content">
                        @if (in_array('date', $posts_fields) && $posts_data_source !== 'input')

                            @typography([
                                    'element' => "span",
                                    'classList' => ['box-post-brick-date']
                                ])
                                @typography([
                                    'element' => "time"
                                ])
                                    {{ apply_filters('Modularity/Module/Posts/Date', get_the_time(get_option('date_format'), $post->ID) . ' ' . get_the_time(get_option('time_format'), $post->ID), $post->ID, $post->post_type) }}
                                @endtypography
                            @endtypography

                        @endif

                        @if (in_array('title', $posts_fields))
                           @link([
                                'href' => $posts_data_source === 'input' ? $post->permalink : get_permalink ($post->ID),
                                'classList' => ['box', 'box-post-brick'],
                                'attributeList' => ['style' =>  isset($post->column_height) && !empty($post->column_height) ?'padding-bottom:0;height:' .$post->column_height  : '']
                           ])
                                @typography([
                                    'element' => "h3",
                                    'classList' => ['post-title']
                                ])
                                    {!! apply_filters('the_title', $post->post_title) !!}
                                @endtypography
                            @endlink
                        @endif
                    </div>

                    @if (in_array('excerpt', $posts_fields))
                        <div class="box-post-brick-lead">

                            @link([
                                'href' => $posts_data_source === 'input' ? $post->permalink : get_permalink ($post->ID),
                                'classList' => ['box', 'box-post-brick'],
                                'attributeList' => ['style' =>  isset($post->column_height) && !empty($post->column_height) ?'padding-bottom:0;height:' .$post->column_height  : '']
                            ])
                                @if(has_excerpt($post->ID))
                                    {!! wp_strip_all_tags(strip_shortcodes(get_the_excerpt($post->ID))) !!}
                                @elseif(isset($extended['main']) && !empty($extended['main']))
                                    {!! $extended['main'] !!}
                                @else
                                    {!! wp_trim_words(wp_strip_all_tags(strip_shortcodes($post->post_content)), 100, '') !!}
                                @endif

                            @endlink

                        </div>
                    @endif

            </div>
        @endforeach
    @else

        <div class="o-grid-12@md">
            <?php _e('No posts to show…', 'modularity'); ?>
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
