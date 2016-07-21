<?php $this->set('title_for_layout', 'Manage Home Page Features'); ?>

        <!--
<?php $this->start('jquery-scripts'); ?>
	$('#features-table').dataTable({	
		"sPaginationType": "bootstrap"
	});
<?php $this->end(); ?>

<?php if (!empty($features)): ?>
        <ul>
<?php foreach ($features as $feature) { ?>
    <li><?php echo $feature['Feature']['name']; ?></li>
<?php } ?>
            </ul>
<a role="button" href="/admin/features/reorder" class="btn btn-primary small"><i class="fa fa-plus"></i>Reorder Features</a>
<?php else: ?>
<p>There are no home page features.</p>
<?php endif; ?>
-->
        <div id="feature-reorder-container">
<ul id="my-list">
    <?php foreach ($features as $feature): ?>
    <li id="Feature_<?php echo $feature['Feature']['id']; ?>"><?php echo $feature['Feature']['name']; ?></li>
    <?php endforeach; ?>
</ul>
<?php
$this->Js->get('#my-list');
$this->Js->sortable(array(
'complete' => '$.post("/admin/features/reorder", $("#my-list").sortable("serialize"))',
));
?>

<a role="button" href="/admin/features" class="btn btn-primary small"><i class="fa fa-check"></i>Save Changes</a>
<!--<a role="button" href="/admin/features" class="btn btn-primary-red small"><i class="fa fa-times"></i>Cancel Changes</a>-->
            </div>