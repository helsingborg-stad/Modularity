@includeIf($template,
[
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
    'isPanel' => true
])
    <div class="o-container">
    @form([
        'method'    => 'POST',
        'classList' => ['u-print-display--none'],
        'attributeList' => [
            'data-js-manual-input-user' => $user,
            'data-js-manual-input-id' => $ID,
            'data-js-manual-input-form' => ''
        ]
    ])
        @group([
            'display' => 'grid',
            'gap' => 3,
            'attributeList' => [
                'style' => 'grid-template-columns: auto 1fr;'
            ],
            'classList' => [
                'u-margin__bottom--3'
            ],
        ])
                @typography([
                    'element' => 'h3',
                    'variant' => 'h4',
                    'classList' => ['u-margin__top--0']
                ])
                    {{ $lang['name'] }}
                @endtypography
                @typography([
                    'element' => 'h3',
                    'variant' => 'h4',
                    'classList' => ['u-margin__top--0']
                ])
                    {{ $lang['description'] }}
                @endtypography

            @foreach ($manualInputs as $input)
                    <div>
                        @option([
                            'type' => 'checkbox',
                            'value' => $input['uniqueId'],
                            'label' => $input['title'],
                            'attributeList' => ['name' => $input['uniqueId']],
                            'checked' => $input['obligatory'] ?? false
                        ])
                        @endoption
                    </div>
                    <div>
                        @if (!empty($input['linkDescription']))
                            {{ $input['linkDescription'] }}
                        @endif
                    </div>
            @endforeach
        @endgroup

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
                'data-close' => ''
            ],
            'disableColor' => false,
        ])
        @endbutton
    @endform
    </div>    
@endmodal