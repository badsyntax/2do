
$.ajaxSetup({
	dataType: 'json',
	error: function(xhr, textStatus, error, callback) {
			alert('Something went wrong! Please try again.');
		}

});
