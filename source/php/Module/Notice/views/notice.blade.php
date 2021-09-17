@php
    $moduleHeading = (!$hideTitle && !empty($post_title)) ?
        '<b>'.apply_filters('the_title', $post_title).'</b><br/>' : '';
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