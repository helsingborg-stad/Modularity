document.addEventListener('DOMContentLoaded',() => {
    if (typeof acf !== 'undefined') {
        init();
    }
});

function init() {
    var imageSelect = acf.Field.extend({ 
        type: 'image_select',
    });

	acf.registerFieldType(imageSelect);
	acf.registerConditionForFieldType('equalTo', 'image_select');
	acf.registerConditionForFieldType('notEqualTo', 'image_select');
}
