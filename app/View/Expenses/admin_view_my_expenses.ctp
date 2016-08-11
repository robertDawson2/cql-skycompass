<?php $this->set('title_for_layout', 'Manage My '
        . "Expenses"); ?>

<h1>Expense Entries for <small><em><?= $user['first_name'] . " " . $user['last_name']; ?></em></small></h1>
<?php if (!empty($expenses)): ?>
<table class="table table-striped table-responsive table-bordered table-hover dataTable" id="users-table">
	<thead>
		<tr>
                    
			<th>Date</th>
                        <th>Amount</th>
			<th>Customer/Job</th>
			<th>Item</th>
			<th style='max-width: 100px;'>Class</th>
                        <th>Description</th>
                        <th>Approved?</th>
                        <th>Options</th>
			
		</tr>
	</thead>
	<tbody>
	<?php foreach ($expenses as $time) {  $entry = $time['BillItem'];?>
                <tr <?php if($entry['approved']) { ?> class='disabled' <?php } ?>>
                        
			<td><?php echo date("m/d/Y", strtotime($entry['txn_date'])); ?></td>
                        <td>$<?= $entry['amount']; ?></td>
			<td><?php echo $time['Customer']['full_name']; ?></td>
                        <td><?php if(isset($time['Item']['full_name'])) { echo $time['Item']['full_name']; } ?></td>
			<td><?php echo $time['Classes']['full_name']; ?></td>
                        <td><?php echo $entry['description']; ?></td>
                        <td><?php if($entry['approved']===1 || $entry['approved'] === '1') 
                                    echo "<i class='green fa fa-lg fa-check-circle-o'></i> Approved";
                                  elseif($entry['approved']===0 || $entry['approved'] === '0')
                                     echo "<i class='red fa fa-lg fa-warning'></i> Denied";
                                  else
                                      echo "";
                                      ?></td>
                       
                        <td>
                            <?php if(!empty($entry['image'])) { ?>
                            <a class="btn btn-primary view-attachment" data-src="<?= $entry['image']; ?>" href="#"><i class="fa fa-lg fa-search"></i> View Receipt</a>
                            <?php } if(!$entry['approved']): ?>
                            
                            <a role="button" class="btn btn-success" href="/admin/expenses/edit/<?= $entry['id']; ?>"><i class='fa fa-edit'></i> Edit</a>
                            <a role="button" href="/admin/expenses/delete/<?= $entry['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you would like to delete this expense?');"><i class="fa fa-trash-o"></i> Delete</a>&nbsp;
                            
                            <?php endif; ?>
                        </td>
                        
		</tr>
	<?php } ?>
	</tbody>
</table>


<?php else: ?>
<p>There are no expense for this user in your database.</p>
<?php endif; ?>

<?php $this->append('scripts'); ?>
<script>
    $(".view-attachment").click(function(e){
        e.preventDefault();
        $.fancybox({
        
        autoSize: true,
        content: "<img src='/files/uploads/" + $(this).data('src') + "' />"
    });
    
    });
    
    
    </script>
    
    <?php $this->end(); ?>