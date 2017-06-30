<h1>Manage Contact Types</h1>

<div class='row'>
    <div class='col-md-8'>
        <h2>Current Contact Types</h2>
        <table class='table table-striped table-hover'>
            <thead>
            <th>Name</th>
            <th>Created</th>
            
            <th>Options</th>
            </thead>
            <tbody>
        <?php foreach($certs as $cert): ?>
                <tr>
                    <td><?= $cert['ContactType']['name']; ?></td>
                    <td><?= date('m/d/Y H:i', strtotime($cert['ContactType']['created'])); ?></td>
                    
                    <td><a href='/admin/contactTypes/edit/<?= $cert['ContactType']['id']; ?>'><i class='fa fa-edit'></i> Edit</a>&nbsp; &nbsp; 
                        <a class='text-red delete-object' role='button' 
                            data-toggle="modal" data-object-name="<?php echo $cert['ContactType']['name']; ?>" 
                            data-object-id="<?php echo $cert['ContactType']['id']; ?>"><i class='fa fa-remove'></i> Remove</a></td>
                </tr>
        <?php endforeach; ?>
            </tbody>
            </table>
    </div>

    <div class='col-md-4'>

        <h2>Add Contact Type</h2>
<form action='/admin/contactTypes/add' method='POST'>
    <div class='row'>
    <div class='col-md-12'>
    <label>
        Name
    </label>
    <input class='input form-control' name='data[ContactType][name]' type='text' placeholder="Enter new name here..." />
    </div>
    </div>
    
    <div class='row'>
        <div class='col-md-12'>
             <input style='margin-top: 24px;' type='submit' class='btn btn-success' value='Add Contact Type' />
        </div>
    </div>
    
        
    
</form>
    </div>
</div>

<?php echo $this->element('modals/delete', array('title' => 'Delete Contact Type', 'text' => 'Are you sure you want to delete the contact type <strong>{name}</strong>? This action CANNOT be undone.', 'action' => '/admin/contactTypes/delete/{id}')); ?>