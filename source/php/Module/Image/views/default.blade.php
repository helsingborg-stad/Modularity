@if (!$hideTitle && !empty($postTitle))
        @typography([
            'element' => 'h2', 
            'variant' => 'h2', 
            'classList' => ['module-title']
        ])
            {!! $postTitle !!}
        @endtypography
@endif

@if (isset($mod_image_link_url) && strlen($mod_image_link_url) > 0)

        @link([
            'href' => $mod_image_link_url,
            'classList' => ['u-no-decoration'],
        ])
            @image([
                'src'=> $image['src'],
                'alt' => $image['alt'],
                'caption' => (isset($mod_image_caption) && !empty($mod_image_caption)) ?
                    $mod_image_caption : "",
                'classList' => ['block-level', $img_classes]
            ])
            @endimage
        @endlink

@else
        @image([
            'src'=> $image['src'],
            'alt' => $image['alt'],
            'caption' => (isset($mod_image_caption) && !empty($mod_image_caption)) ?
                $mod_image_caption : "",
            'classList' => ['block-level', $img_classes],
            'context' => ['module.image', $sidebarContext . '.module.image', $sidebarContext . '.animation-item'],
        ])
        
        @endimage
@endif
