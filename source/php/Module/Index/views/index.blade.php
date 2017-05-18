<div class="grid" data-equal-container>
    @if (!$hideTitle && !empty($post_title))
    <div class="grid-xs-12">
        <h2>{!! apply_filters('the_title', $post_title) !!}</h2>
    </div>
    @endif

    @foreach ($items as $item)
    <div class="{{ $columnClass }}">
        <a href="{{ $item['permalink'] }}" class="{{ $classes }}" data-equal-item>
            @if ($item['thumbnail'])
                <img class="box-image" src="{{ $item['thumbnail'][0] }}" alt="{{ $item['title'] }}">
            @endif

            <div class="box-content">
                <h5 class="box-index-title link-item">{{ $item['title'] }}</h5>
                {!! $item['lead'] !!}
            </div>
        </a>
    </div>
    @endforeach
</div>
