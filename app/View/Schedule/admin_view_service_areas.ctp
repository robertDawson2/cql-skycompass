<h1>Service Areas</h1>

<div class='row'>
    <div class='col-md-6'>
        <h2>Current Areas</h2>
        <?php foreach($serviceAreas as $a): ?>
        <h4><?= $a['ServiceArea']['name']; ?></h4>
        <div style='padding-left: 20px;'>
        <?php foreach($a['children'] as $c): ?>
        <p><?= $c['ServiceArea']['name']; ?> <a style='color: red;' onclick='return confirm("Are you sure you want to remove this service area?");' href='/admin/schedule/removeServiceArea/<?= $c['ServiceArea']['id']; ?>'><i class='fa fa-remove'></i></a></p>
        
        <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <div class='col-md-6'>

        <h2>Add New Area</h2>
<form action='/admin/schedule/addServiceArea' method='POST'>
    <div class='row'>
    <div class='col-md-4'>
    <label>
        Name
    </label>
    <input class='input form-control' name='data[ServiceArea][name]' type='text' placeholder="Enter new name here..." />
    </div>
        <div class='col-md-4'>
    <label>
        Parent
    </label>
            <select class='input form-control' name='data[ServiceArea][parent_id]'>
                <?php foreach($possibleParents as $i => $p): ?>
                <option value='<?= $i; ?>'><?= $p ?></option>
                <?php endforeach; ?>
            </select>
    
    </div>
        
        <div class='col-md-4'>
            
            <input style='margin-top: 24px;' type='submit' class='btn btn-success' value='Add Service Area' />
        </div>
    </div>
    
        
    
</form>
    </div>
</div>