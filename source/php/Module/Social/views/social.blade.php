<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))

        @typography([
            'element'   => "h4",
            'classList' => ['post-title']
        ])
            <i class="fa fa-{{ $feedArgs['network'] }}"></i>  {!!  apply_filters('the_title', $post_title) !!}
        @endtypography

    @endif

    {!! $feed->render() !!}
</div>
