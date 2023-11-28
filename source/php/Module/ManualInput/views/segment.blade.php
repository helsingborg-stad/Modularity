@includeWhen(empty($hideTitle) && !empty($custom_block_title), 'partials.post-title')

@if (!empty($manualInputs))
<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}">
    @foreach ($manualInputs as $input)
        @segment([
            'layout' => 'card',
            'title' => $input['title'],
            'context' => $context,
            'image' => $input['image']['src'],
            'content' => $input['content'],
            'buttons' => [['text' => $input['linkText'], 'href' => $input['link'], 'color' => 'primary']],
            'containerAware' => true,
            'reverseColumns' => $input['imageBeforeContent'],
            'classList' => [$columns]
        ])
        @endsegment
    @endforeach
</div>
@endif
