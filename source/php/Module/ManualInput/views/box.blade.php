@includeWhen(empty($hideTitle) && !empty($postTitle), 'partials.post-title')
<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}">
    @foreach ($manualInputs as $input)
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
    @endforeach
</div>