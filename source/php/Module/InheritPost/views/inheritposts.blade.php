@if ($inherit->post_status == 'publish') :
<article>
    @if (!$hideTitle && !empty($post_title))
        <h1>{{ $module->post_title }}</h1>
    @endif

    <h2>{!! apply_filters('the_title', $inherit->post_title) !!}</h2>
    {!! apply_filters('the_content', $inherit->post_content) !!}
</article>
@endif
