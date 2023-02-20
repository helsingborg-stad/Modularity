@modal([
    'id' => 'modal-' . $post->id,
    'size' => 'md',
    'heading' => false,
    'padding' => 1
])
    <div class="o-grid">
        <div class="o-grid-7">
            @if ($post->oembed)
                {!! $post->oembed !!}
            @else
                @image([
                    'src' => $post->image
                ])
                @endimage
            @endif
        </div>
        <div class="o-grid-5">
            <header class="o-container o-container--fullwidth u-margin__left u-margin__top u-margin__bottom--1">
                @avatar([
                    'image' => $post->user_image,
                    'name' => $post->user_screen_name,
                    'size' => 'sm',
                    'classList' => ['u-float--left', 'u-margin__right--1']
                ])
                @endavatar

                @typography([
                    'variant' => 'h6',
                    'classList' => ['u-margin__top--0', 'u-padding--1', 'u-padding__top--0']
                ])
                    {{ $post->user_screen_name }}
                @endtypography
            </header>
            @typography([
                'element' => 'small',
                'classList' => ['u-color__text--light', 'u-margin__bottom--0', 'u-margin__top--1']
            ])
                {{ $post->formatted_date }}
            @endtypography
            @typography([
                'element' => 'h2',
                'variant' => 'h4',
                'classList' => ['u-margin--0']
            ])
                {{ $post->title }}
            @endtypography
            @typography([
                'variant' => 'p',
                'classList' => ['u-margin__top--1', 'u-margin__bottom--2']
            ])
                {{ $post->full_text }}
            @endtypography
            @button([
                // TODO: How to position the button centered at the bottom of the post?
                'text' => $i18n['goToOriginalPost'],
                'color' => 'default',
                'size' => 'sm',
                'style' => 'filled',
                'href' => $post->url,
            ])
            @endbutton
            @if ($post->likes > 0 || $post->comments > 0)
                <!-- TODO: How to give the footer a light top border? -->
                <footer class="o-container--fullwidth u-border__top u-margin__top--1 u-padding__top--1">
                    @if ($post->likes > 0)
                        @typography([
                            'element' => 'span',
                            'classList' => []
                        ])
                            @typography([
                                'element' => 'span'
                            ])
                                {{ $post->likes }}
                            @endtypography
                            {{ $post->likesText }}
                        @endtypography
                    @endif
                    @if ($post->comments > 0)
                        @typography([
                            'element' => 'span',
                            'classList' => ['comments']
                        ])
                            @typography([
                                'element' => 'span'
                            ])
                                {{ $post->comments }}
                            @endtypography
                            {{ $post->commentsText }}
                        @endtypography
                    @endif
                </footer>
            @endif
        </div>
    </div>
@endmodal
