<?php $this->set('title_for_layout', 'Manage Content'); ?>

<?php $this->start('jquery-scripts'); ?>
	$('#content-table').dataTable({	
		"sPaginationType": "bootstrap"
	});
<?php $this->end(); ?>

<?php if (!empty($contents)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="content-table">
	<thead>
		<tr>
			<th>Page Name</th>
			<th>Page Title</th>
            <th>Order</th>
			<th>URL</th>
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($contents as $content) { ?>
		<tr>
			<td><?php echo $content['Content']['name']; ?><?php if ($content['Content']['status'] == 'draft'): ?> (DRAFT)<?php endif; ?></td>
			<td><?php echo $content['Content']['title']; ?></td>
            <td><?php echo $content['Content']['order_id']; ?></td>
			<td><?php echo $content['Content']['tag']; ?></td>
			
			<td>
				<a role="button" class="btn btn-primary" href="/admin/content/edit/<?php echo $content['Content']['id']; ?>"><i class="fa fa-edit"></i> Edit</a>&nbsp;
				<a role="button" class="btn btn-primary" href="/admin/content/index/<?php echo $content['Content']['id']; ?>"><i class="fa fa-list-alt"></i> View Sub-Pages</a>&nbsp;
				<?php if (0): ?><a role="button" class="btn btn-primary" href="/admin/content/history/<?php echo $content['Content']['id']; ?>"><i class="fa fa-clock-o"></i> View History</a>&nbsp;<?php endif; ?>
				<a role="button" class="btn btn-primary" href="/admin/content/move/<?php echo $content['Content']['id']; ?>"><i class="fa fa-mail-forward"></i> Move</a>&nbsp;
				<a role="button" class="btn btn-danger delete-object" data-toggle="modal" data-object-name="<?php echo $content['Content']['name']; ?>" data-object-id="<?php echo $content['Content']['id']; ?>"><i class="fa fa-trash-o"></i> Delete</a>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php echo $this->element('modals/delete', array('title' => 'Delete Content', 'text' => 'delete the <strong>{name}</strong> content', 'action' => '/admin/content/delete/{id}')); ?>

<?php else: ?>
<p>There is no content in this section.</p>
<?php endif; ?>

<a role="button" href="/admin/content/create/<?php echo $parentId; ?>" class="btn btn-primary small"><i class="fa fa-plus"></i> Create New Page</a>