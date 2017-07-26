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
if(!empty($attendees))
    $count = count($attendees);
?>
              <h3 class="box-title">Event Attendees</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                  <span data-toggle="tooltip" title="" class="badge bg-green" data-original-title="<?= $count; ?> Contacts"><?= $count; ?></span>
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">
<?php if (!empty($attendees)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="contacts-table">
	<thead>
		<tr>
                    
			<th>Name</th>
			<th>Email</th>
			<th>Organization</th>
			
		</tr>
	</thead>
	<tbody>
	<?php foreach ($attendees as $cust) { ?>
		<tr>
                    
			<td><a role="button" class="btn btn-primary"
                               href="/admin/contacts/view/<?php echo $cust['Contact']['id']; ?>">
                                <i class='fa fa-user'></i> <?php echo $cust['Contact']['first_name']. " "; 
                       if(!empty($cust['Contact']['middle_name']))
                           echo $cust['Contact']['middle_name'] . " ";
                       echo $cust['Contact']['last_name']; ?></a></td>
			
                        <td><?= $cust['Contact']['email']; ?></td>
			<td><?php if(!empty($cust['Contact']['Customer'])) {
                           
                            foreach($cust['Contact']['Customer'] as $phone) { ?>
                            <a href='/admin/customers/view/<?= $phone['id']; ?>'><?= $phone['name']; ?></a>
                            <br />
                            <?php }  } ?>
                        </td>
                        
		
                </tr>
	<?php } ?>
	</tbody>
</table>

<?php else: ?>
<p>There are no attendees linked to this event.</p>
<?php endif; ?>
    </div>
</div>