@if (!$hideTitle && !empty($post_title))
    @typography(['element' => 'h2'])
        {!! apply_filters('the_title', $post_title) !!}
    @endtypography
@endif

<div class="o-grid">
    @foreach ($items as $item)
        <div class="{{ apply_filters('Municipio/Controller/Archive/GridColumnClass', $columnClass) }}">
            @card([
                'heading' => $item['title'],
                'content' => $item['lead'],
                'image' => [
                    'src' => $item['thumbnail'][0] ?? null,
                    'alt' => $item['title'],
                    'backgroundColor' => 'secondary',
                    'padded' => false
                ],
                'link' => $item['permalink'],
                'classList' => ['u-height--100', 'u-height-100']
            ])
            @endcard
        </div>
    @endforeach
</div>