<?php $this->set('title_for_layout', 'Manage News'); ?>

<?php $this->start('jquery-scripts'); ?>
	$('#news-table').dataTable({	
		"sPaginationType": "bootstrap"
	});
<?php $this->end(); ?>

<?php if (!empty($news)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="news-table">
	<thead>
		<tr>
			<th>Headline</th>
			<th>Begin Showing</th>
			<th>Stop Showing</th>
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($news as $news) { ?>
		<tr>
			<td><?php echo $news['News']['headline']; ?></td>
			<td><?php echo $news['News']['begin_showing'] == '0000-00-00 00:00:00' ? 'N/A' : date('n/j/Y g:i A', strtotime($news['News']['begin_showing'])); ?></td>
			<td><?php echo $news['News']['stop_showing'] == '0000-00-00 00:00:00' ? 'N/A' : date('n/j/Y g:i A', strtotime($news['News']['stop_showing'])); ?></td>
			<td>
				<a role="button" class="btn btn-primary" href="/admin/news/edit/<?php echo $news['News']['id']; ?>"><i class="fa fa-edit"></i> Edit</a>&nbsp;
				<a role="button" class="btn btn-danger delete-object" data-toggle="modal" data-object-name="<?php echo $news['News']['headline']; ?>" data-object-id="<?php echo $news['News']['id']; ?>"><i class="fa fa-trash-o"></i> Delete</a>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php echo $this->element('modals/delete', array('title' => 'Delete News', 'text' => 'delete the <strong>{name}</strong> news item', 'action' => '/admin/news/delete/{id}')); ?>
<?php else: ?>
<p>There are no news items in the database.</p>
<?php endif; ?>

<a role="button" href="/admin/news/create" class="btn btn-primary small"><i class="fa fa-plus"></i> Create New News Item</a>