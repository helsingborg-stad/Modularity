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






<!-- use below script as a reference. This needs to be implemented in above. -->


<div>
	<label for="contactEmail">E-post:</label> <input type="email" name="Contact[Email]" id="contactEmail" required /><br/>
	<label for="contactConsentText"><input type="checkbox" required name="ConsentText" id="contactConsentText" value="Jag vill få relevant information från Helsingborgs stad - Kulturförvaltningen till min inkorg. Helsingborgs stad - Kulturförvaltningen ska inte dela eller sälja min personliga information. Jag kan när som helst avsluta prenumerationen." /> Jag vill få relevant information från Helsingborgs stad - Kulturförvaltningen till min inkorg. Helsingborgs stad - Kulturförvaltningen ska inte dela eller sälja min personliga information. Jag kan när som helst avsluta prenumerationen.</label><br/>
	<button onclick="submitForm(event)">Anmälan</button>
</div>

<script type="application/javascript">
function submitForm(event) {
	var form = event.target.parentNode,
	inputs = form.querySelectorAll("input"),
	lists = form.querySelectorAll("[name=ListIds]"),
	selectedLists = [],
	valid = true;
	for(var i = 0; i < inputs.length; i++){
		var input = inputs[i];

		if (input.required === true) {
			if (input.type === "radio" || input.type === "checkbox" && input.checked === false) {
				valid = false;
			}
			else if ((input.type === "email" || input.type === "tel") && !validateType(input.type, input.value)) {
				valid = false;
			}
			else if (input.value === "") {
				valid = false;
			}
		}
	}
	if(valid) {
		var subscription = {
			Contact: {
				Email: form.querySelectorAll("[name=ContactEmail]")[0].value
			},
			ConsentText: "Jag vill få relevant information från Helsingborgs stad - Kulturförvaltningen till min inkorg. Helsingborgs stad - Kulturförvaltningen ska inte dela eller sälja min personliga information. Jag kan när som helst avsluta prenumerationen."
		}
		var xhr = new XMLHttpRequest();
		xhr.open("POST", "https://ui.ungpd.com/Api/Subscriptions/b49e3734-ceb9-4d0e-b286-ac42aa0850df/ajax", true);
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.send(JSON.stringify(subscription));
		alert("Thanks for subscribing!");
	}
	else {
		alert("Oh snap! We were unable to submit the form. Maybe you haven't filled in all required fields or a field has a non valid value. Give it one more go.");
	}
}
function validateType(type, value) {
	if (type === "email") {
		var regEx = /^(([a-zA-Z0-9_\-\+]+)|([a-zA-Z0-9_\-\+]+)([a-zA-Z0-9_\-\+\.]*)([a-zA-Z0-9_\-\+]+))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,63}|[0-9]{1,63})$/;
	}
	if (type === "tel") {
		var regEx = /^((\+|00)\d{1,3})\d{2,4}[\-]?(\d{3,14})$/;
	}
	return regEx.test(value);
}
</script>