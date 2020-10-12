<div class="grid-xs-12 grid-md-6">
    @link([
        'href' => apply_filters('Modularity/Module/Posts/Permalink', get_permalink($post), $post)
    ])

        <article class="full u-mb-0">
            <div class="grid">
                @if (in_array('image', $posts_fields) && $post->image && !get_field('posts_display_magazine_show_image_on_first_post_only', $ID))
                    <div class="grid-xs-2 grid-md-3">

                        @image([
                            'src'=> $post->image,
                            'alt' => $post->post_title
                        ])
                        @endimage
                    </div>
                @endif

                <div class="grid-auto">
                    @if (in_array('title', $posts_fields))
                        @typography([
                            "element" => "h4",
                        ])
                            {{$post->post_title}}
                        @endtypography


                    @endif

                    @if (in_array('date', $posts_fields))
                        @date([
                            'action' => 'formatDate',
                            'timestamp' => $post->humanReadableTime ? $post->humanReadableTime : apply_filters('Modularity/Module/Posts/Date', get_the_time(get_option('date_format'), $post->ID) . ' ' . get_the_time(get_option('time_format'), $post->ID), $post->ID, $post->post_type, $posts_display_as)
                        ])
                        @enddate

                    @endif

                    @if (is_array($post->terms) && !empty($post->terms))
                        -
                        @foreach ($post->terms as $term)
                            @typography([
                                "element" => "span",
                            ])
                                {{$term->name}}
                            @endtypography

                        @endforeach
                    @endif

                    @if (in_array('excerpt', $posts_fields))
                        {!! isset(get_extended($post->post_content)['main']) ? apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(strip_shortcodes(get_extended($post->post_content)['main'])), 15, null)) : '' !!}
                    @endif

                </div>
            </div>
        </article>
    @endbutton
</div>
