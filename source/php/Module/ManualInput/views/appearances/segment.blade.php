@segment([
    'layout' => 'card',
    'title' => $input['title'],
    'context' => $context,
    'image' => $input['image']['src'],
    'content' => $input['content'],
    'buttons' => [['text' => $input['linkText'], 'href' => $input['link'], 'color' => 'primary']],
    'containerAware' => true,
    'reverseColumns' => $input['imageBeforeContent'],
    'classList' => [$input['columnSize']]
])
@endsegment