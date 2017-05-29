@if (!$hideTitle && !empty($post_title))
<h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
@endif

<div class="grid">
@foreach ($contacts as $contact)
<div class="{{ $columns }}">
    <div class="{{ $classes }}" itemscope itemtype="http://schema.org/Person">
        @if ($contact['thumbnail'] !== false)
        <img class="box-image" src="{{ $contact['thumbnail'][0] }}" alt="{{ $contact['full_name'] }}">
        @endif

        <div class="box-content">
            <h5 itemprop="name">{{ $contact['full_name'] }}</h5>

            <ul>
            @if ((isset($contact['work_title']) && !empty($contact['work_title'])) || (isset($contact['administration_unit']) && !empty($contact['administration_unit'])))
            <li class="card-title">
                <span itemprop="jobTitle">{{ (isset($contact['work_title']) && !empty($contact['work_title'])) ? $contact['work_title'] : '' }}</span>
                <span itemprop="department">{{ (isset($contact['administration_unit']) && !empty($contact['administration_unit'])) ? $contact['administration_unit'] : '' }}</span>
            </li>
            @endif

            @if (isset($contact['phone']) && !empty($contact['phone']))
            @foreach ($contact['phone'] as $phone)
                <li><a itemprop="telephone" class="link-item" href="tel:{{ $phone['number'] }}">{{ $phone['number'] }}</a></li>
            @endforeach
            @endif

            @if (isset($contact['email']) && !empty($contact['email']))
            <li><a itemprop="email" class="link-item truncate" href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></li>
            @endif

            @if (!empty($module->post_content))
            <li class="small description">{!! apply_filters('the_content', $this->post_content) !!}</li>
            @endif
            </ul>

            @if (isset($contact['address']) && !empty($contact['address']))
            <div class="gutter gutter-top small">
                @if (isset($contact['visiting_address']) && !empty($contact['visiting_address']))
                    <strong><?php _e('Postal address', 'modularity'); ?></strong>
                @endif
                {!! $contact['address'] !!}
            </div>
            @endif

            @if (isset($contact['visiting_address']) && !empty($contact['visiting_address']))
            <div class="gutter gutter-top small">
                @if (isset($contact['address']) && !empty($contact['address']))
                    <strong><?php _e('Visiting address', 'modularity'); ?></strong>
                @endif
                {!! $contact['visiting_address'] !!}
            </div>
            <?php endif; ?>

            @if (isset($contact['other']) && !empty($contact['other']))
            <div class="gutter gutter-top small">
                {!! $contact['other'] !!}
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach
</div>
