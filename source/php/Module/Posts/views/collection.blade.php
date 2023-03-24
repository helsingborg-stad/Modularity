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

<div class="o-grid 
    {{ $stretch ? 'o-grid--stretch' : '' }} 
    {{ $noGutter ? 'o-grid--no-gutter' : '' }}" 
    @if (!$hideTitle && !empty($postTitle))
    aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}"
    @endif
    >
    @collection([
        'classList' => ['c-collection--posts']
    ])
        @foreach ($posts as $post)
            @collection__item([
                'link' => $post->link,
                'classList' => ['c-collection__item--post', 'u-flex-direction--column@xs']
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
                    'classList' => ['u-padding__y--1']
                ])
                @endtags
                @typography([])
                    {!! $post->showExcerpt ? $post->post_content : false !!}
                @endtypography

                @endgroup
            @endcollection__item
        @endforeach
    @endcollection
</div>