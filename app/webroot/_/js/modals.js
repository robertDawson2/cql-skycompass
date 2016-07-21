$(document).on('click', '.delete-object', function() {
	var $modal = $('#confirm-delete');
	var $command = $(this);
	$("#confirm-delete .object-name").text($command.data('object-name'));
	$("#confirm-delete .confirm-delete").attr('data-object-id', $command.data('object-id'));
	$modal.modal();
	return false;
});
$(document).on('click', '.confirm-delete', function() {
	var href = $("#delete-modal-action").val().replace('{id}', $(this).data('object-id'));
	window.location.replace(href);
});