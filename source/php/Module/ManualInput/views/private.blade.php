@element([
    'attributeList' => [                
        'data-js-manual-input-user' => $user,
        'data-js-manual-input-id' => $ID,
        'data-js-manual-input-user-ordering' => ''
    ],
    'classList' => [
        'mod-manual-input-private',
    ]
])
    @includeFirst([$template, 'base'],
    [
        'manualInputs' => $filteredManualInputs,
        'titleIcon' => [
            'icon' => 'edit',
            'size' => 'md',
            'attributeList' => [
                'data-open' => 'modal-' . $ID
            ],
            'classList' => [
                'mod-manual-input-private__edit-button'
            ]
        ]
    ])

    @modal([
        'id' => 'modal-' . $ID,
        'size' => 'md'
    ])
    @group([
        'classList' => [
            'o-container',
            'u-padding__bottom--6',
            'u-padding__top--6',
        ],
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
                'mod-manual-input-private__form',
            ],
            'attributeList' => [
                'data-js-manual-input-form' => '',
            ]
        ])
            @group([
                'display' => 'grid',
                'classList' => [
                    'mod-manual-input-private__grid'
                ],
            ])
                    @typography([
                        'element' => 'h3',
                        'classList' => [
                            'mod-manual-input-private__form-title',
                            'mod-manual-input-private__value'
                        ]
                    ])
                        {{ $lang['name'] }}
                    @endtypography
                    @typography([
                        'element' => 'h3',
                        'classList' => [
                            'mod-manual-input-private__form-title',
                            'mod-manual-input-private__description'
                        ]
                    ])
                        {{ $lang['description'] }}
                    @endtypography

                @foreach ($filteredManualInputs as $input)
                    <div class="mod-manual-input-private__value" @if(!empty($input['obligatory'])) data-tooltip="{{ $lang['obligatory'] }}" @endif>
                        @option([
                            'type' => 'checkbox',
                            'value' => $input['uniqueId'],
                            'label' => $input['title'],
                            'attributeList' => array_merge([
                                'name' => $input['uniqueId']], 
                                (!empty($input['obligatory']) ? ['disabled' => ''] : [])
                            ),
                            'checked' => $input['checked'],
                            'classList' => [(!empty($input['obligatory']) ? 'is-disabled' : '')]
                        ])
                        @endoption
                    </div>
                    <div class="mod-manual-input-private__description">
                        @if (!empty($input['linkDescription']))
                            {{ $input['linkDescription'] }}
                        @endif
                    </div>
                @endforeach
            @endgroup

        @endform
            <div class="mod-manual-input-private__buttons">
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
            </div>
        @endgroup
    @endmodal
@endelement
