@include('partials.post-filters')

<div class="o-grid {{ $stretch ? 'o-grid--stretch' : '' }} {{ $noGutter ? 'o-grid--no-gutter' : '' }}" aria-labelledby="{{ 'mod-posts-' . $ID . '-label' }}">
    @foreach ($posts as $post)
        <div class="{{$posts_columns}}">
            @box([
                'heading' => ($post->showTitle ? $post->post_title : false),
                'content' => ($post->showExcerpt ? $post->post_content : false),
                'link' => $post->link,
                'meta' => $post->tags,
                'date' => $post->showDate ? date("Y-m-d H:i", strtotime($post->post_date)) : false,
                'ratio' => $ratio,
                'image' => $post->showImage ? [
                    'src' => $post->thumbnail[0] ?? false,
                    'alt' => $post->post_title
                ] : [],
                'icon' => [
                    'name' => $post->item_icon ?? false,
                ]
            ])
            @endbox
        </div>
    @endforeach
</div>
