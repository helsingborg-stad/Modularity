@if (!$hideTitle && !empty($post_title))
<h4 class="box-title u-mb-4">{!! apply_filters('the_title', $post_title) !!}</h4>
@endif

<div class="grid grid--columns" {{ $equalContainer }}>
@foreach ($contacts as $contact)
<div class="{{ $columns }}">

    <div class="{{ $classes }}" {{ $equalItem }} itemscope itemtype="http://schema.org/Person">
        @if ($contact['thumbnail'] !== false)
        <img class="box-image" src="{{ $contact['thumbnail'][0] }}" alt="{{ $contact['full_name'] }}">
        @endif

        @if ($contact['thumbnail'] === false && $hasImages)
            <figure class="image-placeholder ratio-1-1"></figure>
        @endif

        <div class="box-content">
            {{-- Name --}}
            <h5 itemprop="name">{{ $contact['full_name'] }}</h5>
            <ul>
                {{-- Work Title & Administration Unit --}}
                @if ((isset($contact['work_title']) && !empty($contact['work_title'])) || (isset($contact['administration_unit']) && !empty($contact['administration_unit'])))
                    <li class="card-title">
                        <span itemprop="jobTitle">{{ (isset($contact['work_title']) && !empty($contact['work_title'])) ? $contact['work_title'] : '' }}</span>
                        <span itemprop="department">{{ (isset($contact['administration_unit']) && !empty($contact['administration_unit'])) ? $contact['administration_unit'] : '' }}</span>
                    </li>
                @endif

                {{-- Compact mode wrapper --}}
                @if (isset($compact_mode) && $compact_mode)
                <li>
                    <ul class="u-flex u-justify-content-center u-align-items-center">
                @endif

                {{-- E-mail --}}
                @if (isset($contact['email']) && !empty($contact['email']))
                    <li>
                        @component('components.link', [
                            'href' => 'mailto:' . $contact['email'],
                            'icon' => 'email',
                            'itemprop' => 'email',
                            'compact' => (isset($compact_mode) ? $compact_mode : false)]
                        )
                            {{$contact['email']}}
                        @endcomponent
                    </li>
                @endif

                {{-- Phone --}}
                @if (isset($contact['phone']) && !empty($contact['phone']))
                    @foreach ($contact['phone'] as $phone)
                        <li>
                            @component('components.link', [
                                'href' => 'tel:' . $phone['number'],
                                'icon' => (isset($phone['type']) ? $phone['type'] : 'phone'),
                                'itemprop' => 'telephone',
                                'compact' => (isset($compact_mode) ? $compact_mode : false)]
                            )
                                {{ $phone['number'] }}
                            @endcomponent
                        </li>
                    @endforeach
                @endif

                {{-- Social Media --}}
                @if (isset($contact['social_media']) && !empty($contact['social_media']))
                    @foreach ($contact['social_media'] as $media)
                        <li>
                            @component('components.link', [
                                'href' => $media['url'],
                                'icon' => $media['media'],
                                'itemprop' => ucfirst($media['media']),
                                'compact' => (isset($compact_mode) ? $compact_mode : false)]
                            )
                                {{ ucfirst($media['media']) }}
                            @endcomponent
                        </li>
                    @endforeach
                @endif

                {{-- Compact mode wrapper --}}
                @if (isset($compact_mode) && $compact_mode)
                    </ul>
                </li>
                @endif

                {{-- Description --}}
                @if (!empty($module->post_content))
                <li class="small description">{!! apply_filters('the_content', $this->post_content) !!}</li>
                @endif

                {{-- Address --}}
                @if (isset($contact['address']) && !empty($contact['address']))
                <li class="gutter gutter-top small">
                        <strong><?php _e('Postal address', 'modularity'); ?></strong>
                    {!! $contact['address'] !!}
                </li>
                @endif

                {{-- Visiting Address --}}
                @if (isset($contact['visiting_address']) && !empty($contact['visiting_address']))
                <li class="gutter gutter-top small">
                    <strong><?php _e('Visiting address', 'modularity'); ?></strong>
                    {!! $contact['visiting_address'] !!}
                </li>
                @endif

                {{-- Other --}}
                @if (isset($contact['other']) && !empty($contact['other']))
                <li class="gutter gutter-top small">
                    {!! $contact['other'] !!}
                </li>
                @endif

            </ul>
        </div>
    </div>
</div>
@endforeach
</div>
