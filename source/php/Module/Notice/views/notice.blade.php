@notice([
    'type' => $notice_type,
    'message' => [
        'title' => !$hideTitle && !empty($postTitle) ? $postTitle : null,
        'text' => $moduleHeading . $notice_text,
        'size' => $notice_size
    ],
    'icon' => $icon,
    'context' => ['notice', 'module.notice']
])
@endnotice