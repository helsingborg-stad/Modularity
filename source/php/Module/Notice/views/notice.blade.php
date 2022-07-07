@notice([
    'stretch' => $stretch,
    'type' => $notice_type,
    'message' => [
        'title' => !$hideTitle && !empty($postTitle) ? $postTitle : null,
        'text' => $moduleHeading . $notice_text,
    ],
    'icon' => $icon,
    'context' => ['notice', 'module.notice']
])
@endnotice