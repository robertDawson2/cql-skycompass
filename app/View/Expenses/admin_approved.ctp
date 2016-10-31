<?php $this->set('title_for_layout', 'Imported '
        . "Expenses"); ?>

<h1>Imported Expense Entries</h1>
<?php if (!empty($expenses)): ?>
<table class="table table-striped table-responsive table-bordered table-hover dataTable" id="users-table">
	<thead>
		<tr>
                    
			<th>Date</th>
                        <th>Employee</th>
                        <th>Amount</th>
			<th>Customer/Job</th>
			<th>Item</th>
			<th style='max-width: 100px;'>Class</th>
                        <th>Description</th>
                        
                        <th>Options</th>
			
		</tr>
	</thead>
	<tbody>
	<?php foreach ($expenses as $time) {  $entry = $time['BillItem'];?>
                <tr <?php if($entry['approved']) { ?> class='disabled' <?php } ?>>
                        
			<td><?php 
                        if($entry['txn_date'] !== "0000-00-00 00:00:00")
                            echo date("m/d/Y", strtotime($entry['txn_date'])); 
                        else
                            echo date("m/d/Y", strtotime($time['Bill']['txn_date']));
                        ?></td>
                        <td>
                            <?= $time['Vendor']['first_name'] . " " . $time['Vendor']['last_name']; ?>
                        </td>
                        <td>$<?= $entry['amount']; ?></td>
			<td><?php echo $time['Customer']['full_name']; ?></td>
                        <td><?php if(isset($time['Item']['full_name'])) { echo $time['Item']['full_name']; } ?></td>
			<td><?php echo $time['Classes']['full_name']; ?></td>
                        <td><?php echo $entry['description']; ?></td>
                        
                       
                        <td>
                            <?php if(!empty($entry['image'])) { ?>
                            <a class="btn btn-primary view-attachment" data-src="<?= $entry['image']; ?>" href="#"><i class="fa fa-lg fa-search"></i> View Receipt</a>
                            <?php }  ?>
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
//    $(".view-attachment").click(function(e){
//        e.preventDefault();
//        $.fancybox({
//        
//        autoScale: true,
//        content: "<img width='800px'  src='/files/uploads/" + $(this).data('src') + "' />"
//    });
//    
//    });
    
     $(".view-attachment").click(function(e){
        e.preventDefault();
        var val = $(this).data('src');
var file_type = val.substr(val.lastIndexOf('.')).toLowerCase();
if (file_type  === '.pdf') {
    var url = "/files/uploads/" + $(this).data("src");
    $.fancybox({
    autoScale: false,
    // href : $('.fancybox').attr('id'), // don't need this
    type: 'iframe',
    padding: 0,
    closeClick: false,
    // other options
    beforeLoad: function () {
        
      
        this.href = url;
    }
}); // fancybox
        }
        else
        {
    $content = "<img width='800px' src='/files/uploads/" + $(this).data('src') + "' />";

        $.fancybox({
        
        autoScale: true,
        content: $content
    });
    }
    });
    
    </script>
    
    <?php $this->end(); ?>