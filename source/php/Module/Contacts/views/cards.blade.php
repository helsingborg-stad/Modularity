@if (!$hideTitle && !empty($post_title))
    @typography([
        "variant" => "h4"
    ])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif

@grid([
    "container" => true,
    "columns"   => "auto-fit",
    "min_width" => "300px",
    "max_width" => "400px",
    "col_gap"   => 5,
    "row_gap"   => 5
])
    @foreach ($contacts as $contact)
        @grid([])
            @card([
                'collapsible'   => true,
                'imageFirst'    => true,
                'heading'       => $contact['full_name'],
                'subHeading'    => $contact['administration_unit'] ? "{$contact['work_title']} - {$contact['administration_unit']}" : $contact['work_title'],
                'image'         => [
                    'src'               => $contact['thumbnail'][0],
                    'alt'               => $contact['full_name'],
                    'backgroundColor'   => 'secondary',
                ],
                'attributeList' => [
                    'itemscope'     => '',
                    'itemtype'      => 'http://schema.org/Person'
                ]
            ])

                <div class="u-margin__bottom--4 u-margin__top--1">
                    {{-- E-mail --}}
                    @if ($contact['email'])
                        @component('components.link', [
                            'href'      => 'mailto:' . $contact['email'],
                            'icon'      => 'email',
                            'itemprop'  => 'email',
                            'compact'   => (isset($compact_mode) ? $compact_mode : false)]
                        )
                            {{$contact['email']}}
                        @endcomponent
                    @endif

                    {{-- Phone --}}
                    @if ($contact['phone'])
                        @foreach ($contact['phone'] as $phone)
                            @component('components.link', [
                                'href'      => 'tel:' . $phone['number'],
                                'icon'      => (isset($phone['type']) ? $phone['type'] : 'phone'),
                                'itemprop'  => 'telephone',
                                'compact'   => (isset($compact_mode) ? $compact_mode : false)]
                            )
                                {{ $phone['number'] }}
                            @endcomponent
                        @endforeach
                    @endif

                    {{-- Social Media --}}
                    @if ($contact['social_media'])
                        @foreach ($contact['social_media'] as $media)
                            @component('components.link', [
                                'href'      => $media['url'],
                                'icon'      => $media['media'],
                                'itemprop'  => ucfirst($media['media']),
                                'compact'   => (isset($compact_mode) ? $compact_mode : false)]
                            )
                                {{ ucfirst($media['media']) }}
                            @endcomponent
                        @endforeach
                    @endif
                </div>

                {{-- Description --}}
                @if (!empty($module->post_content))
                    <div class="small description">{!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $this->post_content)) !!}</div>
                @endif

                {{-- Opening Hours --}}
                @includeWhen($contact['opening_hours'], 'components.opening_hours')

                {{-- Address --}}
                @includeWhen($contact['address'], 'components.adress')

                {{-- Visiting Address --}}
                @includeWhen($contact['visiting_address'], 'components.visiting')

                {{-- Other --}}
                @if ($contact['other'])
                    {!! $contact['other'] !!}
                @endif
            @endcard
        @endgrid
    @endforeach
@endgrid