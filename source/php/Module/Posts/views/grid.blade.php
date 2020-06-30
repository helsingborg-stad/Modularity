
@include('partials.post-filters')


<div class="grid">
    @if (!$hideTitle && !empty($post_title))

        @typography([
            'element' => "h4",
            'classList' => ['box-title']
        ])
            {!! apply_filters('the_title', $post_title) !!}
        @endtypography

    @endif

    @if (count($posts) > 0)
        @foreach ($posts as $post)
            <div class="{{ isset($post->column_width) ? $post->column_width : $column_width }}">
                <a href="{{ $posts_data_source === 'input' ? $post->permalink : get_permalink($post->ID) }}" class="box box-post-brick" {!! isset($post->column_height) && !empty($post->column_height) ? 'style="padding-bottom:0;height:' . $post->column_height . '"' : '' !!}>
                    @if (isset($post->thumbnail) && is_array($post->thumbnail))
                        <div class="box-image" style="background-image:url({{ $post->thumbnail[0] }});">

                            @image([
                                'src'=> $post->thumbnail[0],
                                'alt' => $post->post_title
                            ])
                            @endimage

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

                            @typography([
                                'element' => "h3",
                                'classList' => ['post-title']
                            ])
                                {!! apply_filters('the_title', $post->post_title) !!}
                            @endtypography

                        @endif
                    </div>

                    @if (in_array('excerpt', $posts_fields))
                        <div class="box-post-brick-lead">

                            @if(has_excerpt($post->ID))
                                {!! wp_strip_all_tags(strip_shortcodes(get_the_excerpt($post->ID))) !!}
                            @elseif(isset($extended['main']) && !empty($extended['main']))
                                {!! $extended['main'] !!}
                            @else
                                {!! wp_trim_words(wp_strip_all_tags(strip_shortcodes($post->post_content)), 100, '') !!}
                            @endif

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
