<div id="confirm-delete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirm-delete" aria-hidden="true">
<div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">	
    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h3 id="confirm-delete-label"><?php echo $title; ?></h3>
	</div>
	<div class="modal-body">
		<p>Are you sure you want to <?php echo str_replace('{name}', '<b><span class="object-name"></span></b>', $text); ?>?<?php if (!isset($undo) || $undo == false): ?> This action cannot be undone.<?php endif; ?></p>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
		<a href="<?= $action; ?>" data-dismiss="modal" class="btn btn-danger confirm-delete"><i class="fa fa-trash-o"></i> Delete</a>
	</div>
	<input type="hidden" id="delete-modal-action" value="<?php echo $action; ?>">
    </div>
</div>
</div>