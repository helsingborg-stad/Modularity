@foreach ($posts as $post)
    <div class="open-modal modularity-socialmedia__item {{ $columnClasses }}">
        @block([
            'filled' => true,
            'image' => [
                'src' => $post->image,
                'alt' => $post->text,
                'backgroundColor' => 'secondary'
            ],
            'attributeList' => ['data-open' => 'modal-' . $post->id],
            'ratio' => $ratio,
            'classList' => ['u-height--100', 'c-block--curator']
        ])
        @endblock
    </div>
    @include('partials.modal', ['post' => $post])
@endforeach
