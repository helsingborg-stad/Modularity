@element([
    'attributeList' => [                
        'data-js-manual-input-user' => $user,
        'data-js-manual-input-id' => $ID,
        'data-js-manual-input-user-ordering' => ''
    ],
])
    @includeFirst([$template, 'base'],
    [
        'manualInputs' => $filteredManualInputs,
        'titleIcon' => [
            'icon' => 'edit',
            'size' => 'md',
            'attributeList' => [
                'data-open' => 'modal-' . $ID,
                'style' => 'cursor: pointer;'
            ]
        ]
    ])

    @modal([
        'id' => 'modal-' . $ID,
        'size' => 'sm',
        'padding' => 4,
        'heading' => $lang['changeContent']
    ])
    @group([
        'direction' => 'vertical',
        'justifyContent' => 'center'
    ])
        @notice([
            'type' => 'error',
            'classList' => [
                'u-display--none',
                'u-print-display--none',
                'u-margin__bottom--2'
            ],
            'message' => ['text' => $lang['error']],
            'attributeList' => [
                'data-js-manual-input-error' => ''
            ],
            'icon' => [
                'name' => 'report',
                'size' => 'md',
                'color' => 'white'
            ]
        ])
        @endnotice
        @form([
            'classList' => [
                'u-print-display--none',
            ],
            'attributeList' => [
                'data-js-manual-input-form' => '',
            ]
        ])
        @collection()
            @foreach ($filteredManualInputs as $input)
                @collection__item([])
                    @slot('prefix')
                        <div class="c-collection__icon" style="margin-top: 4px;" @if(!empty($input['obligatory'])) data-tooltip="{{ $lang['obligatory'] }}" @endif>
                            @option([
                                'type' => 'checkbox',
                                'value' => $input['uniqueId'],
                                'label' => '',
                                'attributeList' => array_merge([
                                    'name' => $input['uniqueId']], 
                                    (!empty($input['obligatory']) ? ['disabled' => ''] : [])
                                ),
                                'checked' => $input['checked'],
                                'classList' => [(!empty($input['obligatory']) ? 'is-disabled' : '')]
                            ])
                            @endoption
                        </div>
                    @endslot
                    @typography([
                        'element' => 'h3',
                        'variant' => 'h3',
                    ])
                        {{ $input['title'] }}
                    @endtypography
                    @if (!empty($input['linkDescription']))
                        @typography([])
                            {{ $input['linkDescription'] }}
                        @endtypography
                    @endif
                @endcollection__item
            @endforeach
        @endcollection
        @endform
        @endgroup
        @slot('bottom')
            @button([
                'text' => $lang['save'],
                'type' => 'submit',
                'size' => 'md',
                'color' => 'primary',
                'disableColor' => false,
                'attributeList' => [
                    'data-js-saving-lang' => $lang['saving'],
                ]
            ])
            @endbutton
            @button([
                'text' => $lang['cancel'],
                'size' => 'md',
                'color' => 'default',
                'attributeList' => [
                    'data-close' => '',
                    'data-js-cancel-save' => ''
                ],
                'disableColor' => false,
            ])
            @endbutton
        @endslot
    @endmodal
@endelement
