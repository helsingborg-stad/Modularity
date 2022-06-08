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
        <div class="{{ $loop->first && $highlight_first_column ? $highlight_first_column : $posts_columns }}">
            @if($loop->first && $highlight_first_column && $highlight_first_column_as === 'block')
                @block([
                    'heading' => ($post->showTitle ? $post->post_title : false),
                    'content' => ($post->showExcerpt ? $post->post_content : false),
                    'ratio' => '16:9',
                    'meta' => $post->tags,
                    'date' => ($post->showDate ? $post->post_date : false),
                    'filled' => true,
                    'image' => ($post->showImage ? [
                        'src' => $post->thumbnail[0],
                        'alt' => $contact['full_name'],
                        'backgroundColor' => 'secondary',
                    ] : false),
                    'hasPlaceholder' => $anyPostHasImage && !isset($post->thumbnail[0]),
                    'classList' => ['t-posts-block', ' u-height--100'],
                    'context' => 'module.posts.block',
                    'link' => $post->link,
                ])
                @endblock
            @else
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
            @endif
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