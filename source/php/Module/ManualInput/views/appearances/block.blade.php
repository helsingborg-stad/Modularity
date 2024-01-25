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