<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}">
    @foreach ($manualInputs as $input)
    <div class="{{$columns}}">
            @box([
                'heading'   => $input['title'],
                'content'   => $input['content'],
                'link'      => $input['link'],
                'ratio'     => $blockBoxRatio,
                'image'     => $input['image'],
                'context'   => $context
            ])
            @endbox
    </div>
    @endforeach
</div>