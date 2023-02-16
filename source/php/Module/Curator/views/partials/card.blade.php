<div class="o-grid">
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
                @image([
                    'src' => $post->image,
                    'alt' => $post->text,
                    'classList' => ['c-card__image', 'u-padding--2', 'u-color__bg--complementary-lightest'],
                    'rounded' => false
                ])
                @endimage

                @avatar([
                    'name' => $post->user_readable_name,
                    'size' => 'sm',
                    'classList' => ['u-position--absolute', 'u-level-1', 'u-box-shadow--1', 'u-margin--3']
                ])
                @endavatar


                <div class="c-card__body">
                    @typography(['element' => 'span', 'variant' => 'meta', 'classList' => ['u-display--flex']])
                        @icon(['icon' => 'date_range', 'size' => 'sm', 'classList' => ['u-margin__right--1']])
                        @endicon
                        @date(['action' => 'formatDate', 'timestamp' => $post->source_created_at])
                        @enddate
                    @endtypography

                    @typography(['element' => 'div', 'classList' => ['u-margin__top--1']])
                        {{ $post->text }}
                    @endtypography
                </div>
            @endcard
        </div>
    @endforeach
</div>
