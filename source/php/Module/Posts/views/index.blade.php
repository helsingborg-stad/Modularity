@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}{{ !empty($noGutter) ? ' o-grid--no-gutter' : '' }}{{ (!empty($preamble)||(!$hideTitle && !empty($postTitle))) ? ' u-margin__top--4' : '' }}"
@if (!$hideTitle && !empty($postTitle)) aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}" @endif>
    @if($posts)    
        @foreach ($posts as $post)
            <div class="{{ $loop->first && $highlight_first_column ? $highlight_first_column : $posts_columns }}">
                @if ($loop->first && $highlight_first_column && $highlight_first_column_as === 'block')
                    @include('partials.post.block', ['ratio' => '16:9'])
                @else
                    @include('partials.post.card')
                @endif
            </div>
        @endforeach
    @endif
</div>

@include('partials.more')

@button([
    'text' => __('More'),
    'classList' => ['c-button--block', 'c-button--primary'],
    'context' => ['module.posts.index'],
    'iconPosition' => 'right',
    'attributeList' => ['data-js-load-more' => $ID],
])@endbutton