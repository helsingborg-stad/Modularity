<div class="box box-plain wp-caption">
    @if (!$hideTitle && !empty($post_title))
            @typography([
                "variant" => "h2"
            ])
                {!! apply_filters('the_title', $post_title) !!}
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
            @endbutton
        <a href="{{ $mod_image_link_url }}"><img src="{{ $img_src }}" alt="{{ $mod_image_image['alt'] }}" class="block-level {!! $img_classes !!}"></a>
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

</div>
