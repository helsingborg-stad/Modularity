@php
    $moduleHeading = (!$hideTitle && !empty($postTitle)) ?
        '<b>'. $postTitle.'</b><br/>' : '';
@endphp

@notice([
    'type' => $notice_type,
    'message' => [
        'text' => $moduleHeading . $notice_text,
        'size' => $notice_size
    ],
    'icon' => $icon,
    'context' => ['notice', 'module.notice']
])
@endnotice