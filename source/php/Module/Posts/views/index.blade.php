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
    @foreach ($posts as $post)
        <div class="{{ $posts_columns }}">

            @card([
                'link' =>  $post->link,
                'classList' => $classes,

            ])
                <div class="c-card__image c-card__image--secondary">
                    <div class="c-card__image-background u-ratio-16-9" alt="{{ $contact['full_name'] }}" style="background-image:url('{{ $post->thumbnail[0] }}');"></div>
                </div>
            
                <div class="c-card__body">
                    @typography([
                        'element' => "h2",
                    ])
                        {{$post->showTitle ? $post->post_title : ''}}
                    @endtypography
           
                    {!! $post->post_content !!}
                    
                </div>
                @if($post->tags)
                    <div class="c-card__footer">
                        @tags ([$post->tags])
                        @endtags
                    </div>
                @endif
            @endcard

        </div>
    @endforeach
</div>

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