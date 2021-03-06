jQuery(function($) {
	$(window).bind("popstate", function() {
		window.location = location.href;
	});
	// global shop stuff; to be used fired again after filtering
	window.themePagination = function() {
		// site wide pagination with conditional
		if ($(".uk-pagination").length) {
			$('.archive-posts').infiniteScroll({
				path: '.next',
				append: '.item',
				status: '.page-load-status',
				hideNav: '.pagi',
				history: false,
                button: '.view-more-button',
                scrollThreshold: false,
			});
		}
	};
	// add to cart button triggers modal when item is added to cart
	$(document).on('added_to_cart', function(e, fragments, cart_hash, this_button) {
		var modal = UIkit.modal("#MiniCartModal");
		modal.show();
	});
	// ajax search js
	$(document).on("input", "#input_search", _.debounce(function() {
		var input_value = $(this).val();
		var query = input_value;
		var req;
		
		if (query.length < 2) {
			$('#response_search_results').hide();
			return false;
		}
		
		if (req != null) req.abort();
		
		req = $.ajax({
			type: 'post',
			url: myAjax.ajaxurl,
			data: {
				action: 'ajax_live_search',
				query: query
			},
			success: function(response) {
				if (!response) {
					alert('empty');
					$('#response_search_results').hide();
					return;
				}
				var obj = JSON.parse(response);
				if (obj.result == 1) {
					document.getElementById("response_search_results").innerHTML = obj.response;
					$('#response_search_results').show();
				}
				$('#response_search_results ul li a').wrapInTag({
					words: [input_value]
				});
			},
			error: function(request, status, error) {
				$('#response_search_results').hide();
			}
		});
	}, 500));
	// search results hide on additional click away
	$(document).on('click', function(e) {
		if ($(e.target).closest(".top-search-bar").length === 0) {
			$("#response_search_results").hide();
		}
	});
	// ajax search mobile js
	$(document).on("input", "#input_search_mobile", _.debounce(function() {
		var input_value = $(this).val();
		var query = input_value;
		var req;
		
		if (query.length < 2) {
			$('#response_search_results_mobile').hide();
			return false;
		}
		
		if (req != null) req.abort();
		
		req = $.ajax({
			type: 'post',
			url: myAjax.ajaxurl,
			data: {
				action: 'ajax_live_search_mobile',
				query: query
			},
			success: function(response) {
				if (!response) {
					alert('empty');
					$('#response_search_results_mobile').hide();
					return;
				}
				var obj = JSON.parse(response);
				if (obj.result == 1) {
					document.getElementById("response_search_results_mobile").innerHTML = obj.response;
					$('#response_search_results_mobile').show();
				}
				$('#response_search_results_mobile ul li a').wrapInTag({
					words: [input_value]
				});
			},
			error: function(request, status, error) {
				$('#response_search_results_mobile').hide();
			}
		});
	}, 500));
	// search results hide on additional click away
	$(document).on('click', function(e) {
		if ($(e.target).closest(".top-search-bar-mobile").length === 0) {
			$("#response_search_results_mobile").hide();
		}
	});
	// helper function to highlight search results text
	$.fn.wrapInTag = function(opts) {
		function getText(obj) {
			return obj.textContent ? obj.textContent : obj.innerText;
		}
		var tag = opts.tag || 'strong',
			words = opts.words || [],
			regex = RegExp(words.join('|'), 'gi'),
			replacement = '<' + tag + '>$&</' + tag + '>';
		$(this).contents().each(function() {
			if (this.nodeType === 3) {
				$(this).replaceWith(getText(this).replace(regex, replacement));
			} else if (!opts.ignoreChildNodes) {
				$(this).wrapInTag(opts);
			}
		});
	};
});