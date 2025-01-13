@element([
    'attributeList' => [
        'data-js-posts-user' => $currentUser,
        'data-js-posts-id' => $moduleId,
        'data-js-posts-user-ordering' => ''
    ]
])
    @includeFirst([$template, 'list'])
@endelement