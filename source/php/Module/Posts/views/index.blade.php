@include('partials.post-filters')

@if (!$hideTitle && !empty($post_title))
    @typography([
        'id' => 'mod-posts-' . $ID . '-label',
        'element' => 'h4', 
        'variant' => 'h2', 
        'classList' => ['module-title']
    ])
        {!! apply_filters('the_title', $post_title) !!} INDEXZ
    @endtypography
@endif

<div class="o-grid" aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}">
    @foreach ($posts as $post)
        <div class="{{ $posts_columns }}">

            @card([
                'link' =>  $post->link,
                'classList' => $classes,

            ])

                @if(isset($post->thumbnail[0]) && !empty($post->thumbnail[0]))
                    <div class="c-card__image c-card__image--secondary">
                        <div class="c-card__image-background u-ratio-16-9" alt="{{ $contact['full_name'] }}" style="height:initial; background-image:url('{{ $post->thumbnail[0] }}');"></div>
                    </div>
                @endif
            
                <div class="c-card__body">
                    @typography([
                        'element' => "h2",
                        'classList' => ['c-card__heading'],
                    ])
                        {{$post->showTitle ? $post->post_title : ''}}
                    @endtypography
           
                    {!! $post->post_content !!}
                    
                </div>
                @if($post->tags)
                    <div class="c-card__footer">
                        @tags (['tags' => $post->tags])
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