<div class="box no-padding">
    @if (!$hideTitle && !empty($post_title))
    <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <iframe src="{{ $url }}" frameborder="0" style="width: 100%; height: {{ $height }}px;"></iframe>
</div>
