@include('partials.post-filters')

@if (!$hideTitle && !empty($post_title))
    @typography(['element' => 'h2'])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif

<div class="grid">
    @foreach ($posts as $post)
        <div class="{{ $posts_columns }}">
            
            @card([
                'heading' => $post->showTitle ? $post->post_title : '',
                'image' => $post->showImage ? [
                    'src' => $post->thumbnail[0],
                    'alt' => $post->post_title,
                    'backgroundColor' => 'secondary',
                    'padded' => false
                ] : false,
                'link' =>  $post->link,
                'classList' => $classes,
                'tags' => $post->tags
            ])

                @if($post->showDate) 
                    @date([
                        'action' => 'formatDate',
                        'timestamp' => $post->post_date
                    ])
                    @enddate
                @endif

                @if($post->showExcerpt) 
                    {!! $post->post_content !!}
                @endif

            @endcard

        </div>
    @endforeach
</div>

@if ($posts_data_source !== 'input' && isset($archive_link) && $archive_link && $archive_link_url)
    <div class="t-read-more-section u-display--flex u-align-content--center">
        @button([
            'text' => __('Show more', 'modularity'),
            'color' => 'secondary',
            'style' => 'filled',
            'href' => $archive_link_url ."?".http_build_query($filters),
            'classList' => ['u-flex-grow--1@xs']
        ])
        @endbutton
    </div>
@endif