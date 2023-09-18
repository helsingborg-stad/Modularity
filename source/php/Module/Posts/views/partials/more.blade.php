@if ($posts_data_source !== 'input' && !empty($archive_link_url))
    <div class="t-read-more-section u-display--flex u-align-content--center u-margin__y--4">
        @if(!empty($filters) && is_array($filters))
          @button([
            'text' => __('Show more', 'modularity'),
            'color' => 'secondary',
            'style' => 'filled',
            'href' => $archive_link_url . '?' . http_build_query($filters),
            'classList' => ['u-flex-grow--1@xs', 'u-margin__x--auto'],
          ])
          @endbutton
        @else 
          @button([
            'text' => __('Show more', 'modularity'),
            'color' => 'secondary',
            'style' => 'filled',
            'href' => $archive_link_url,
            'classList' => ['u-flex-grow--1@xs', 'u-margin__x--auto'],
          ])
          @endbutton
        @endif
    </div>
@endif
