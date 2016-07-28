<?php $this->set('title_for_layout', 'Manage Navigation'); ?>
<?php $this->Html->script('/_/plugins/jquery.nestable/jquery.nestable.js', array('block' => 'scripts')); ?>
<?php $this->Html->css('/_/plugins/jquery.nestable/jquery.nestable.css', array('block' => 'css')); ?>
<?php $this->start('jquery-scripts'); ?>
$(".dd-navigation").nestable({
	group: 1
});
$(".dd-site-content").nestable({
	group: 1
});
$('input[type=radio]').change(function() {
	if (this.value == 'auto')
		$("#custom").hide();
	else
		$("#custom").show();
});
$("form").on('submit', function() {
	$("#navigation").val(window.JSON.stringify($(".dd-navigation").nestable('serialize')));
});
<?php $this->end(); ?>
<?php
	function printDDList($list) {
		echo '<ol class="dd-list">';
		foreach ($list as $id => $item) {
			echo '<li class="dd-item dd3-item" data-id="' . $id . '">';
			echo '<div class="dd-handle dd3-handle"></div>';
			echo '<div class="dd3-content">' . $item['name'] . '</div>';
			if (isset($item['children']) && !empty($item['children']))
				printDDList($item['children']);
			echo '</li>';
		}
		echo '</ol>';
	}
?>
<form method="post" action="/admin/content/navigation">

<div class="row form-group">
	<div class="col-md-12">
		<div class="radio">
		  <label>
			<input type="radio" name="data[mode]" id="navigationAuto" value="auto" <?php if ($config['site.navigation.mode'] == 'auto') echo 'checked'; ?>>
			Auto Navigation
		  </label>
		</div>
		<div class="radio">
		  <label>
			<input type="radio" name="data[mode]" id="navigationCustom" value="custom" <?php if ($config['site.navigation.mode'] == 'custom') echo 'checked'; ?>>
			Custom Navigation
		  </label>
		</div>
	</div>
</div>

<div class="row form-group" id="custom" style="display: <?php echo $config['site.navigation.mode'] == 'auto' ? 'none' : 'block'; ?>">
	<div class="col-md-6">
		<h4 style="color: #444;">Navigation</h4>
		<div class="well">
			<div class="dd dd-navigation">
				<?php if (!is_array($navigation) || empty($navigation)): ?>
				<div class="dd-empty"></div>
				<?php else: ?>
				<?php printDDList($navigation); ?>
				<?php endif; ?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	
	<div class="col-md-6">
		<h4 style="color: #444;">Site Content</h4>
		<div class="well">
			<div class="dd dd-site-content">
				<?php
					printDDList($content);
				?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>


<?php echo $this->Form->hidden('navigation'); ?>
<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save Changes</button>
