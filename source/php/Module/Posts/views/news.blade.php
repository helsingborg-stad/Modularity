@include('partials.post-filters')

<div aria-label="yo">
    @if (!$hideTitle && !empty($postTitle))

        @typography([
            'element' => 'h4', 
            'variant' => 'h2', 
            'classList' => ['module-title']
        ])
            {!! $postTitle !!}
        @endtypography

    @endif

    @foreach ($posts as $post)

        <div class="o-grid u-margin__bottom--4">

            @if($post->showImage)

                <div class="o-grid-12@xs o-grid-4@md">
                    @if ($post->thumbnail && $post->showImage)

                        @image([
                            'src'=> $post->thumbnail[0],
                            'alt' => $post->post_title
                        ])
                        @endimage

                    @elseif($post->showImage)
                        @image([
                            'src'=> false,
                            'alt' => $post->post_title,
                        ])
                        @endimage
                    @endif
                </div>

            @endif

            <div class="o-grid-12@xs o-grid-{{$post->showImage ? '8' : '12' }}@md">

                @if ($post->showTitle)
                    @typography([
                        'element' => "h3",
                        'classList' => ['text-highlight']
                    ])
                        {!! $post->post_title !!}
                    @endtypography
                @endif

                @if($post->showDate)
                    @typography(['element' => 'span', 'variant' => 'meta'])
                        @date([
                            'action' => 'formatDate',
                            'timestamp' => $post->post_date
                        ])
                        @enddate
                    @endtypography
                @endif

                @if ($post->showExcerpt)
                    {!! $post->post_content !!}
                @endif

                @button([
                    'text' => __('Read more', 'modularity'),
                    'color' => 'default',
                    'style' => 'filled',
                    'href' => $post->link,
                    'classList' => ['u-margin__top--1', 'u-display--block@xs']
                ])
                @endbutton

            </div>

        </div>

    @endforeach


    @if ($posts_data_source !== 'input' && isset($archive_link) && $archive_link && $archive_link_url)
        <div class="t-read-more-section u-display--flex u-align-content--center u-margin__y--4">
            @button([
                'text' => __('Show more', 'modularity'),
                'color' => 'secondary',
                'style' => 'filled',
                'href' => $archive_link_url . "?" . http_build_query($filters),
                'classList' => ['u-flex-grow--1@xs']
            ])
            @endbutton
        </div>
    @endif
</div>