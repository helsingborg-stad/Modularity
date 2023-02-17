@foreach ($posts as $post)
    <div class="{{ $gridClasses }}">
        @block([
            'filled' => true,
            'image' => [
                'src' => $post->image,
                'alt' => $post->text,
                'backgroundColor' => 'secondary'
            ],
            'attributeList' => ['data-open' => 'modal-' . $post->id],
            'ratio' => $ratio,
            'classList' => ['u-height--100']
        ])
        @endblock
    </div>
    @include('partials.modal', ['post' => $post])
@endforeach
