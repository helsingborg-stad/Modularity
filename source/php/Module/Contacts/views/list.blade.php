@card([
    'classList' => [
        'c-card--panel',
    ]
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                "variant"   => "h4",
                "element"   => "h4",
                "classList" => ['u-padding--0', 'u-padding__x--3']
            ])
                {!! apply_filters('the_title', $post_title) !!}
            @endtypography
        </div>
    @endif

    @accordion([
    'classList' => ['u-padding__x--0']
    ])
        @foreach ($contacts as $key => $contact)
            @accordion__item([
                'heading' => $contact['full_name'],
                'attributeList' => [
                    'itemscope' => 'person',
                    'itemtype' => 'http://schema.org/Organization'
                ]
            ])
                @include('partials.list_info')
            @endaccordion__item
        @endforeach
    @endaccordion
@endcard
