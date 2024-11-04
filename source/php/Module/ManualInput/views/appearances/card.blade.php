@element([
    'attributeList' => $input['attributeList'] ?? [],
    'classList'     => array_merge($input['classList'] ?? [], [$input['columnSize']])
])
    @card([
        'link'              => $input['link'],
        'heading'           => $input['title'],
        'context'           => $context,
        'content'           => $input['content'],
        'image'             => $input['image'],
        'containerAware'    => true,
        'classList'         => array_merge($input['classList'] ?? [], ['u-height--100']),
        'hasPlaceholder'    => $anyItemHasImage,
    ])
    @endcard
@endelement