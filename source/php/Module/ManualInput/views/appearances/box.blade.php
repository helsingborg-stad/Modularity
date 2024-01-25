<div class="{{$columns}}">
    @box([
        'heading'   => $input['title'],
        'content'   => $input['content'],
        'link'      => $input['link'],
        'ratio'     => $ratio,
        'image'     => $input['image'],
        'icon'      => $input['boxIcon'],
        'context'   => $context
    ])
    @endbox
</div>