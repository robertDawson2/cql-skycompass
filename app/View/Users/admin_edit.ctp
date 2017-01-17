<?php $this->set('title_for_layout', 'Edit User'); ?>
<?php $this->set('breadcrumbs', array('/admin/users' => 'Manage Users', '-' => 'Edit User')); ?>

<form method="post" action="/admin/users/edit" novalidate>

<ul class="nav nav-tabs">
	<li class="active"><a href="#user" data-toggle="tab">Details</a></li>
        <?php if(isset($currentUser['pmArray']['users']['setPermissions']) && $currentUser['pmArray']['users']['setPermissions']): ?>
	<li><a href="#web-permissions" data-toggle="tab">Web Content Permissions</a></li>
        <li><a href="#skycompass-permissions" data-toggle="tab">SkyCompass Permissions</a></li>
        <li><a href="#abilities" data-toggle="tab">Scheduling</a></li>
        <?php endif; ?>
</ul>

 
<div class="tab-content">
	<div class="tab-pane active" id="user">
		<div class="row form-group">	
			<?php echo $this->Form->input('User.first_name', array('div' => 'col-md-6', 'label' => 'First Name', 'autofocus', 'class' => 'input form-control')); ?>
			<?php echo $this->Form->input('User.last_name', array('div' => 'col-md-6', 'label' => 'Last Name', 'class' => 'input form-control')); ?>
		</div>
		<div class="row form-group">	
			<?php echo $this->Form->input('User.email', array('div' => 'col-md-12', 'label' => 'Email Address', 'class' => 'input form-control')); ?>
		</div>
            <?php if(isset($currentUser['pmArray']['users']['resetPassword']) && $currentUser['pmArray']['users']['resetPassword']): ?>
		<div class="row form-group">	
			<?php echo $this->Form->input('User.new_password', array('type' => 'password', 'div' => 'col-md-6', 'label' => 'Password (leave blank for no change)', 'class' => 'input form-control')); ?>
			<?php echo $this->Form->input('User.password_confirmation', array('type' => 'password', 'div' => 'col-md-6', 'label' => 'Password (Repeat)', 'class' => 'input form-control')); ?>
		</div>
            <?php endif; ?>
	</div>
	
	<?php if(isset($currentUser['pmArray']['users']['setPermissions']) && $currentUser['pmArray']['users']['setPermissions']): ?>
	<div class="tab-pane" id="web-permissions">
		<h4>Web Permissions</h4>
		<div class="row form-group">
			<div class="col-md-12">
				<?php echo $this->Form->input('User.permissions.site.content', array('type' => 'checkbox', 'label' => 'Content <small>(user can edit all content, regardless of permissions below)</small>')); ?>
				<?php echo $this->Form->input('User.permissions.site.home_page_features', array('type' => 'checkbox', 'label' => 'Home Page Features')); ?>
				<?php echo $this->Form->input('User.permissions.site.events', array('type' => 'checkbox', 'label' => 'Events')); ?>
				<?php echo $this->Form->input('User.permissions.site.news', array('type' => 'checkbox', 'label' => 'News')); ?>
				<?php echo $this->Form->input('User.permissions.site.galleries', array('type' => 'checkbox', 'label' => 'Galleries')); ?>
				<?php echo $this->Form->input('User.permissions.site.users', array('type' => 'checkbox', 'label' => 'Users <small>(user can create and manage other users &ndash; including you!)</small>')); ?>
			</div>
		</div>
		
		<h4>Content Permissions</h4>
		<p style="margin-bottom: 0;">Selecting a page will automatically allow access to any children of that page.</p>
		<div class="row form-group">
			<div class="col-md-12">
				<?php
					foreach ($content as $id => $name) {
						$name = explode('|', $name);
						echo $this->Form->input('User.permissions.content.' . $id, array('type' => 'checkbox', 'div' => array('class' => 'input checkbox', 'style' => 'margin-left: ' . ($name[0] * 20) . 'px;'), 'label' => $name[1]));
					}
				?>
			</div>
		</div>
	</div>

    <div class="tab-pane" id="skycompass-permissions">
		<h4>SkyCompass Permissions</h4>
		<div class="row form-group">
                    
			<div class="col-md-6 panel panel-info">
                            <div class="panel-heading">Customers</div>
                            <div class="panel-body">
				<?php echo $this->Form->input('User.permissions.customers.admin_index', array('type' => 'checkbox', 'label' => 'View All')); ?>
				<?php echo $this->Form->input('User.permissions.customers.admin_edit', array('type' => 'checkbox', 'label' => 'Edit Info')); ?>
				<?php echo $this->Form->input('User.permissions.jobs.admin_viewAll', array('type' => 'checkbox', 'label' => 'View Jobs')); ?>
				<?php echo $this->Form->input('User.permissions.customers.admin_add', array('type' => 'checkbox', 'label' => 'Create New')); ?>
				<?php echo $this->Form->input('User.permissions.customers.admin_delete', array('type' => 'checkbox', 'label' => 'Delete')); ?>
                            </div>
			</div>
		
			<div class="col-md-6 panel panel-info">
                            <div class="panel-heading">Jobs</div>
                            <div class="panel-body">
				<?php echo $this->Form->input('User.permissions.jobs.admin_index', array('type' => 'checkbox', 'label' => 'View All')); ?>
				<?php echo $this->Form->input('User.permissions.jobs.admin_edit', array('type' => 'checkbox', 'label' => 'Edit Info')); ?>
				<?php echo $this->Form->input('User.permissions.jobs.admin_view', array('type' => 'checkbox', 'label' => 'View Specific')); ?>
				<?php echo $this->Form->input('User.permissions.jobs.admin_add', array('type' => 'checkbox', 'label' => 'Create New')); ?>
				<?php echo $this->Form->input('User.permissions.jobs.admin_delete', array('type' => 'checkbox', 'label' => 'Delete')); ?>
				</div>
			</div>
                        </div>
                    <div class="row form-group">
                    <div class="col-md-6 panel panel-info">
                             <div class="panel-heading">Employees</div>
                             <div class="panel-body">
				<?php echo $this->Form->input('User.permissions.users.admin_index', array('type' => 'checkbox', 'label' => 'View All')); ?>
				<?php echo $this->Form->input('User.permissions.users.admin_edit', array('type' => 'checkbox', 'label' => 'Edit General Info')); ?>
				<?php echo $this->Form->input('User.permissions.users.admin_timeEntries', array('type' => 'checkbox', 'label' => 'View Employee Time Entries')); ?>
				<?php echo $this->Form->input('User.permissions.users.admin_add', array('type' => 'checkbox', 'label' => 'Create New')); ?>
				<?php echo $this->Form->input('User.permissions.users.admin_delete', array('type' => 'checkbox', 'label' => 'Delete')); ?>
				<?php echo $this->Form->input('User.permissions.timeEntries.admin_approve', array('type' => 'checkbox', 'label' => 'Approve Time Entries')); ?>
				<?php echo $this->Form->input('User.permissions.bills.admin_approve', array('type' => 'checkbox', 'label' => 'Approve Expenses')); ?>
				
				<?php echo $this->Form->input('User.permissions.users.admin_delete', array('type' => 'checkbox', 'label' => 'Delete')); ?>
				</div>
			</div>
                         <div class="col-md-6 panel panel-info">
                            <div class="panel-heading">Scheduling</div>
                            <div class="panel-body">
				<?php echo $this->Form->input('User.permissions.jobs.admin_scheduler', array('type' => 'checkbox', 'label' => 'View Scheduler Pagex')); ?>
				<?php echo $this->Form->input('User.permissions.schedule.admin_alertAllUsers', array('type' => 'checkbox', 'label' => 'Send Schedule Mass Email')); ?>
				<?php echo $this->Form->input('User.permissions.schedule.admin_approveTimeOff', array('type' => 'checkbox', 'label' => 'Approve/Deny Time Off Requests')); ?>
				<?php echo $this->Form->input('User.permissions.taskListTemplates.admin_index', array('type' => 'checkbox', 'label' => 'Edit Task List Templates')); ?>
				<?php echo $this->Form->input('User.permissions.taskListTemplates.admin_create', array('type' => 'checkbox', 'label' => 'Create New Task List Templates')); ?>
                                <?php echo $this->Form->input('User.permissions.schedule.admin_viewServiceAreas', array('type' => 'checkbox', 'label' => 'Manage Service Areas')); ?>
				</div>
			</div>
                    <div class="col-md-6 panel panel-info">
                            <div class="panel-heading">Other</div>
                            <div class="panel-body">
				<?php echo $this->Form->input('User.permissions.timeEntries.admin_index', array('type' => 'checkbox', 'label' => 'View All Time Entries')); ?>
				<?php echo $this->Form->input('User.permissions.bills.admin_index', array('type' => 'checkbox', 'label' => 'View All Expenses')); ?>
				<?php echo $this->Form->input('User.permissions.users.resetPassword', array('type' => 'checkbox', 'label' => 'Reset Employee Passwords')); ?>
				<?php echo $this->Form->input('User.permissions.users.setPermissions', array('type' => 'checkbox', 'label' => 'Set User Permissions/Types')); ?>
				<?php echo $this->Form->input('User.permissions.config.admin_index', array('type' => 'checkbox', 'label' => 'Change Site Configuration Settings')); ?>
				</div>
			</div>
                    
		</div>
                
                <h4>Approval Managers</h4>
		<div class="row form-group">
                    <select id="admin-selector">
                        <?php foreach($admins as $i => $admin): ?>
                        
                        <option data-id="<?= $admin['User']['id']; ?>"><?= $admin['User']['first_name'] . " " . $admin['User']['last_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <a id="admin-selector-add" href="#" class="btn btn-primary">
                        Add
                    </a>
                    
                </div>
                <div class="row form-group">
                    <h5>Current Approval Managers:</h5>
                    <div id="current-approval-managers">
                    <?php foreach($currentAdmins as $cadmin): ?>
                    <div><?= $cadmin['User']['first_name'] . " " . $cadmin['User']['last_name']; ?> <a class="approval-manager-remove" href="#" data-id="<?= $cadmin['User']['id']; ?>" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></a>
                    </div>
                    <?php endforeach; ?>
                    </div>
                </div>
    </div>
    
    <div class="tab-pane" id="abilities">
        <h4>Abilities</h4>
        <div class="row">
            <div class="col-md-6">
		<div class="row form-group">
                    <div class="col-md-6">
                    <select id="ability-selector">
                        <?php foreach($abilities as $i => $ability): ?>
                        
                        <option data-id="<?= $i; ?>"><?= $ability; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <a id="ability-add" href="#" data-id="<?= $user['User']['id']; ?>" class="btn btn-primary">
                        Add
                    </a>
                    
                </div>
		<div class="col-md-6">	
			<h5>Current Abilities:</h5>
                    <div id="current-abilities">
                    <?php foreach($user['Ability'] as $ability): ?>
                    <div><?= $ability['name']; ?> <a class="ability-remove" href="#" data-id="<?= $ability['id']; ?>"  data-userid="<?= $user['User']['id']; ?>" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></a>
                    </div>
                    <?php endforeach; ?>
                    </div>
		</div>
            </div>
            </div>
            <div class="col-md-6">
                <?= $this->Form->input('User.scheduling_admin_notes', array('div'=>'col-md-12', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('User.scheduling_employee_notes', array('div'=>'col-md-12', 'class'=>'input form-control')); ?>
            </div>
        </div>
            
	</div>
</div>
<?php endif; ?>
<?php echo $this->Form->hidden('User.id'); ?>
    
<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save Changes</button>
</form>

<?php $this->append('scripts'); ?>
<script>
    
$("#admin-selector-add").click(function(e) {
    e.preventDefault();
    $url = '/admin/users/ajaxAddManager/<?= $this->data['User']['id']; ?>/' + $( "#admin-selector option:selected" ).data('id');

    $.ajax(
            {
                url: $url
            }
                )
                .done(function(result) {
                 if(result === 'ok')
         {
             $addition = '<div>' + $( "#admin-selector option:selected" ).val() + 
                     '<a class="approval-manager-remove" href="#" data-id="' + 
                     $( "#admin-selector option:selected" ).data('id') +
                    '" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></a></div>';
             $( "#admin-selector option:selected" ).remove();
             $("#current-approval-managers").append($addition);
             
             
        }
                
            });
});

$(".approval-manager-remove").click(function(e) {
    e.preventDefault();
    $url = '/admin/users/ajaxRemoveManager/<?= $this->data['User']['id']; ?>/' + $(this).data('id');
    $id = $(this).data('id');
    $clicked = $(this);

    $.ajax(
            {
                url: $url
            }
                )
                .done(function(result) {
                 if(result === 'ok')
         {
             $addition = '<option data-id="' + $id + '">' + $clicked.parent().text() +
                     '</option>';
             $clicked.parent().remove();
             $("#admin-selector").append($addition);
             
             
        }
                
            });
});

$("#ability-add").click(function(e) {
    e.preventDefault();
   
    $url = '/admin/users/ajaxAddAbility/<?= $this->data['User']['id']; ?>/' + $( "#ability-selector option:selected" ).data('id');

    $.ajax(
            {
                url: $url
            }
                )
                .done(function(result) {
                 if(result === 'ok')
         {
             $addition = '<div>' + $( "#ability-selector option:selected" ).val() + 
                     '<a class="ability-remove" href="#" data-id="' + 
                     $( "#ability-selector option:selected" ).data('id') +
                    '" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></a></div>';
             $( "#ability-selector option:selected" ).remove();
             $("#current-abilities").append($addition);
             
             
        }
                
            });
});

$(".ability-remove").click(function(e) {
    e.preventDefault();
    $url = '/admin/users/ajaxRemoveAbility/<?= $this->data['User']['id']; ?>/' + $(this).data('id');
    $id = $(this).data('id');
    $clicked = $(this);

    $.ajax(
            {
                url: $url
            }
                )
                .done(function(result) {
                 if(result === 'ok')
         {
             $addition = '<option data-id="' + $id + '">' + $clicked.parent().text() +
                     '</option>';
             $clicked.parent().remove();
             $("#ability-selector").append($addition);
             
             
        }
                
            });
});

</script>

<?php $this->end(); ?>