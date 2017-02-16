<?php $this->set('title_for_layout', 'Edit Content'); ?>
<?php $this->Html->script('/_/plugins/tinymce/tinymce.min.js', array('block' => 'scripts')); ?>

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
		$('#roxyCustomPanel2').dialog({modal:true, width:1050,height:600});
                return false;
	});
	$("#ContentPhotoGallery").change(function() {
		if ($(this).val() == '')
			$(".gallery-options").hide();
		else
			$(".gallery-options").show();
	});
	$("#ContentPhotoGallery").change();
<?php $this->end(); ?>

<form method="post" action="/admin/content/edit">

<ul class="nav nav-tabs">
	<li class="active"><a href="#home" data-toggle="tab">Primary Content</a></li>
	<li><a href="#seo" data-toggle="tab">SEO and Social Media</a></li>
</ul>

<div class="tab-content">
	<div class="tab-pane active" id="home">
		<div class="row form-group">	
			<?php echo $this->Form->input('Content.name', array('div' => 'col-md-3', 'label' => 'Page Name', 'autofocus', 'class' => 'input form-control')); ?>
			<?php echo $this->Form->input('Content.title', array('div' => 'col-md-3', 'label' => 'Page Title', 'class' => 'input form-control')); ?>
			<?php echo $this->Form->input('Content.tag', array('div' => 'col-md-3', 'label' => 'URL', 'class' => 'input form-control')); ?>
			<?php echo $this->Form->input('Content.sidebar_title', array('div' => 'col-md-3', 'label' => 'Sidebar Title (Optional)', 'class' => 'input form-control')); ?>
		</div>
		<div class="row form-group">
			<div class="col-md-3">
				<?php echo $this->Form->input('Content.sidebar_image', array('div' => 'input-group', 'label' => 'Sidebar Image (240x240 pixels)', 'class' => 'input form-control', 'after' => '<span class="input-group-btn" style="padding-top: 25px;"><button class="btn btn-primary" id="bgImageBrowse" type="button">Browse</button></span>')); ?>
			</div>
			<?php
				$galleries = array('' => 'None');
				$d = dir(WWW_ROOT . "files/_galleries");
				while (false !== ($entry = $d->read())) {
				   if ($entry != '.' && $entry != '..')
				   	$galleries[$entry] = $entry;
				}
				$d->close();
			?>
			<?php echo $this->Form->input('Content.photo_gallery', array('div' => 'col-md-3', 'label' => 'Photo Gallery', 'class' => 'input form-control', 'options' => $galleries)); ?>
			<?php echo $this->Form->input('Content.status', array('div' => 'col-md-1', 'label' => 'Status', 'class' => 'select form-control', 'options' => array('draft' => 'Draft', 'live' => 'Live'))); ?>
			<?php echo $this->Form->input('Content.order_id', array('type' => 'text', 'div' => 'col-md-1', 'label' => 'Order ID', 'class' => 'input form-control')); ?>
			<div class="col-md-2">
		      <p style="margin: 0; padding-bottom: 3px;">Options</p>
		      <?php echo $this->Form->input('Content.show_sharing_options', array('type' => 'checkbox', 'label' => 'Show sharing options', 'div' => array('style' => 'margin: 0; padding-left: 30px;'))); ?>
		      <?php echo $this->Form->input('Content.hide_from_sidebar', array('type' => 'checkbox', 'label' => 'Hide from sidebar/dropdown', 'div' => array('style' => 'margin: 0; padding-left: 30px;'))); ?>
		    </div>
		    <div class="col-md-2">
		      <?php echo $this->Form->input('Content.hide_sidebar', array('type' => 'checkbox', 'label' => 'Hide sidebar', 'div' => array('style' => 'margin: 22px 0 0; padding-left: 30px;'))); ?>
		      <?php echo $this->Form->input('Content.hide_from_search', array('type' => 'checkbox', 'label' => 'Hide from search', 'div' => array('style' => 'margin: 0; padding-left: 30px;'))); ?>
		    </div>
		</div>
	    <div class="row form-group gallery-options">	
			<?php echo $this->Form->input('Content.gallery_width', array('div' => 'col-md-2', 'label' => 'Gallery Width', 'class' => 'input form-control')); ?>
			<?php echo $this->Form->input('Content.gallery_height', array('div' => 'col-md-2', 'label' => 'Gallery Height', 'class' => 'input form-control')); ?>
			<?php echo $this->Form->input('Content.gallery_position', array('div' => 'col-md-2', 'label' => 'Gallery Position', 'class' => 'input form-control', 'options' => array('top' => 'Top', 'bottom' => 'Bottom'))); ?>
			<?php echo $this->Form->input('Content.gallery_alignment', array('div' => 'col-md-2', 'label' => 'Gallery Alignment', 'class' => 'input form-control', 'options' => array('' => 'None', 'left' => 'Left', 'right' => 'Right'))); ?>
			<?php echo $this->Form->input('Content.gallery_timing', array('div' => 'col-md-2', 'label' => 'Gallery Timing (milliseconds)', 'class' => 'input form-control')); ?>
		</div>
		<div class="row form-group">
			<?php echo $this->Form->input('Content.content', array('div' => 'col-md-12', 'label' => false, 'rows' => '20', 'cols' => '30', 'style' => 'height: 600px;', 'class' => 'tinymce form-control')); ?>
		</div>
	</div>

	<div class="tab-pane" id="seo">
		<div class="row form-group">
			<?php echo $this->Form->input('Content.meta_description', array('div' => 'col-md-12', 'rows' => '6', 'class' => 'textarea form-control')); ?>
		</div>
		<div class="row form-group">
			<?php echo $this->Form->input('Content.meta_keywords', array('div' => 'col-md-12', 'rows' => '6', 'class' => 'textarea form-control')); ?>
		</div>
		<div class="row form-group">
			<?php echo $this->Form->input('Content.social_media_summary', array('div' => 'col-md-12', 'rows' => '6', 'class' => 'textarea form-control')); ?>
		</div>
		<div class="row form-group">
			<?php echo $this->Form->input('Content.redirect_url', array('div' => 'col-md-12', 'label' => 'Redirect', 'class' => 'select form-control', 'options' => $redirects)); ?>
		</div>
	</div>
</div>

<?php echo $this->Form->hidden('Content.id'); ?>
<?php echo $this->Form->hidden('Content.parent_id'); ?>
<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save Changes</button>


</form>

<div id="roxyCustomPanel2" style="display: none; z-index: 100000;">
  <iframe src="/adminPanel/plugins/filemanager/dialog.php?type=1&field_id=ContentSidebarImage" style="width:100%;height:100%" frameborder="0">
  </iframe>
</div>