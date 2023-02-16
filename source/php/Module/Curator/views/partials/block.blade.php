<div class="o-grid o-grid--half-gutter">
    @foreach ($posts as $post)
        <div class="{{ $gridClasses }}">
            @block([
                'filled' => true,
                'image' => [
                    'src' => $post->image,
                    'alt' => $post->text,
                    'backgroundColor' => 'secondary'
                ],
                'link' => $post->url,
                'ratio' => $ratio,
                'classList' => ['u-height--100']
            ])
            @endblock
        </div>
    @endforeach
</div>
