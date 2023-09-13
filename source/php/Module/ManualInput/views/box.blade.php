<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}">
    @foreach ($manualInputs as $input)
            @box([
                'heading'   => $input['title'],
                'content'   => $input['content'],
                'link'      => $input['link'],
                'ratio'     => $blockBoxRatio,
                'image'     => $input['image'],
                'classList' => [$columns],
                'context'   => $context
            ])
            @endbox
    @endforeach
</div>