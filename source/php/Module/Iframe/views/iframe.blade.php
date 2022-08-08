@if (!$hideTitle && !empty($postTitle))
    @typography([
        'element' => 'h4',
        'variant' => 'h2',
        'classList' => ['module-title']
    ])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif   

<div iframe-container style="height: {{ $height }}px;">
<div class="u-level-top u-position--absolute u-align--middle u-padding__x--3" iframe-acceptance-wrapper style="top:20%;">
    @typography([
        'variant' => 'h2',
        'element' => 'h4',
    ])
    Informationen i den här rutan hämtas från en extern leverantör
    @endtypography
    @typography([
        'classList' => ['u-padding__bottom--4']
    ])
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eget viverra ex, in facilisis ex. Praesent sit amet massa felis. Interdum et malesuada fames ac ante ipsum primis in faucibus.
    @endtypography
    @button([
        'text' => 'Visa informationen',
        'color' => 'primary',
        'attributeList' => [
            'data-js-toggle-trigger' => 'show-iframe'
        ],
    ])
    @endbutton
</div>
    <iframe src="{{$url}}" title="{!! $description ?? apply_filters('the_title', $post_title) !!}" frameborder="0" style="width: 100%;height:100%; filter:blur(20px);pointer-events:none;"></iframe>
</div> 
