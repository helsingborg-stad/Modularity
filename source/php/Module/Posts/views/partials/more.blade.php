@if ($posts_data_source !== 'input' && !empty($archive_link_url))
    <div class="t-read-more-section u-display--flex u-align-content--center u-margin__y--4">
        @button([
          'text' => $lang['showMore'],
          'color' => 'secondary',
          'style' => 'filled',
          'href' => $archive_link_url,
          'classList' => ['u-flex-grow--1@xs', 'u-margin__x--auto'],
        ])
    </div>
@endif
