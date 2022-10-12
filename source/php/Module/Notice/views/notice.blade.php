@notice([
    'stretch' => (!is_admin() && isset($blockData) ? ((bool) $blockData['align'] == 'full') : $stretch ?? false),
    'type' => $notice_type,
    'message' => [
        'title' => !$hideTitle && !empty($postTitle) ? $postTitle : null,
        'text' => $notice_text,
    ],
    'icon' => $icon,
    'context' => ['notice', 'module.notice']
])
@endnotice