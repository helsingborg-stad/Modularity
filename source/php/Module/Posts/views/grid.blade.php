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
            @block([
                'heading' => ($post->showTitle ? $post->post_title : false),
                'content' => ($post->showExcerpt ? $post->post_content : false),
                'ratio' => $ratio,
                'meta' => $post->tags,
                'date' => ($post->showDate ? $post->post_date : false),
                'filled' => true,
                'image' => ($post->showImage ? [
                    'src' => $post->thumbnail[0],
                    'alt' => $contact['full_name'],
                    'backgroundColor' => 'secondary',
                ] : false),
                'classList' => ['t-posts-block'],
                'context' => 'module.posts.block',
                'link' => $post->link,
            ])
            @endblock
        </div>
    @endforeach
</div>

<!-- //$post->showDate     = (bool) in_array('date', $this->data['posts_fields']); -->
  

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
