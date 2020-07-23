<div class="grid" data-equal-container>
    @if (!$hideTitle && !empty($post_title))
        <div class="grid-xs-12">
            @typography([
                'element' => 'h2'
            ])
                {!! apply_filters('the_title', $post_title) !!}
            @endtypography
        </div>
    @endif

    @foreach ($items as $item)
        <div class="{{ $columnClass }}">
            @link([
                'href' => $item['permalink'],
                'classList' => [$classes],
                'attributeList' => ['data-equal-item' => '']
            ])
                @if ($item['thumbnail'])
                    @image([
                        'src'=> $item['thumbnail'][0],
                        'alt' => $item['title'],
                        'classList' => ['box-image']
                    ])
                    @endimage
                @endif

                <div class="box-content">
                    @typography([
                        'element' => 'h5',
                        'variant' => 'h5',
                        'classList' => ['box-index-title link-item']
                    ])
                        {{ $item['title'] }}
                    @endtypography

                    {!! $item['lead'] !!}
                </div>
            @endlink
        </div>
    @endforeach
</div>
