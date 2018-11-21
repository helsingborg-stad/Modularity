@include('partials.post-filters')

<div class="{{ $classes }} posts-{{$posts_display_as}}">
        @if (!$hideTitle && !empty($post_title))
            <h4 class="box-title u-mb-4">{!! apply_filters('the_title', $post_title) !!}</h4>
        @endif

        @if (count($posts) > 0)
            <div class="grid grid--columns js-mod-posts-{{$ID}}">
                @foreach ($posts as $post)
                    @if ($loop->first && get_field('posts_highlight', $ID))
                        <div class="grid-xs-12">
                            <a href="{{apply_filters('Modularity/Module/Posts/Permalink', get_permalink($post), $post)}}">
                                <article class="full u-mb-0">
                                    @if (in_array('image', $posts_fields) && $post->image)
                                        <img class="u-mb-3 u-w-100 u-pb-0" src="{{ $post->image }}" alt="{{ $post->post_title }}">
                                    @endif

                                    @if (in_array('title', $posts_fields))
                                        <h4 class="h2">{{$post->post_title}}</h4>
                                    @endif

                                    @if (in_array('date', $posts_fields))
                                        <time datetime="{{get_the_time(get_option('date_format'), $post->ID) . ' ' . get_the_time(get_option('time_format'), $post->ID)}}">
                                            @if ($post->humanReadableTime)
                                                {{$post->humanReadableTime}}
                                            @else
                                            {{ apply_filters('Modularity/Module/Posts/Date', get_the_time(get_option('date_format'), $post->ID) . ' ' . get_the_time(get_option('time_format'), $post->ID), $post->ID, $post->post_type, $posts_display_as) }}
                                             @endif
                                        </time>
                                    @endif

                                    @if (is_array($post->terms) && !empty($post->terms))
                                        @foreach ($post->terms as $term)
                                            <span> - {{$term->name}}</span>
                                        @endforeach
                                    @endif

                                    @if (in_array('excerpt', $posts_fields))
                                        {!! isset(get_extended($post->post_content)['main']) ? apply_filters('the_excerpt', wp_trim_words(wp_strip_all_tags(strip_shortcodes(get_extended($post->post_content)['main'])), 45, null)) : '' !!}
                                    @endif
                                </article>
                            </a>
                        </div>
                    @else
                        @include('partials.post.post-horizontal')
                    @endif
                @endforeach
            </div>

            @if (get_field('posts_count', $ID) > 0)
                <div class="grid">
                    <div class="grid-xs-12 text-center u-py-2">
                        <button class="btn btn-primary js-mod-posts-load-more" data-mod-posts-load-more="{{$loadMorePostsAttributes}}">{{$loadMoreButtonText}}</button>
                    </div>
                </div>
            @endif
        @else

        <section>
            <?php _e('Nothing to display…', 'modularity'); ?>
        </section>
        @endif
</div>
