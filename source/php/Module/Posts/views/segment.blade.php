@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid 
    {{ $stretch ? 'o-grid--stretch' : '' }} 
    {{ $noGutter ? 'o-grid--no-gutter' : '' }}"
    @if (!$hideTitle && !empty($postTitle)) aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}" @endif>
    @foreach ($posts as $post)
        <div class="{{ $loop->first && $highlight_first_column ? $highlight_first_column : $posts_columns }}">
            @segment([
                'layout' => 'card',
                'title' => !empty($post->showTitle) ? $post->postTitle : false,
                'context' => ['module.posts.segment'],
                'meta' => !empty($display_reading_time) ? $post->readingTime : false,
                'tags' => !empty($post->termsUnlinked) ? $post->termsUnlinked : false,
                'image' => !empty($post->thumbnail['src']) ? $post->thumbnail['src'] : false,
                'date' => $post->postDate,
                'dateBadge' => !empty($post->dateBadge) ? $post->dateBadge : false,
                'content' => !empty($post->excerptShort) ? $post->excerptShort : false,
                'buttons' => [['text' => $labels['readMore'], 'href' => $post->permalink, 'color' => 'primary']],
                'containerAware' => true,
                'reverseColumns' => isset($imagePosition) ? $imagePosition : true,
                'classList' => !empty($post->classList) ? $post->classList : [],
                'icon' => !empty($post->termIcon['icon']) ? $post->termIcon : false,
                'attributeList' => array_merge($post->attributeList, []),
            ])
                @slot('floating')
                    @if (!empty($post->floating['floating']))
                        @icon($post->floating['floating'])
                        @endicon
                    @endif
                @endslot
            @endsegment
        </div>
    @endforeach
</div>
