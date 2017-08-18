<?php $this->set('section', 'superApprove');  $this->set('title_for_layout', 'Manage Unapproved '
        . "Expenses - Super User"); ?>

<h1>Pending Expenses (Super User Approval)</h1>
<?php if (!empty($entries)): ?>
<?php echo $this->Form->create('superApprove'); ?>

<style>
    .bg-green {
        background-color: rgba(0,255,0,0.2) !important;
        color: #222 !important;
    }
    .bg-yellow {
        background-color: rgba(200,200,0,0.2) !important;
        color: #222 !important;
    }
</style>
<div style="display: none;">
    <small><em>
            Green: Waiting on <?= $secondary[1]; ?> --- Yellow: Waiting on <?= $primary[1]; ?></em></small>
</div>
<table class="table table-striped table-bordered table-hover approval-dataTable" id="users-table">
	<thead>
		<tr>
                    <th><?= $primary[1]; ?></th>
                    <th><?= $secondary[1]; ?></th>
                    <th>Vendor</th>
			<th>Date</th>
                        <th>Amount</th>
			<th>Organization</th>
			<th>Item</th>
			<th>Class</th>
                        <th>Description</th>
                        <th>CC?</th>
                        <th>Image</th>
                        <th>Billable Status</th>
			
		</tr>
	</thead>
	<tbody>
	<?php foreach ($entries as $time) { $entry = $time['BillItem'];  ?>
            
                <tr class='<?php if($entry['super_approved'] && $entry['drew_approved'] && $entry['mary_approved']) { ?> disabled <?php } ?> 
                    
                    '>
                   <?php if($super_user === 'primary'): ?>
        <td><input style="width: 15px; height: 15px;" name="data[entries][<?= $entry['id']; ?>][approved]" type="checkbox" <?php if($entry['drew_approved']){ echo "disabled";}  ?> <?php if($entry['drew_approved']) {echo "checked"; } ?> class="input form-control checkbox" /> </td>
        <td><input style="width: 15px; height: 15px;" name="ignore" type="checkbox" <?php echo "disabled"; ?> <?php if($entry['mary_approved']) {echo "checked"; } ?> class="input form-control checkbox" /> </td>
        <?php elseif($super_user === 'secondary'): ?>
        <td><input style="width: 15px; height: 15px;" name="ignore" type="checkbox" <?php { echo "disabled";}  ?> <?php if($entry['drew_approved']) {echo "checked"; } ?> class="input form-control checkbox" /> </td>
        <td><input style="width: 15px; height: 15px;" name="data[entries][<?= $entry['id']; ?>][approved]" type="checkbox" <?php if($entry['mary_approved']){ echo "disabled";}  ?> <?php if($entry['mary_approved']) {echo "checked"; } ?> class="input form-control checkbox" /> </td>
         <?php else: ?>
        <td><input style="width: 15px; height: 15px;" name="ignore" type="checkbox" <?php { echo "disabled";}  ?> <?php if($entry['drew_approved']) {echo "checked"; } ?> class="input form-control checkbox" /> </td>
        <td><input style="width: 15px; height: 15px;" name="ignore" type="checkbox" <?php echo "disabled"; ?> <?php if($entry['mary_approved']) {echo "checked"; } ?> class="input form-control checkbox" /> </td>
            <?php endif; ?>
                        <td><?= $time['Vendor']['first_name'] . " " . $time['Vendor']['last_name'];?></td>
			<td><?php echo date("m/d/y", strtotime($entry['txn_date'])); ?></td>
                        <td>$<?= $entry['amount']; ?></td>
			<td><?php echo $time['Customer']['full_name']; ?> <a 
                                data-vendor="<?=$time['Vendor']['id']; ?>" 
                                data-customer="<?= $time['Customer']['id']; ?>" 
                                data-dt="<?= date("Y-m-d",strtotime($entry['txn_date'])); ?>" 
                                title="View Full Bill For Organization" href="#" class="viewFullBill"><i class="fa fa-list-alt"></i></a></td>
                        <td><?php if(isset($time['Item']['full_name'])) { echo $time['Item']['full_name']; } ?></td>
			<td><?php echo $time['Classes']['full_name']; ?></td>
                        <td><?php echo $entry['description']; ?></td>
                        <td><?php if($entry['company_cc_item']): ?><i style='color: green;' class='fa fa-lg fa-check-circle'></i><?php endif; ?></td>
                        <td>
                            <?php if(!empty($entry['image'])) { ?>
                            <a class="btn btn-block btn-primary view-attachment" href="#"  data-srcid="<?= $entry['bill_item_image_id']; ?>" data-src="<?= $entry['image']; ?>">
                                <i class="fa fa-lg fa-eye"></i>
                            <?php } ?>
                            </a>
                        </td>
                        <td><select <?php echo $entry['super_approved'] ? "disabled" : ""; ?> class="input form-control" name='data[entries][<?= $entry['id']; ?>][billable]'><option <?= $entry['billable'] == 'Billable' ? 'selected="selected" ' : ''; ?> value='Billable'>Billable</option><option <?= $entry['billable'] == 'NotBillable' ? 'selected="selected" ' : ''; ?> value='NotBillable'>Not Billable</option><option value='HasBeenBilled'>Billed</option></select></td>
			
		</tr>
	<?php } ?>
	</tbody>
</table>
<input type="hidden" id="approveDeny" name="data[TimeEntry][approveDeny]" value="" />
<?php echo $this->form->end(); ?>
<div class="row">
    <h3>Options:</h3>
    <div class="col-md-2"><a class="btn btn-block btn-default" href="#" id="btn-check-uncheck"><i class="fa fa-check-square-o"></i> Select/Deselect All</a></div>
    <div class="col-md-2"><a class="btn btn-block btn-info" href="#" id="btn-approve"><i class="fa fa-check"></i> Approve Selected</a></div>
    <div class="col-md-2"><a class="btn btn-block btn-danger" href="#" id="btn-deny"><i class="fa fa-remove"></i> Deny Selected</a></div>
</div>
<?php echo $this->element('modals/delete', array('title' => 'Delete User', 'text' => 'delete the user record for <strong>{name}</strong>', 'action' => '/admin/users/delete/{id}')); ?>
<?= $this->element('modals/bill'); ?>

<?php else: ?>
<p>There are no pending time entries in your database.</p>
<?php endif; ?>

<?php $this->append('scripts'); ?>
<script>
    $(".viewFullBill").click(function(e) {
        e.preventDefault();
        $url = "/expenses/ajaxViewBill/" + $(this).data('vendor') + "/" + $(this).data('customer') + "/" + $(this).data('dt') + "/1";
        $.ajax($url).done(function(data) {
            $("#billViewBody").html(data);
            $("#billModal").modal("show");
        });
        
    });
    
    $("#approveBill").click(function() {
        $url = "/admin/expenses/superApproveMultiple/" + $("#approveMultiInfo > #vendor-id").text() + "/" +
                $("#approveMultiInfo > #customer-id").text() + "/" + $("#approveMultiInfo > #selected-date").text();
       
        window.location = $url;
    });
    
    $(".view-attachment").click(function(e){
        e.preventDefault();
        var val = $(this).data('src');
var file_type = val.substr(val.lastIndexOf('.')).toLowerCase();
if (file_type  === '.pdf') {
    var url = "/admin/expenses/ajaxPdfUploads/" + $(this).data("srcid");
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
    $content = "<img width='800px' src='/admin/expenses/ajaxImageUploads/" + $(this).data('srcid') + "' />";

        $.fancybox({
        
        autoScale: true,
        content: $content
    });
    }
    });
    
    
    
    </script>
    
    <?php $this->end(); ?>
<?php $this->append('scripts'); ?>
<script>
var checked = false;
$("#btn-check-uncheck").click(function(e) {
    e.preventDefault();
    if(checked)
    {
        $(".checkbox").each(function(){
           $(this).prop("checked", false); 
        });
        checked = false;
    }
    else
    {
        
        $(".checkbox").each(function(){
           $(this).prop("checked", true); 
        });
        checked = true;
    }
});

$("#btn-approve").click(function(e) {
    e.preventDefault();
    $("#approveDeny").val('approve');
    $("#superApproveAdminSuperApproveForm").submit();
});

$("#btn-deny").click(function(e) {
    e.preventDefault();
    $("#approveDeny").val('deny');
    $("#superApproveAdminSuperApproveForm").submit();
});
</script>

<?php $this->end(); ?>