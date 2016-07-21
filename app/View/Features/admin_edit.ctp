<?php $this->set('title_for_layout', 'Edit Feature'); ?>
<?php $this->Html->script('/_/plugins/tinymce/tinymce.min.js', array('block' => 'scripts')); ?>
<?php $this->Html->script('/_/plugins/tinymce/plugins/moxiemanager/js/moxman.loader.min.js', array('block' => 'scripts')); ?>
<?php $this->Html->script('/_/js/moment.min.js', array('block' => 'scripts')); ?>
<?php $this->Html->script('/_/js/bootstrap-datetimepicker.js', array('block' => 'scripts')); ?>
<?php $this->Html->css('/_/css/datepicker3.css', array('block' => 'css')); ?>

<?php $this->start('scripts'); ?>
<script type="text/javascript">
tinymce.init(tinymceSettings);
function closeCustomRoxy2(){
	$('#roxyCustomPanel2').dialog('close');
}
</script>
<?php $this->end(); ?>
<?php $this->start('jquery-scripts'); ?>
  $("#bgImageBrowse").on('click', function() {
    $('#roxyCustomPanel2').dialog({modal:true, width:875,height:600});
  });

	$("#FeatureBeginShowing").datetimepicker();
	$("#FeatureStopShowing").datetimepicker();
<?php $this->end(); ?>

<form method="post" action="/admin/features/edit">
	<div class="row form-group">	
		<?php echo $this->Form->input('Feature.name', array('div' => 'col-md-6', 'label' => 'Name (internal use only)', 'autofocus', 'class' => 'input form-control')); ?>
		<?php echo $this->Form->input('Feature.headline', array('div' => 'col-md-6', 'label' => 'Headline (optional)', 'class' => 'input form-control')); ?>
	</div>
	<div class="row form-group">
    <?php echo $this->Form->input('Feature.url', array('div' => 'col-md-6', 'label' => 'URL', 'class' => 'input form-control')); ?>
		<div class="col-md-6">
			<?php echo $this->Form->input('Feature.background_image', array('div' => 'input-group', 'label' => 'Background Image', 'class' => 'input form-control', 'after' => '<span class="input-group-btn" style="padding-top: 25px;"><button class="btn btn-primary" id="bgImageBrowse" type="button">Browse</button></span>')); ?>
		</div>
	</div>
  <div class="row form-group"> 
    
    <?php echo $this->Form->input('Feature.x', array('div' => 'col-md-2', 'label' => 'X Position (in pixels)', 'class' => 'input form-control')); ?>
    <?php echo $this->Form->input('Feature.y', array('div' => 'col-md-2', 'label' => 'Y Position (in pixels)', 'class' => 'input form-control')); ?>
    <?php echo $this->Form->input('Feature.width', array('div' => 'col-md-2', 'label' => 'Width', 'class' => 'input form-control')); ?>
    <?php echo $this->Form->input('Feature.height', array('div' => 'col-md-2', 'label' => 'Height', 'class' => 'input form-control')); ?>
    <div class="col-md-2">
      <p style="margin: 0; padding-top: 20px;">Options</p>
      <?php echo $this->Form->input('Feature.show_background_box', array('type' => 'checkbox', 'label' => 'Show background box', 'div' => array('style' => 'margin: 0; padding-left: 30px;'))); ?>
    </div>
  </div>
	<div class="row form-group">
		<?php echo $this->Form->input('Feature.begin_showing', array('div' => 'col-md-4', 'type' => 'text', 'label' => 'Begin Showing (optional)', 'class' => 'input form-control')); ?>
		<?php echo $this->Form->input('Feature.stop_showing', array('div' => 'col-md-4', 'type' => 'text', 'label' => 'Stop Showing (optional)', 'class' => 'input form-control')); ?>
    <?php echo $this->Form->input('Feature.duration', array('div' => 'col-md-2', 'label' => 'Duration (in seconds)', 'class' => 'input form-control')); ?>
    <?php echo $this->Form->input('Feature.order_id', array('type' => 'text', 'div' => 'col-md-2', 'label' => 'Order ID', 'class' => 'input form-control')); ?>
	</div>
	<div class="row form-group">
		<?php echo $this->Form->input('Feature.content', array('div' => 'col-md-12', 'label' => false, 'rows' => '20', 'cols' => '30', 'style' => 'height: 600px;', 'class' => 'tinymce form-control')); ?>
	</div>

	<?php echo $this->Form->hidden('Feature.id'); ?>
	<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
</form>

<div id="roxyCustomPanel2" style="display: none; z-index: 100000;">
  <iframe src="/_/plugins/fileman/index.html?integration=custom&type=files&txtFieldId=FeatureBackgroundImage" style="width:100%;height:100%" frameborder="0">
  </iframe>
</div>