@grid([
    "container" => true,
    "columns" => "auto-fit",
    "min_width" => "25%",
])
    @foreach($sites as $site)
        @grid([])
            @link([
                'href' => $site->home
            ])

            @if ($site->image_rendered)
                {!! $site->image_rendered !!}
            @elseif ($site->image)

                <div style="background-image:url({{ $site->image }});" class="box-image">
                    @image([
                        'src'=> $site->image,
                        'alt' => $site->blogname
                    ])
                    @endimage
                </div>
            @endif

            <div class="box-content">
                @typography([
                    'element'   => "h3",
                    'classList' => ['post-title']
                ])
                    {!!  $site->blogname !!}
                @endtypography

                @typography([
                    'element'   => "small",
                    'classList' => ['post-title']
                ])
                    {!! explode('://', $site->home)[1] !!}
                @endtypography

            </div>

            <div class="box-post-brick-lead">
                @typography([
                    'element'   => "p"
                ])
                    {!! $site->description !!}
                @endtypography
            </div>
       @link
    @endgrid
    @endforeach
@endgrid
