@card([
    'classList' => [
        'c-card--panel',
    ],
    'attributeList' => [
        'aria-labelledby' => 'mod-text-' . $ID .'-label'
    ]
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                'id'        => 'mod-text-' . $ID .'-label',
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

        @php
            // Title partials
            $titlePropeties = ['full_name', 'administration_unit', 'work_title'];
            
            // Build array
            $title = array_filter(array_map(function($key) use ($contact) {
                return $contact[$key] ?: false;
            }, $titlePropeties), function($item) {return $item;});
            
            // Array 2 String
            $title = !empty($title) ? implode(' - ', $title) : false;
        @endphp

            @accordion__item([
                'heading' => $title,
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
