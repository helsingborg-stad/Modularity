@if (!$hideTitle && !empty($postTitle))
        @typography([
            'element' => 'h4', 
            'variant' => 'h2', 
            'classList' => ['module-title']
        ])
            {!! $postTitle !!}
        @endtypography
@endif

@if (isset($mod_image_link_url) && strlen($mod_image_link_url) > 0)

        @link([
            'href' => $mod_image_link_url
        ])
            @image([
                'src'=> $img_src,
                'alt' => $mod_image_image['alt'],
                'caption' => (isset($mod_image_caption) && !empty($mod_image_caption)) ?
                    $mod_image_caption : "",
                'classList' => ['block-level', $img_classes]
            ])
            @endimage
        @endlink

@else
        @image([
            'src'=> $img_src,
            'alt' => $mod_image_image['alt'],
            'caption' => (isset($mod_image_caption) && !empty($mod_image_caption)) ?
                $mod_image_caption : "",
            'classList' => ['block-level', $img_classes]
        ])
        @endimage
@endif
