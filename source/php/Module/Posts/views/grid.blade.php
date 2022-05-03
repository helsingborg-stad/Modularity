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

<div class="o-grid {{ $stretch ? 'o-grid--stretch o-grid--no-gutter' : '' }}"" aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}">
    @foreach ($posts as $post)
        <div class="{{ $posts_columns }}">
            @block([
                'heading' => $post->post_title,
                'content' => $post->post_content,
                'ratio' => $ratio,
                'meta' => $post->tags,
                'filled' => true,
                'image' => [
                    'src' => $post->thumbnail[0],
                    'alt' => $contact['full_name'],
                    'backgroundColor' => 'secondary',
                ],
                'classList' => ['t-posts-block'],
                'context' => 'module.posts.block',
                'link' => $post->link,
            ])
            @endblock
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
