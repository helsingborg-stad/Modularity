@block([
    'heading'   => $input['title'],
    'content'   => $input['content'],
    'ratio'     => $input['isHighlighted'] ? '16:9' : $ratio,
    'image'     => $input['image'],
    'classList' => [$input['columnSize'], 'u-height--100'],
    'context'   => $context,
    'link'      => $input['link'],
])
@endblock