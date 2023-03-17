@php    
$icon['attributeList']['data-post-type'] = $post->post_type;
$icon['attributeList']['data-post-id'] = $post->ID; 
@endphp

@icon([
    'icon' => $icon['icon'],
    'size' => $icon['size'] ?? 'md',
    'filled' => $icon['filled'] ?? false,
    'attributeList' => $icon['attributeList'],
    'classList' => $icon['classList'],
])
@endicon