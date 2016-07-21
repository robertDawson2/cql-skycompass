<?php $this->set('title_for_layout', 'New News Item'); ?>
<?php $this->Html->script('/_/plugins/tinymce/tinymce.min.js', array('block' => 'scripts')); ?>
<?php $this->Html->script('/_/js/moment.min.js', array('block' => 'scripts')); ?>
<?php $this->Html->script('/_/js/bootstrap-datetimepicker.js', array('block' => 'scripts')); ?>
<?php $this->Html->css('/_/css/datepicker3.css', array('block' => 'css')); ?>

<?php $this->start('scripts'); ?>
<script type="text/javascript">
tinymce.init(tinymceSettings);
</script>
<?php $this->end(); ?>
<?php $this->start('jquery-scripts'); ?>
	$("#bgImageBrowse").on('click', function() {
		$('#roxyCustomPanel2').dialog({modal:true, width:875,height:600});
	});
	$("#NewsBeginShowing").datetimepicker();
	$("#NewsStopShowing").datetimepicker();
<?php $this->end(); ?>

<form method="post" action="/admin/news/create">
	<div class="row form-group">	
		<?php echo $this->Form->input('News.headline', array('div' => 'col-md-12', 'label' => 'Headline', 'autofocus', 'class' => 'input form-control')); ?>
	</div>
  	<div class="row form-group"> 
    	<?php echo $this->Form->input('News.url', array('div' => 'col-md-12', 'label' => 'URL', 'class' => 'input form-control')); ?>
  	</div>
	<div class="row form-group">
		<?php echo $this->Form->input('News.begin_showing', array('div' => 'col-md-4', 'type' => 'text', 'label' => 'Begin Showing (optional)', 'class' => 'input form-control')); ?>
		<?php echo $this->Form->input('News.stop_showing', array('div' => 'col-md-4', 'type' => 'text', 'label' => 'Stop Showing (optional)', 'class' => 'input form-control')); ?>
		<?php echo $this->Form->input('Event.order_id', array('div' => 'col-md-2', 'type' => 'text', 'label' => 'Order ID', 'class' => 'input form-control')); ?>
		<div class="col-md-2">
			<p style="margin: 0; padding-top: 20px;">Options</p>
			<?php echo $this->Form->input('Event.stickied', array('type' => 'checkbox', 'label' => 'Stick to top', 'div' => array('style' => 'margin: 0; padding-left: 30px;'))); ?>
		</div>
	</div>
	<div class="row form-group">	
		<?php echo $this->Form->input('News.content', array('div' => 'col-md-12', 'rows' => '20', 'cols' => '30', 'style' => 'height: 600px;', 'class' => 'tinymce form-control')); ?>
	</div>

	<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>

</form>