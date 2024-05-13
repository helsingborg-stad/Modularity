 <div class="{{$input['columnSize']}}">
    @card([
        'link'              => $input['link'],
        'heading'           => $input['title'],
        'context'           => $context,
        'content'           => $input['content'],
        'image'             => $input['image'],
        'imageFirst'        => true,
        'containerAware'    => true,
        'classList'         => ['u-height--100']
    ])
    @endcard
</div>