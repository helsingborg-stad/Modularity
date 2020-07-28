@notice([
    'type' => $notice_type,
    'message' => [
        'text' => $notice_text,
        'size' => $notice_size
    ],
    'icon' => [
        'name' => isset($notice_icon) && !empty( $notice_icon ) ? $notice_icon : 'info',
        'size' => $notice_size
    ]
])
@endnotice