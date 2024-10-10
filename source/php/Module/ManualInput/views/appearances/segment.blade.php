@segment([
    'layout' => 'card',
    'title' => $input['title'],
    'context' => $context,
    'image' => $input['image'],
    'content' => $input['content'],
    'buttons' => [['text' => $input['linkText'], 'href' => $input['link'], 'color' => 'primary']],
    'containerAware' => true,
    'reverseColumns' => $imagePosition,
    'classList' => [$input['columnSize']],
    'hasPlaceholder' => $anyItemHasImage,
])
@endsegment