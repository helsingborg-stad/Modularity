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
            'classList' => ['u-height--100']
        ])
        @endblock
    </div>
@endforeach
