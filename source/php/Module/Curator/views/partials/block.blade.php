<h1>CURATOR block</h1>
@foreach ($posts as $post)
    <div class="o-grid-12@xs o-grid-6@sm o-grid-4@md o-grid-3@lg u-padding--1">
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
