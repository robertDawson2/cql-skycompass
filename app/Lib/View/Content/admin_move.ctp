<?php $this->set('title_for_layout', 'Move Page'); ?>

<p>Select the destination for the <b><?php echo $content['Content']['name']; ?></b> page. Please note that moving a page will cause its URL to change, and will also change the URLs of any children of that page. Navigation links will be automatically updated, but links within content will need to be updated manually.</p>

<form method="post" action="/admin/content/move/<?php echo $content['Content']['id']; ?>">
	<div class="row form-group">	
		<?php echo $this->Form->input('Content.parent_id', array('div' => 'col-md-12', 'label' => 'Destination', 'class' => 'select form-control', 'options' => $parents)); ?>
	</div>

	<?php echo $this->Form->hidden('Content.id'); ?>
	<button type="submit" class="btn btn-primary" id="saveAndPublish"><i class="fa fa-mail-forward"></i> Move Page</button>

</form>