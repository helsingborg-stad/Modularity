@include('partials.post-filters')

@if (!$hideTitle && !empty($postTitle))
    @typography([
        'id' => 'mod-posts-' . $ID . '-label',
        'element' => 'h2', 
        'variant' => 'h2', 
        'classList' => ['module-title']
    ])
        {!! $postTitle !!}
    @endtypography
@endif

@if ($preamble)
    @typography([
        'classList' => ['module-preamble', 'u-margin__bottom--3'] 
    ])
        {!! $preamble !!}
    @endtypography
@endif

    @collection([
        'classList' => ['c-collection--posts', 'o-grid']
    ])
        @foreach ($posts as $post)
        <div class="{{$posts_columns}}">
            @collection__item([
                'link' => $post->link,
                'classList' => ['c-collection__item--post'],
                'containerAware' => true,
            ])
            @slot('before')
            @if($post->showImage && isset($post->thumbnail[0]))
                @image([
                    'src' => $post->thumbnail[0] ,
                    'alt' => $post->post_title,
                ])
                @endimage
            @endif
            @endslot
                @group([
                    'direction' => 'vertical'
                ])
                @group([
                    'justifyContent' => 'space-between'
                ])
                    @typography([
                        'element' => 'h2',
                        'variant' => 'h3',
                    ])
                        {{$post->showTitle ? $post->post_title : false}}
                    @endtypography
                    {{-- TODO: Add icon --}}
                @endgroup
                @tags([
                    'tags' => $post->tags,
                    'classList' => ['u-padding__y--2']
                ])
                @endtags
                @typography([])
                    {!! $post->showExcerpt ? $post->post_content : false !!}
                @endtypography

                @endgroup
            @endcollection__item
        </div>
        @endforeach
    @endcollection