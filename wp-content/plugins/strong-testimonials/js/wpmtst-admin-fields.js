/**
 *	Strong Testimonials > Custom Fields Editor
 */
jQuery(document).ready(function($) {

	// Function to get the Max value in Array
	Array.max = function( array ){
		return Math.max.apply( Math, array );
	};

	// Convert "A String" to "a_string"
	function convertLabel(label) {
		return label.replace(/\s+/g, "_").replace(/\W/g, "").toLowerCase();
	}

	// Remove invalid characters
	function removeSpaces(word) {
		//return word.replace(/\s+/g, "_");
		return word.replace(/[^\w\s(?!\-)]/gi, '')
	}

	/**
	 * Custom fields
	 */

	$(".custom-field").hide();
	var $fieldList = $("#custom-field-list");

	// check all field names
	$("#wpmtst-custom-fields-form").submit(function(event){
		$("input.field-name").each(function(index){
			if( 'name' == $(this).val() || 'date' == $(this).val() ) {
				$(this).focus().parent().find('.field-name-help.important').addClass('error');
				var $parent = $(this).closest("li");
				if(!$parent.hasClass("open")) {
					$parent.find("a.field").click();
				}
				event.preventDefault();
			} else {
				$(this).parent().find('.field-name-help').removeClass('error');
			}
		});
	});

	// sortable
	$fieldList.sortable({
		placeholder: "sortable-placeholder",
		forcePlaceholderSize: true,
		handle: ".handle",
		cursor: "move",
	});

	// click handler (delegated)
	$fieldList.on("click", "a.field", function(e){
		$(this)
			.blur()
			.closest("li")
			.toggleClass("open")
			.find(".custom-field")
			.toggleClass("open")
			.slideToggle("slow")
			.find(".first-field")
			.focus()
			.select();
		return false;
	});

	// update list item label when field label changes
	$fieldList.on("change blur", "input.field-label", function(e){
		var newLabel = $(this).val();
		var $parent = $(this).closest("li");

		// fill in blank label
		if( ! $(this).val() ) {
			$(this).val("New Field");
		}

		// update parent list item
		$parent.find("a.field").html(newLabel);

		// fill in blank field name
		var $fieldName = $parent.find("input.field-name");
		if( ! $fieldName.val() ) {
			var newFieldName = convertLabel(newLabel);
			$fieldName.val(newFieldName);
		}
	});

	// fill in blank field name
	$fieldList.on("blur", "input.field-name", function(e){
		var fieldLabel = $(this).closest(".field-table").find(".field-label").val();

		if( ! $(this).val() ) {
			var newFieldName = convertLabel(fieldLabel);
			$(this).val(newFieldName);
			return;
		}
		if( 'name' == $(this).val() || 'date' == $(this).val() ) {
			$(this).focus().parent().find('.field-name-help.important').addClass('error');
		} else {
			$(this).parent().find('.field-name-help').removeClass('error');
		}
	});

	// restore defaults
	$("#restore-defaults").click(function(){
		return confirm("Restore the default fields?");
	});

	// delete field
	$fieldList.on("click", ".delete-field", function(){
		var thisField = $(this).closest("li");
		var thisLabel = thisField.find(".field").html();
		var yesno = confirm('Delete "' + thisLabel + '"?');
		if( yesno ) {
			thisField.fadeOut(function(){$(this).remove()});
			// enable "Add New Field" button
			$("#add-field").removeAttr("disabled");
		}
	});

	// close field
	$fieldList.on("click", "span.close-field a", function(){
		$(this)
			.blur()
			.closest("li")
			.toggleClass("open")
			.find(".custom-field")
			.toggleClass("open")
			.slideToggle();
		return false;
	});


	// -------------
	// Add new field
	// -------------
	$("#add-field").click(function(e) {
		var keys = $("#custom-field-list > li").map(function() {
			var key_id = $(this).attr("id");
			return key_id.substr( key_id.lastIndexOf("-")+1 );
		}).get();
		var nextKey = Array.max(keys)+1;

		var data = {
			'action'     : 'wpmtst_add_field',
			'key'        : nextKey,
			'fieldClass' : null,
			'fieldType'  : null,
		};
		$.get( ajaxurl, data, function( response ) {
			// disable Add button
			$("#add-field").attr("disabled","disabled");

			// create list item
			var $li = $('<li id="field-'+nextKey+'">').append( response );

			// append to list
			$fieldList.append($li);

			// ---------------------------------------------------------
			// Disable any Post fields already in use.
			// ---------------------------------------------------------
			// Doing this client-side so a Post field can be added
			// but not saved before adding more fields;
			// i.e. add multiple fields of either type without risk
			// of duplicating single Post fields before clicking "Save".
			// ---------------------------------------------------------
			$fieldList.find('input[name$="[record_type]"]').each(function(index) {
				if( "post" == $(this).val() ) {
					var name = $(this).closest("li").find(".field-name").val();
					$li.find("select.field-type.new").find('option[value="'+name+'"]').attr("disabled","disabled");
				}
			});

			// hide "Close" link until Type is selected
			$("span.close-field").hide();

			// click it to open
			$li.find("a.field").click();
		});
	});


	// -----------------
	// Field type change
	// -----------------
	$fieldList
		.on("focus", ".field-type", function() {
			// store existing values on parent element

			// find parent element
			var fieldType = $(this).val();
			var $parent = $(this).closest("li");
			$parent.data('fieldType',fieldType);

			// label
			var $fieldLabel = $parent.find('input.field-label');
			$fieldLabel.data('oldValue',$fieldLabel.val());

			// name
			var $fieldName = $parent.find('input.field-name');
			$fieldName.data('oldValue',$fieldName.val());

			// input_type
			var $fieldInputType = $parent.find('input[name$="[input_type]"]');
			$fieldInputType.data('oldValue',$fieldInputType.val());

		})
		.on("change", ".field-type", function() {

			var fieldType = $(this).val();

			var $table = $(this).closest("table");
			var $parent = $(this).closest('li');

			var key_id = $parent.attr("id");
			var key = key_id.substr( key_id.lastIndexOf("-")+1 );

			var $fieldLabel = $parent.find('input.field-label');
			var $fieldName  = $parent.find('input.field-name');
			var $fieldInputType = $parent.find('input[name$="[input_type]"]');

			// Force values if selecting a Post field.

			// get type of field from its optgroup
			var fieldClass = $(this)
				.find("option[value='"+fieldType+"']")
				.closest("optgroup")
				.attr("class");

			// Are we adding a new field or changing an existing one?
			if( $parent.data('fieldType') != 'none' ) {

				// --------
				// changing
				// --------
				// could be changing after being *added* but before being *saved*

				switch( fieldClass ) {
					case "post":

						if( fieldType == 'post_title' ) {
							$fieldLabel.val('Testimonial Title');
							$fieldName.val('post_title').attr('disabled','disabled');
							// move value to hidden input
							$fieldName.after('<input type="hidden" name="'+$fieldName.attr("name")+'" value="'+$fieldName.val()+'" />');
							// change input_type
							$parent.find('input[name$="[input_type]"]').val("text");
							// hide help message
							$parent.find(".field-name-help").hide();
						}
						else if( fieldType == 'featured_image' ) {
							$fieldLabel.val('Photo');
							$fieldName.val('featured_image').attr('disabled','disabled');
							// move value to hidden input
							$fieldName.after('<input type="hidden" name="'+$fieldName.attr("name")+'" value="'+$fieldName.val()+'" />');
							// change input_type
							$parent.find('input[name$="[input_type]"]').val("file");
						}
						else if( fieldType == 'post_content' ) {
							$fieldLabel.val('Testimonial');
							$fieldName.val('post_content').attr('disabled','disabled');
							// move value to hidden input
							$fieldName.after('<input type="hidden" name="'+$fieldName.attr("name")+'" value="'+$fieldName.val()+'" />');
							// hide help message
							$parent.find(".field-name-help").hide();
						}
						break;

					case "custom":

						// if switching back from Post field to Custom field
						var fieldName = $fieldName.val();
						if( fieldName == 'post_title' || fieldName == 'featured_image' ) {
							// restore previous values
							$fieldLabel.val($fieldLabel.data('oldValue'));
							$fieldName.val($fieldName.data('oldValue')).removeAttr('disabled');
							$fieldInputType.val($fieldInputType.data('oldValue'));
							$parent.find(".custom-field-header a.field").html( $fieldLabel.val() );
							// remove hidden input
							$fieldName.next('input:hidden').remove();
							// show help message
							$parent.find(".field-name-help").show();
						}

						break;

					case "optional":

						if( fieldType == 'categories' ) {
							$fieldName.val('category').attr('disabled','disabled');
							// move value to hidden input
							$fieldName.after('<input type="hidden" name="'+$fieldName.attr("name")+'" value="'+$fieldName.val()+'" />');
							// hide help message
							$parent.find(".field-name-help").hide();
						}
						break;

					default:
				}

				// update admin_table setting
				var data = {
					'action'     : 'wpmtst_add_field_4',
					'key'        : key,
					'fieldClass' : fieldClass,
					'fieldType'  : fieldType,
				};
				$.get( ajaxurl, data, function( response ) {
					$table.find("tr.field-admin-table").replaceWith(response);
				});

			}
			else {

				// ------
				// adding
				// ------

				if( fieldClass == 'post' ) {

					if( fieldType == 'post_title' ) {
						$fieldLabel.val('Testimonial Title');
						$fieldName.val('post_title').attr('disabled','disabled');
						// add hidden input
						$fieldName.after('<input type="hidden" name="'+$fieldName.attr("name")+'" value="'+$fieldName.val()+'" />');
						// hide help message
						$parent.find(".field-name-help").hide();
					}
					else if( fieldType == 'featured_image' ) {
						$fieldLabel.val('Photo');
						$fieldName.val('featured_image').attr('disabled','disabled');
						// add hidden input
						$fieldName.after('<input type="hidden" name="'+$fieldName.attr("name")+'" value="'+$fieldName.val()+'" />');
					}
					else if( fieldType == 'post_content' ) {
						$fieldLabel.val('Testimonial');
						$fieldName.val('post_content').attr('disabled','disabled');
						// add hidden input
						$fieldName.after('<input type="hidden" name="'+$fieldName.attr("name")+'" value="'+$fieldName.val()+'" />');
						// hide help message
						$parent.find(".field-name-help").hide();
					}

				}

				if( fieldType == 'categories' ) {
					$fieldName.val('category').attr('disabled','disabled');
					// move value to hidden input
					$fieldName.after('<input type="hidden" name="'+$fieldName.attr("name")+'" value="'+$fieldName.val()+'" />');
					// hide help message
					$parent.find(".field-name-help").hide();
				}

				// Nesting Ajax calls for now.
				// secondary form fields
				var data1 = {
					'action'     : 'wpmtst_add_field_2',
					'key'        : key,
					'fieldClass' : fieldClass,
					'fieldType'  : fieldType,
				};
				$.get( ajaxurl, data1, function( response ) {

					$table.append(response);

					// admin-table field
					var data2 = {
						'action'     : 'wpmtst_add_field_4',
						'key'        : key,
						'fieldClass' : fieldClass,
						'fieldType'  : fieldType,
					};
					$.get( ajaxurl, data2, function( response ) {

						$table.append(response);

						// hidden inputs
						var data3 = {
							'action'     : 'wpmtst_add_field_3',
							'key'        : key,
							'fieldClass' : fieldClass,
							'fieldType'  : fieldType,
						};
						$.get( ajaxurl, data3, function( response ) {

							$table.parent().append(response);

						});

					});

				});


				// Successfully added so show "Close" link...
				$("span.close-field").show();
				// ...and enable "Add New Field" button.
				$("#add-field").removeAttr("disabled");
			}

			// update parent list item...
			$parent.find(".custom-field-header a.field").html( $fieldLabel.val() );
			// ...and stored fieldType
			$parent.data('fieldType',fieldType);

			// update hidden [record_type] input
			$parent.find('input[name$="[record_type]"]').val(fieldClass);

		}); // on(change)

});
