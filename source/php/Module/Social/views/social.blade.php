<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
        <h4 class="module-title"><i class="fa fa-{{ $feedArgs['network'] }}"></i> {!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    {!! $feed->render() !!}
</div>
