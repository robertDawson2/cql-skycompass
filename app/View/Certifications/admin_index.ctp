<h1>Manage Certifications</h1>

<div class='row'>
    <div class='col-md-8'>
        <h2>Current Certifications</h2>
        <table class='table table-striped table-hover'>
            <thead>
            <th>Name</th>
            <th>Created</th>
            
            <th>Options</th>
            </thead>
            <tbody>
        <?php foreach($certs as $cert): ?>
                <tr>
                    <td><?= $cert['Certification']['name']; ?></td>
                    <td><?= date('m/d/Y H:i', strtotime($cert['Certification']['created'])); ?></td>
                    
                    <td><a href='/admin/certifications/edit/<?= $cert['Certification']['id']; ?>'><i class='fa fa-edit'></i> Edit</a>&nbsp; &nbsp; 
                        <a class='text-red delete-object' role='button' 
                            data-toggle="modal" data-object-name="<?php echo $cert['Certification']['name']; ?>" 
                            data-object-id="<?php echo $cert['Certification']['id']; ?>"><i class='fa fa-remove'></i> Remove</a></td>
                </tr>
        <?php endforeach; ?>
            </tbody>
            </table>
    </div>

    <div class='col-md-4'>

        <h2>Add Certification</h2>
<form action='/admin/certifications/add' method='POST'>
    <div class='row'>
    <div class='col-md-12'>
    <label>
        Name
    </label>
    <input class='input form-control' name='data[Certification][name]' type='text' placeholder="Enter new name here..." />
    </div>
    </div>
    
    <div class='row'>
        <div class='col-md-12'>
             <input style='margin-top: 24px;' type='submit' class='btn btn-success' value='Add Certification' />
        </div>
    </div>
    
        
    
</form>
    </div>
</div>

<?php echo $this->element('modals/delete', array('title' => 'Delete Certification', 'text' => 'Are you sure you want to delete the certification <strong>{name}</strong>? This action CANNOT be undone.', 'action' => '/admin/certifications/delete/{id}')); ?>