@includeWhen(empty($hideTitle) && !empty($post_title), 'partials.post-title')
@if (!empty($manualInputs))
    <div class="o-grid">
        @foreach ($manualInputs as $input)
        <div class="{{$columns}}">
            @card([
                'link' => $input['link'],
                'heading' => $input['title'],
                'context' => $input['context'],
                'content' => $input['content'],
                'image' => $input['image'],
                'classList' => ['u-height--100'],
                'imageFirst' => true,
                'containerAware' => true,
                'hasAction' => true,
            ])
            @endcard
        </div>
        @endforeach
    </div>
@endif