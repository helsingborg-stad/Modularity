@include('partials.post-filters')

@if (!$hideTitle && !empty($postTitle))
    @typography([
        'id' => 'mod-posts-' . $ID . '-label',
        'element' => 'h4', 
        'variant' => 'h2', 
        'classList' => ['module-title']
    ])
        {!! $postTitle !!}
    @endtypography
@endif

<div class="o-grid {{ $stretch ? 'o-grid--stretch' : '' }} {{ $noGutter ? 'o-grid--no-gutter' : '' }}" aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}">
    @foreach ($posts as $post)
        <div class="{{ $posts_columns }}">
            @card([
                'link' => $post->link,
                'imageFirst' => true,
                'image' =>  $post->thumbnail,
                'heading' => $post->post_title,
                'classList' => $classes,
                'hasFooter' => $post->tags ? true : false,
                'context' => ['module.posts.index'],
                'content' => $post->post_content,
                'tags' => $post->tags,
                'date' => $post->postDate,
                'containerAware' => true,
                'hasAction' => true,
                'hasPlaceholder' => $anyPostHasImage && $post->showImage && !isset($post->thumbnail[0]),
                'image' => $post->showImage ? [
                    'src' => $post->thumbnail[0],
                    'alt' => $post->post_title,
                    'backgroundColor' => 'secondary',
                ] : []
            ])
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