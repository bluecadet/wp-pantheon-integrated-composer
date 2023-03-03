(function($){


	/**
	*  initialize_field
	*
	*  This function will initialize the $field.
	*
	*  @date	30/11/17
	*  @since	5.6.5
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function initialize_field( $field ) {

		//$field.doStuff();
		// console.log($field);

		$el = $field.find("div.content-editable");
		// console.log($el);

		$attached_input = $field.find("input");
		// console.log($attached_input);

		$el.on("blur keyup paste input", function () {
			$(this).parents('.simple-formatter-field-container').find("input").val(this.innerHTML);
		});

		$attached_input.on("keyup change", function () {
			$(this).parents('.simple-formatter-field-container').find(".content-editable")[0].innerHTML = $(this).val();
		}).trigger("change").on('invalid', function () {
			// Flip to real form item so browser validation works ok.
			$(this).parents('.simple-formatter-field-container').addClass('raw-mode');
		});

		$el.addClass('ec-bound');

		// console.log($field, $field.find("button.simple-editor-button"));
		$field.find("button.simple-editor-button").on('click', function (e) {

			// console.log(e);
			e.preventDefault();

			if ($(this).hasClass('simple-editor-button--bold')) {
				document.execCommand('bold', false, null);
			}
			if ($(this).hasClass('simple-editor-button--italic')) {
				document.execCommand('italic', false, null);
			}
			if ($(this).hasClass('simple-editor-button--underline')) {
				document.execCommand('underline', false, null);
			}
			if ($(this).hasClass('simple-editor-button--remove_formatting')) {
				document.execCommand('removeFormat', false, null);
			}
			if ($(this).hasClass('simple-editor-button--toggle')) {
				$(this).parents('.simple-formatter-field-container').toggleClass('raw-mode');
			}

		});
	}


	if( typeof acf.add_action !== 'undefined' ) {

		/*
		*  ready & append (ACF5)
		*
		*  These two events are called when a field element is ready for initizliation.
		*  - ready: on page load similar to $(document).ready()
		*  - append: on new DOM elements appended via repeater field or other AJAX calls
		*
		*  @param	n/a
		*  @return	n/a
		*/

		acf.add_action('ready_field/type=text_simple', initialize_field);
		acf.add_action('append_field/type=text_simple', initialize_field);


	} else {

		/*
		*  acf/setup_fields (ACF4)
		*
		*  These single event is called when a field element is ready for initizliation.
		*
		*  @param	event		an event object. This can be ignored
		*  @param	element		An element which contains the new HTML
		*  @return	n/a
		*/

		$(document).on('acf/setup_fields', function(e, postbox){

			// find all relevant fields
			$(postbox).find('.field[data-field_type="text_simple"]').each(function(){

				// initialize
				initialize_field( $(this) );

			});

		});

	}

})(jQuery);
