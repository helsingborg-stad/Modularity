<div class="box box-iframe no-padding">
    @if (!$hideTitle && !empty($post_title))

        @typography([
            'element' => "h4",
            'classList' => ['box-title']
        ])
            {!! apply_filters('the_title', $post_title) !!}
        @endtypography

    @endif

    <iframe src="{{ $url }}" frameborder="0" style="width: 100%; height: {{ $height }}px;"></iframe>
</div>
