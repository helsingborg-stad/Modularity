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
                "attributeList" => [
                    'itemprop'      => 'name'
                ]
            ])
                {{ $contact['full_name'] }}
            @endtypography

            <div>
                @if ($contact['work_title'] || $contact['administration_unit'])
                    @typography([
                        "variant"   => "h2",
                        "element"   => "h4",
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

                {{-- Address --}}
                @if ($contact['address'])
                    @include('components.adress')
                @endif

                {{-- Visiting Address --}}
                @if ($contact['visiting_address'])
                    @include('components.visiting')
                @endif

                @if (isset($contact['other']) && !empty($contact['other']))
                        {!! $contact['other'] !!}
                @endif
            </div>
        </section>
    @endforeach
</div>
