<!-- Map --> {{$cardMoreCardCss}}
<div class="o-grid o-grid--equal-elements modularity-map-container">
    <div class="modularity-map-container__map-box {{$cardMapCss}}">
        @card([
            'classList' => [
                'modularity-map-container__map',
            ],
            'attributeList' => [
                'aria-labelledby' => 'mod-map-' . $id .'-label'
            ],
            'context' => 'module.map'
        ])
            @if (!$hideTitle && !empty($post_title))
                <div class="c-card__header">
                    @icon(['icon' => 'room', 'size' => 'md', 'color' => 'primary', 'classList' => ['u-margin__right--1']])
                    @endicon
        
                    @typography([
                        'element' => 'h4',
                        'variant' => 'p',
                        'id'      => 'mod-map-' . $id .'-label'
                    ])
                        
                        {!! apply_filters('the_title', $post_title) !!}
                    @endtypography
                </div>
            @endif
        
            @if($show_button)
                @button([
                    'type' => 'filled',
                    'color' => 'primary',
                    'text' => $button_label,
                    'size' => 'sm',
                    'attributeList' => ['data-open' => 'modal-' . $uid],
                    'classList' => ['u-display--block@xs', 'u-display--block@sm', 'modularity-mod-map__button ']
                ])
                @endbutton
            @endif
            
            @modal([
                    'id' => 'modal-' . $uid,
                    'isPanel' => true,
                    'animation' => 'slide-up',
                    'padding' => 0,
                    'heading' => $post_title
            ])
                <iframe src="{{ $button_url }}" frameborder="0" class="u-width--100 u-display--block" style="height: 100vh;" aria-label="{{ $map_description }}"></iframe>
            @endmodal
        
            <div class="c-card__body">
                <iframe src="{{ $map_url }}" frameborder="0" class="u-width--100 u-display--block" style="min-height: {{ $height }}px;" title="{{ $map_description }}"></iframe>
            </div>
        
        @endcard
    </div>
    
    <!-- More information -->
    @if ($more_info_button)
        
        <div class="modularity-map-container__more-info {{$cardMoreInfoCss}}">
            @card([
                'attributeList' => [
                    'aria-labelledby' => 'mod-map-' . $id .'-label-moreinfo'
                ],
                'context' => 'module.map'
            ])
                @if (!$hideTitle && !empty($post_title))
                    <div class="c-card__header">
                        @icon(['icon' => 'info', 'size' => 'md', 'color' => 'primary', 'classList' => ['u-margin__right--1']])
                        @endicon
                        
                        @typography([
                            'element' => 'h4',
                            'variant' => 'p',
                            'id'      => 'mod-map-' . $id .'-label-moreinfo'
                        ])
                        
                            {!! $more_info_title !!}
                        @endtypography
                    </div>
                @endif
                <div class="c-card__body" style="min-height: {{$height}}px">
                    {!! $more_info !!}
                </div>
            @endcard
        </div>
        
    @endif
</div>
