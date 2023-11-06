@includeWhen(empty($hideTitle) && !empty($post_title), 'partials.post-title')
@if (!empty($manualInputs))
    <div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }} u-height--100">
        @foreach ($manualInputs as $input)
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
        @endforeach
    </div>
@endif