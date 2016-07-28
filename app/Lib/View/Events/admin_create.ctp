<?php $this->set('title_for_layout', 'New Event'); ?>
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
	$("#EventBeginShowing").datetimepicker();
	$("#EventStartDate").datetimepicker();
	$("#EventEndDate").datetimepicker();
<?php $this->end(); ?>

<form method="post" action="/admin/events/create">
	<div class="row form-group">	
		<?php echo $this->Form->input('Event.title', array('div' => 'col-md-12', 'label' => 'Title', 'autofocus', 'class' => 'input form-control')); ?>
	</div>
  <div class="row form-group"> 
    <?php echo $this->Form->input('Event.url', array('div' => 'col-md-12', 'label' => 'URL', 'class' => 'input form-control')); ?>
  </div>
	<div class="row form-group">
		<?php echo $this->Form->input('Event.start_date', array('div' => 'col-md-6', 'type' => 'text', 'label' => 'Start Date/Time', 'class' => 'input form-control')); ?>
		<?php echo $this->Form->input('Event.end_date', array('div' => 'col-md-6', 'type' => 'text', 'label' => 'End Date/Time', 'class' => 'input form-control')); ?>
	</div>
	<div class="row form-group">
		<?php echo $this->Form->input('Event.begin_showing', array('div' => 'col-md-6', 'type' => 'text', 'label' => 'Begin Showing', 'class' => 'input form-control')); ?>
		<?php echo $this->Form->input('Event.order_id', array('div' => 'col-md-3', 'type' => 'text', 'label' => 'Order ID', 'class' => 'input form-control')); ?>
		<div class="col-md-3">
			<p style="margin: 0; padding-bottom: 3px;">Options</p>
      		<?php echo $this->Form->input('Event.all_day', array('type' => 'checkbox', 'label' => 'All day', 'div' => array('style' => 'margin: 0; padding-left: 30px;'))); ?>
			<?php echo $this->Form->input('Event.stickied', array('type' => 'checkbox', 'label' => 'Stick to top', 'div' => array('style' => 'margin: 0; padding-left: 30px;'))); ?>
		</div>
	</div>

	<div class="row form-group">	
		<?php echo $this->Form->input('Event.description', array('div' => 'col-md-12', 'label' => 'Description', 'rows' => '20', 'cols' => '30', 'style' => 'height: 600px;', 'class' => 'tinymce form-control')); ?>
	</div>

	<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>

</form>