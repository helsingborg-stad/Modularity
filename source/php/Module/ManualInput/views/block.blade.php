@includeWhen(empty($hideTitle) && !empty($post_title), 'partials.post-title')
<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}">
    @foreach ($manualInputs as $input)
        @block([
            'heading'   => $input['title'],
            'content'   => $input['content'],
            'ratio'     => $ratio,
            'filled'    => true,
            'image'     => $input['image'],
            'classList' => [$columns, 'u-height--100'],
            'context'   => $context,
            'link'      => $input['link'],
        ])
        @endblock
    @endforeach
</div>