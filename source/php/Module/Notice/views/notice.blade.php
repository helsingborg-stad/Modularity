<div class="notice {{ $notice_type }} {{ $notice_size }}">
    <div class="grid grid-table-md grid-va-middle no-margin no-padding">

        @if (isset($notice_icon) && !empty( $notice_icon ))
            <div class="grid-fit-content"><i class="fa {{ $notice_icon }}"></i></div>
        @endif

        <div class="grid-auto">
            @if (!$hideTitle && !empty($post_title))
                <h4>{!! apply_filters('the_title', $post_title) !!}</h4>
            @endif

            {!! $notice_text !!}

        </div>
    </div>
</div>
