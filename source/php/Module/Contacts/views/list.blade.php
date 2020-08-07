<div>
    @if (!$hideTitle && !empty($post_title))
        @typography([
            "variant"   => "h6",
            "element"   => "h6"
        ])
            {!! apply_filters('the_title', $post_title) !!}
        @endtypography
    @endif

    @foreach ($contacts as $key => $contact)
        <section itemscope="person" itemtype="http://schema.org/Organization">
            @typography([
                "variant"       => "h2",
                "element"       => "h2",
                'classList'     => [
                    'u-color__text--black'
        ],
                "attributeList" => [
                    'itemprop'      => 'name'
                ]
            ])
                <strong>{{ $contact['full_name'] }}</strong>
            @endtypography

            <div>
                @if ($contact['work_title'] || $contact['administration_unit'])
                    @typography([
                        "variant"   => "h4",
                        "element"   => "h4",
                        'classList' => [
                            'u-margin__bottom--2',
                        ]
                    ])
                        @if ($contact['work_title'])
                            <span itemprop="jobTitle">{{ $contact['work_title'] }}</span>
                        @endif

                        @if ($contact['work_title'] && $contact['administration_unit'])
                        -
                        @endif

                        @if ($contact['administration_unit'])
                            <span itemprop="department">{{ $contact['administration_unit'] }}</span>
                        @endif
                    @endtypography
                @endif

                @if (isset($contact['phone']) && !empty($contact['phone']))
                    @foreach ($contact['phone'] as $phone)
                        @component('components.link', [
                            'href'      => 'tel:' . $phone['number'],
                            'icon'      => 'phone',
                            'itemprop'  => 'telephone',
                        ])
                            {{$phone['number']}}
                        @endcomponent
                    @endforeach
                @endif

                @if (isset($contact['email']) && !empty($contact['email']))
                    @component('components.link', [
                        'href'      => 'mailto:' . $contact['email'],
                        'icon'      => 'email',
                        'itemprop'  => 'email'
                    ])
                        {{$contact['email']}}
                    @endcomponent
                @endif

                @if (!empty($module->post_content))
                    {!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $this->post_content)) !!}
                @endif

                {{-- Opening Hours --}}
                @includeWhen($contact['opening_hours'], 'components.opening_hours')

                {{-- Address --}}
                @includeWhen($contact['address'], 'components.adress')

                {{-- Visiting Address --}}
                @includeWhen($contact['visiting_address'], 'components.visiting')

                @if (isset($contact['other']) && !empty($contact['other']))
                    {!! $contact['other'] !!}
                @endif
            </div>
        </section>
    @endforeach
</div>
