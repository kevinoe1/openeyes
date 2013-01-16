$(document).ready(function() {
	/**
	 * Add all optional elements
	 */
	$('#optionals_all').delegate('#add-all', 'click', function(e) {
		$('#inactive_elements .element').each(function() {
			addElement(this, false);
		});
		e.preventDefault();
	});

	/**
	 * Add an optional element
	 */
	$('#inactive_elements').delegate('.element', 'click', function(e) {
		if (!$(this).hasClass('clicked')) {
			$(this).addClass('clicked');
			addElement(this);
		}
		e.preventDefault();
	});
	
	function addElement(element, animate, is_child) {
		if (typeof (animate) === 'undefined')
			animate = true;
		if (typeof (is_child) === 'undefined')
			is_child = false;
		
		var element_type_id = $(element).attr('data-element-type-id');
		var display_order = $(element).attr('data-element-display-order');
		$.get(baseUrl + "/" + moduleName + "/Default/ElementForm", {
			id : element_type_id,
			patient_id : et_patient_id,
		}, function(data) {
			if (is_child) {
				var container = $(element).closest('.inactive_child_elements').parent().find('.active_child_elements:first');
			} else {
				var container = $('#active_elements');
			}
			
			var insert_before = container.find('.element').first();
			
			while (parseInt(insert_before.attr('data-element-display-order')) < parseInt(display_order)) {
				insert_before = insert_before.nextAll('div:first');
			}
			$(element).remove();
			if (insert_before.length) {
				insert_before.before(data);
			} else {
				$(container).append(data);
			}
			$('#event_display textarea.autosize:visible').autosize();
				
			var inserted = (insert_before.length) ? insert_before.prevAll('div:first') : container.find('.element:last');
			if (animate) {
				var offTop = inserted.offset().top - 50;
				var speed = (Math.abs($(window).scrollTop() - offTop)) * 1.5;
				$('body').animate({
					scrollTop : offTop
				}, speed, null, function() {
					$('.elementTypeName', inserted).effect('pulsate', {
						times : 2
					}, 600);
				});
			}

			var el_class = $(element).attr('data-element-type-class');
			var initFunctionName = el_class.replace('Element_', '') + '_init';
			if(typeof(window[initFunctionName]) == 'function') {
				window[initFunctionName]();
			}

			// now init any children
			$(".element." + el_class).find('.active_child_elements').find('.element').each(function() {
				var initFunctionName = $(this).attr('data-element-type-class').replace('Element_', '') + '_init';
				if(typeof(window[initFunctionName]) == 'function') {
					window[initFunctionName]();
				}
			});
		});
	}

	/**
	 * Remove all optional elements
	 */
	$('#optionals_all').delegate('#remove-all', 'click', function(e) {
		$('#active_elements .element').each(function() {
			removeElement(this);
		});
		e.preventDefault();
	});

	/**
	 * Remove an optional element
	 */
	$('#active_elements').delegate('.removeElement button', 'click', function(e) {
		if (!$(this).parents('.active_child_elements').length) {
			var element = $(this).closest('.element');
			removeElement(element);
		}
		e.preventDefault();
	});
	
	/*
	 * Remove a child element
	 */
	$('#active_elements').delegate('.active_child_elements .removeElement button', 'click', function(e) {
		var element = $(this).closest('.element');
		removeElement(element, true);
		e.preventDefault();
		
	})

	function removeElement(element, is_child) {
		if (typeof(is_child) == 'undefined')
			is_child = false;
		var element_type_name = $(element).attr('data-element-type-name');
		var display_order = $(element).attr('data-element-display-order');
		$(element).html($('<h5>' + element_type_name + '</h5>'));
		if (is_child) {
			var container = $(element).closest('.active_child_elements').parent().find('.inactive_child_elements:last');
		}
		else {
			var container = $('#inactive_elements');
		}
		var insert_before = $(container).find('.element').first();
		while (parseInt(insert_before.attr('data-element-display-order')) < parseInt(display_order)) {
			insert_before = insert_before.next();
		}
		if (insert_before.length) {
			insert_before.before(element);
		} else {
			$(container).append(element);
		}
	}
	
	/**
	 * Add optional child element
	 */
	$("#active_elements").delegate('.inactive_child_elements .element', 'click', function(e) {
		if (!$(this).hasClass('clicked')) {
			$(this).addClass('clicked');
			addElement(this, true, true);
		}
		e.preventDefault();
	});
});