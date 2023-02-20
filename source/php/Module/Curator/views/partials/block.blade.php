@foreach ($posts as $post)
    <div class="modularity-socialmedia__item {{ $columnClasses }}">
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
        @include('partials.modal', ['post' => $post])
    </div>
@endforeach
