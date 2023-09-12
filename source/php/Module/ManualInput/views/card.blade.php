@includeWhen(empty($hideTitle) && !empty($post_title), 'partials.post-title')
@if (!empty($manualInputs))
    <div class="o-grid">
        @foreach ($manualInputs as $input)
            @card([
                'link' => $input['link'],
                'heading' => $input['title'],
                'context' => $context,
                'content' => $input['content'],
                'image' => $input['image'],
                'classList' => [$columns, 'u-height--100'],
                'imageFirst' => $input['imageBeforeContent'],
                'containerAware' => true,
            ])
            @endcard
        @endforeach
    </div>
@endif