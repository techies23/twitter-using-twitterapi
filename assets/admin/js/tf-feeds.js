jQuery(document).ready(function($) {
	$('#tf-flush-cache').on('click', function() {
		$.post(ajaxurl, { action: 'tf_delete_json' }).done(function(result) { 
				$('.cache_clear').html('<p>Cleared Cache !!</p>');
				$('.cache_clear').show();
				$('.notice-warning').hide();
				location.reload();
		});
	});

	$('#tf-flush-keys').on('click', function() {
		$.post(ajaxurl, { action: 'tf_flush_keys' }).done(function(result) { 
				$('.cache_clear').html('<p>Flushed Cache !!</p>');
				$('.cache_clear').show();
				$('.notice-warning').hide();
				location.reload();
		});
	});

	$('.tf-color-picker').wpColorPicker();
});

