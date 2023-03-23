@form([
    'method' => 'POST',
    'action' => ''
])
    @field([
        'type' => 'text',
        'name' => 'text',
        'label' => "Enter text"
    ])
    @endfield

    @button([
        'text' => 'Submit',
        'color' => 'primary',
        'type' => 'basic'
    ])
    @endbutton

@endform