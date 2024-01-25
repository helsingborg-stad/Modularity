 <div class="{{$columns}}">
    @card([
        'link'              => $input['link'],
        'heading'           => $input['title'],
        'context'           => $context,
        'content'           => $input['content'],
        'image'             => $input['image'],
        'imageFirst'        => $input['imageBeforeContent'],
        'containerAware'    => true,
        'classList'         => ['u-height--100']
    ])
    @endcard
</div>