@include('partials.post-filters')

@if (!$hideTitle && !empty($postTitle))
    @typography([
        'id' => 'mod-posts-' . $ID . '-label',
        'element' => 'h2',
        'variant' => 'h2',
        'classList' => ['module-title']
    ])
        {!! $postTitle !!}
    @endtypography
@endif

@if ($preamble)
    @typography([
        'classList' => ['module-preamble', 'u-margin__bottom--3']
    ])
        {!! $preamble !!}
    @endtypography
@endif

<div class="o-grid {{ $stretch ? 'o-grid--stretch' : '' }} {{ $noGutter ? 'o-grid--no-gutter' : '' }}"
    aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}">
    @foreach ($posts as $post)
        <div class="{{ $posts_columns }}">
            @box([
                'heading' => $post->showTitle ? $post->post_title : false,
                'content' => $post->showExcerpt ? $post->post_content : false,
                'link' => $post->link,
                'meta' => $post->tags,
                'secondaryMeta' => $display_reading_time ? $post->reading_time : false,
                'date' => $post->showDate
                    ? date_i18n(\Modularity\Helper\Date::getDateFormat('date-time'), strtotime($post->post_date))
                    : false,
                'ratio' => $ratio,
                'image' => $post->showImage
                    ? [
                        'src' => $post->thumbnail[0] ?? false,
                        'alt' => $post->post_title
                    ]
                    : [],
                'icon' => [
                    'name' => $post->item_icon ?? false
                ]
            ])
            @endbox
        </div>
    @endforeach
</div>
