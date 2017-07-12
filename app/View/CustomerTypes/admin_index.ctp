<h1>Manage Organization Types</h1>

<div class='row'>
    <div class='col-md-8'>
        <h2>Current Organization Types</h2>
        <table class='table table-striped table-hover'>
            <thead>
            <th>Name</th>
            <th>Created</th>
            
            <th>Options</th>
            </thead>
            <tbody>
        <?php foreach($certs as $cert): ?>
                <tr>
                    <td><?= $cert['CustomerType']['name']; ?></td>
                    <td><?= date('m/d/Y H:i', strtotime($cert['CustomerType']['created'])); ?></td>
                    
                    <td><a href='/admin/customerTypes/edit/<?= $cert['CustomerType']['id']; ?>'><i class='fa fa-edit'></i> Edit</a>&nbsp; &nbsp; 
                        <a class='text-red delete-object' role='button' 
                            data-toggle="modal" data-object-name="<?php echo $cert['CustomerType']['name']; ?>" 
                            data-object-id="<?php echo $cert['CustomerType']['id']; ?>"><i class='fa fa-remove'></i> Remove</a></td>
                </tr>
        <?php endforeach; ?>
            </tbody>
            </table>
    </div>

    <div class='col-md-4'>

        <h2>Add Organization Type</h2>
<form action='/admin/customerTypes/add' method='POST'>
    <div class='row'>
    <div class='col-md-12'>
    <label>
        Name
    </label>
    <input class='input form-control' name='data[CustomerType][name]' type='text' placeholder="Enter new name here..." />
    </div>
    </div>
    
    <div class='row'>
        <div class='col-md-12'>
             <input style='margin-top: 24px;' type='submit' class='btn btn-success' value='Add Organization Type' />
        </div>
    </div>
    
        
    
</form>
    </div>
</div>

<?php echo $this->element('modals/delete', array('title' => 'Delete Organization Type', 'text' => 'Are you sure you want to delete the customer type <strong>{name}</strong>? This action CANNOT be undone.', 'action' => '/admin/customerTypes/delete/{id}')); ?>