
@if (!$hideTitle && !empty($post_title))
    @typography(['element' => 'h2'])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif

<div class="grid">
        @foreach ($items as $item)
            <div class="{{ $columnClass }}">
                @card([
                    'heading' => $item['title'],
                    'content' => $item['lead'],
                    'image' => [
                        'src' => $item['thumbnail'][0],
                        'alt' => $item['title'],
                        'backgroundColor' => 'secondary',
                        'padded' => false
                    ],
                    'link' => $item['permalink']
                ])
                @endcard
            </div>
        @endforeach
    </div>
</div>