<!-- Social media by curator --> 

@if (!$hideTitle && !empty($post_title))
    @typography([
        'id' => 'mod-posts-' . $ID . '-label',
        'element' => 'h4', 
        'variant' => 'h2', 
        'classList' => ['module-title']
    ])
        {!! apply_filters('the_title', $post_title) !!} 
    @endtypography
@endif

@if($showFeed) 
    <div class="o-grid modularity-socialmedia-container">
        @foreach ($posts as $post)
            <div class="o-grid-12@xs o-grid-6@sm o-grid-4@md o-grid-3@lg">
                @card([ 
                    'link' => $post->url,
                    'image' => [
                        'src' => $post->image,
                        'alt' => $post->text,
                        'backgroundColor' => 'secondary'
                    ],
                    'ratio' => '4:3',
                    'content' => $post->text,
                    'classList' => ['u-height--100']
                ])
                    
                    @avatar(
                        [
                            'name' => $post->user_readable_name,
                            'size' => 'sm',
                            'classList' => ['u-position--absolute', 'u-level-1', 'u-box-shadow--1', 'u-margin--2']
                        ]
                    )
                    @endavatar

                    @image([
                        'src'=> $post->image,
                        'alt' => $post->text,
                        'classList' => ['c-card__image'],
                        'rounded' => false,
                    ])
                    @endimage

                    <div class="c-card__body">
                        @typography(['element' => 'span', 'variant' => 'meta', 'classList' => ['u-display--flex']])
                            @icon(['icon' => 'date_range', 'size' => 'sm', 'classList' => ['u-margin__right--1']])
                            @endicon
                            @date(['action' => 'formatDate', 'timestamp' => $post->source_created_at])
                            @enddate
                        @endtypography   

                        @typography(['element' => 'div'])
                            {{ $post->text }}
                        @endtypography
                    </div>
                @endcard
            </div>
        @endforeach

        <div class="o-grid-12">
            @typography(['element' => 'div', 'variant' => 'meta', 'classList' => ['u-text-align--right']])
                <a href="https://curator.io" target="_blank" rel="nofollow">Powered by Curator.io</a>
            @endtypography
        </div>
    </div>
@else

    @notice([
        'type' => 'info',
        'message' => [
            'text' => $errorMessage,
            'size' => 'sm'
        ],
        'icon' => [
            'name' => 'report',
            'size' => 'md',
            'color' => 'white'
        ]
    ])
    @endnotice

@endif