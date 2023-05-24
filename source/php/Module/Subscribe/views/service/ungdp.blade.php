@form([
    'id' => $uid,
    'method' => 'POST',
    'action' => '#' . $uid,
    'attributeList' => [
      'data-js-ungpd-id' => $formID 
		],
		'errorMessage' => $lang->incomplete->text,
		'validateMessage' => $lang->submitted->text
])
  @group([
    'alignItems' => 'end',
    'classList' => ['u-margin__bottom--1']
  ])

    @field([
      'type' => 'email',
      'placeholder' => $lang->email->placeholder,
      'name' => 'email',
      'autocomplete' => 'email',
      'invalidMessage' => $lang->email->error,
      'label' => $lang->email->label,
      'required' => true,
      'attributeList' => [
        'data-js-ungpd-email' => ''
      ],
    ])
    @endfield

    @button([
      'text' => $lang->submit->label,
      'color' => 'primary',
      'type' => 'filled',
      'icon' => 'arrow_forward'
    ])
    @endbutton

  @endgroup

  @option([
      'type' => 'checkbox',
      'attributeList' => [
        'name' => 'user_consent',
        'data-js-ungpd-consent'
      ],
      'label' => $consentMessage,
			'value' => $consentMessage,
			'required' => true
  ])
  @endoption

@endform

<template id="{!!$formID!!}">
	@notice([
		'type' => 'success',
			'message' => [
			'text' => $lang->submitted->text,
			'title' => $lang->submitted->title,
			'size' => 'sm'
		],
		'icon' => [
			'name' => 'check',
			'size' => 'md',
			'color' => 'white'
		]
	])
	@endnotice
</template>
