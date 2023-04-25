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
                'title' => $post->showTitle ? $post->post_title : false,
                'context' => ['module.posts.segment'],
                'meta' => $display_reading_time ? $post->reading_time : false,
                'tags' => $post->tags,
                'image' => $post->thumbnail[0],
                'date' => $post->showDate
                    ? date_i18n(\Modularity\Helper\Date::getDateFormat('date-time'), strtotime($post->post_date))
                    : false,
                'content' => $post->post_content,
                'buttons' => [['text' => $labels['readMore'], 'href' => $post->link, 'color' => 'primary']],
                'containerAware' => true,
                'reverseColumns' => isset($imagePosition) ? $imagePosition : true,
                'classList' => $post->classList
                'icon' => $post->termIcon['icon'] ? $post->termIcon : false,
            ])
                @slot('floating')
                    @includeWhen(!empty($post->floatingIcon), 'partials.icon')
                @endslot
            @endsegment
        </div>
    @endforeach
</div>
