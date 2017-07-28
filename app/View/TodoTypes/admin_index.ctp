<h1>Manage Todo Types</h1>

<div class='row'>
    <div class='col-md-8'>
        <h2>Current Todo Types</h2>
        <table class='table table-striped table-hover'>
            <thead>
            <th>Name</th>
            <th>Icon</th>
            <th>Created</th>
            
            <th>Options</th>
            </thead>
            <tbody>
        <?php foreach($certs as $cert): ?>
                <tr>
                    <td><?= $cert['TodoType']['name']; ?></td>
                    <td><i class='fa fa-2x <?= $cert['TodoType']['fa_icon']; ?>'></i></td>
                    <td><?= date('m/d/Y H:i', strtotime($cert['TodoType']['created'])); ?></td>
                    
                    <td><a href='/admin/todoTypes/edit/<?= $cert['TodoType']['id']; ?>'><i class='fa fa-edit'></i> Edit</a>&nbsp; &nbsp; 
                        <a class='text-red delete-object' role='button' 
                            data-toggle="modal" data-object-name="<?php echo $cert['TodoType']['name']; ?>" 
                            data-object-id="<?php echo $cert['TodoType']['id']; ?>"><i class='fa fa-remove'></i> Remove</a></td>
                </tr>
        <?php endforeach; ?>
            </tbody>
            </table>
    </div>

    <div class='col-md-4'>

        <h2>Add Todo Type</h2>
<form action='/admin/todoTypes/add' method='POST'>
    <div class='row'>
    <div class='col-md-6'>
    <label>
        Name
    </label>
    <input class='input form-control' name='data[TodoType][name]' type='text' placeholder="Enter new name here..." />
    </div>
<div class='col-md-6'>
    <label>
        Icon <small><a href="http://fontawesome.io/cheatsheet/" target="_BLANK">View icon options...</a></small>
    </label>
    <input class='input form-control' name='data[TodoType][fa_icon]' type='text' placeholder="Enter icon code from website..." />
    </div>
    </div>
    
    <div class='row'>
        <div class='col-md-12'>
             <input style='margin-top: 24px;' type='submit' class='btn btn-success' value='Add Todo Type' />
        </div>
    </div>
    
        
    
</form>
    </div>
</div>

<?php echo $this->element('modals/delete', array('title' => 'Delete Todo Type', 'text' => 'Are you sure you want to delete the todo type <strong>{name}</strong>? This action CANNOT be undone.', 'action' => '/admin/todoTypes/delete/{id}')); ?>