@element([
    'attributeList' => $input['attributeList'] ?? [],
    'classList'     => array_merge($input['classList'] ?? [], [$input['columnSize']])
])
    @card([
        'link'              => $input['link'],
        'linkText'          => isset($input['linkText']) ? $input['linkText'] : '',
        'heading'           => $input['title'],
        'context'           => $context,
        'content'           => $input['content'],
        'image'             => $input['image'],
        'containerAware'    => !$disableLayoutShift,
        'classList'         => array_merge($input['classList'] ?? [], ['u-height--100']),
        'hasPlaceholder'    => $anyItemHasImage,
        'headingAboveImage' => isset($titleAboveImage) ? $titleAboveImage : false,
        'icon'              => $input['icon'] ? [
            'icon' => $input['icon'],
            'size' => 'md',
            'color' => 'black'
        ] : null
    ])
    @endcard
@endelement