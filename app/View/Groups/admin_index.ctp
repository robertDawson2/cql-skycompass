<h1>Manage Groups</h1>

<div class='row'>
    <div class='col-md-8'>
        <h2>Current Groups</h2>
        <table class='table table-striped table-hover'>
            <thead>
            <th>Name</th>
            <th>Contacts?</th>
            <th>Organizations?</th>
            <th>Options</th>
            </thead>
            <tbody>
        <?php foreach($allgroups as $g): ?>
                <tr>
                    <td><?= $g['Group']['name']; ?></td>
                    <td><?= $g['Group']['is_contact'] ? "<i class='fa fa-check-circle'></i>" : ""; ?></td>
                    <td><?= $g['Group']['is_customer'] ? "<i class='fa fa-check-circle'></i>" : ""; ?></td>
                    <td><a href='/admin/groups/edit/<?= $g['Group']['id']; ?>'><i class='fa fa-edit'></i> Edit</a>&nbsp; &nbsp; 
                        <a class='text-red delete-object' role='button' 
                            data-toggle="modal" data-object-name="<?php echo $g['Group']['name']; ?>" 
                            data-object-id="<?php echo $g['Group']['id']; ?>"><i class='fa fa-remove'></i> Remove</a></td>
                </tr>
        <?php endforeach; ?>
            </tbody>
            </table>
    </div>

    <div class='col-md-4'>

        <h2>Add New Group</h2>
<form action='/admin/groups/add' method='POST'>
    <div class='row'>
    <div class='col-md-12'>
    <label>
        Name
    </label>
    <input class='input form-control' name='data[Group][name]' type='text' placeholder="Enter new name here..." />
    </div>
    </div>
    <div class='row'>
        <div class='col-md-6'>
    <?= $this->Form->input('Group.is_contact', array('type'=>'checkbox')); ?>
    
    </div>
        
        <div class='col-md-6'>
            <?= $this->Form->input('Group.is_customer', array('type' => 'checkbox')); ?>
           
        </div>
    </div>
    <div class='row'>
        <div class='col-md-12'>
             <input style='margin-top: 24px;' type='submit' class='btn btn-success' value='Add Group' />
        </div>
    </div>
    
        
    
</form>
    </div>
</div>

<?php echo $this->element('modals/delete', array('title' => 'Delete Group', 'text' => 'Are you sure you want to delete the group <strong>{name}</strong>? This action CANNOT be undone.', 'action' => '/admin/groups/delete/{id}')); ?>