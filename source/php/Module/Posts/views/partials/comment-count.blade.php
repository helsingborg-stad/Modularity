@typography([
    'element' => 'span',
    'classList' => [
        'u-display--flex',
        'u-align-items--center',
    ]
])
    @icon([
        'icon' => 'chat_bubble',
        'attributeList' => [
            'style' => 'margin-right: 4px;',
        ],
    ])
    @endicon
    {!! $post->commentCount !!}
@endtypography