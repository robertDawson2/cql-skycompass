<style>
    .contact-star {
        color: lightgray;
        margin-right: 10px;
        cursor: pointer;
        transition: 300ms;
    }
    
   .primary-contact, .primary-contact > .contact-star {
        color: gold;
    }
</style>
<div class="box box-success collapsed-box">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-users"></i>
<?php $count = 0;
if(!empty($contacts))
    $count = count($contacts);
?>
              <h3 class="box-title">Organization Contacts</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                  <span data-toggle="tooltip" title="" class="badge bg-green" data-original-title="<?= $count; ?> Contacts"><?= $count; ?></span>
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">
<?php if (!empty($contacts)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="contacts-table">
	<thead>
		<tr>
                    <th></th>
			<th>Name</th>
			<th>Email</th>
			<th>Phone(s)</th>
			
		</tr>
	</thead>
	<tbody>
	<?php foreach ($contacts as $cust) { ?>
		<tr>
                    <td><?php if($cust['id'] === $primary_contact_id) {
                        echo "<i class='fa fa-lg fa-star contact-star primary-contact'></i>";
                    } ?></td>
			<td><a role="button" class="btn btn-primary"
                               href="/admin/contacts/view/<?php echo $cust['id']; ?>">
                                <i class='fa fa-user'></i> <?php echo $cust['first_name']. " "; 
                       if(!empty($cust['middle_name']))
                           echo $cust['middle_name'] . " ";
                       echo $cust['last_name']; ?></a></td>
			
                        <td><?= ucfirst($cust['email']); ?></td>
			<td><?php if(!empty($cust['ContactPhone'])) {
                            foreach($cust['ContactPhone'] as $phone) { ?>
                            <em><?=ucwords($phone['type']); ?></em>: <?= $phone['phone']; ?>
                            <?php if(!empty($phone['ext']))
                                    echo " x " . $phone['ext']; ?>
                            <br />
                            <?php }  } ?>
                        </td>
                        
		
                </tr>
	<?php } ?>
	</tbody>
</table>

<?php else: ?>
<p>There are no contacts linked to this organization.</p>
<?php endif; ?>
    </div>
</div>