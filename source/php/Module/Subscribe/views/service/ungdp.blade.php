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
	@typography(['variant' => 'h3']){{$lang->submitted->title}}@endtypography
	@notice([
		'type' => 'success',
			'message' => [
			'text' => $lang->submitted->text,
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

<script>
	function handleSuccess(form, successTemplate) {
		const successElement = successTemplate.content.cloneNode(true);
		
		form.remove();
		successTemplate.parentNode.appendChild(successElement);
	}

	const ungpdForms = [...document.querySelectorAll("[data-js-ungpd-id]")]; 

	ungpdForms.forEach(form => {
		form.addEventListener("submit", (event) => {

			//Prevent default
			event.preventDefault();

			//Gather data
			let formId 	= form.getAttribute('data-js-ungpd-id');
			let email 	= form.querySelector('input[name="email"]');
			let consent = form.querySelector('input[name="user_consent"]');
			const successTemplate = document.querySelector('template#' + formId);

			//Dialogs
			let dialog  = form.parentNode.querySelectorAll('data-js-error-message'); 

			//Form validates, empty data, send request
			if(formId && email.value && consent.checked) {
				
				let subscription = {
					Contact: {Email: email.value},
					ConsentText: consent.value
				};

				fetch("https://ui.ungpd.com/Api/Subscriptions/" + formId + "/ajax", {
					method: "POST",
					headers: {
						"Content-Type": "application/json"
					},
					body: JSON.stringify(subscription)
				})
				.then(response => {
					if (!response.ok) {
						alert('{{$lang->error->text}} (err: ' + response.status + ')');
					} else {
						handleSuccess(form, successTemplate);
					}
				})
				.catch(error => {
					alert('{{$lang->error->text}} (err: ' + error + ')');
				});

				consent.checked = false;
				email.value 		= "";
			}
			
		});
	});
</script>