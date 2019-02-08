<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <ul class="nav">
        @foreach ($items as $item)
            @if ($item['type'] == 'external')
            <li>
                <a href="{{ $item['link_external'] }}">
                    <span class="link-item link-item-outbound title">{{ $item['title'] }}</span>
                </a>
            </li>
            @elseif ($item['type'] == 'internal')
            <li>
                <a href="{{ get_permalink($item['link_internal']->ID) }}">
                    <span class="link-item title">{{ (!empty($item['title'])) ? $item['title'] : $item['link_internal']->post_title }}</span>

                    @if ($item['date'] === true)
                    <time class="date text-sm text-dark-gray">{{ date('Y-m-d', strtotime($item['link_internal']->post_date)) }}</time>
                    @endif
                </a>
            </li>
            @endif
        @endforeach
    </ul>
</div>
