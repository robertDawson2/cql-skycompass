<h1>User Abilities</h1>

<div class='row'>
    <div class='col-md-6'>
        <h2>Current Abilities</h2>
        
        <?php foreach($serviceAreas as $c): ?>
        <p><?= $c['Ability']['name']; ?> <a style='color: red;' onclick='return confirm("Are you sure you want to remove this ability?");' href='/admin/schedule/removeAbility/<?= $c['Ability']['id']; ?>'><i class='fa fa-remove'></i></a></p>
        
        <?php endforeach; ?>
        </div>
       


    <div class='col-md-6'>

        <h2>Add New Ability</h2>
<form action='/admin/schedule/addAbility' method='POST'>
    <div class='row'>
    <div class='col-md-8'>
    <label>
        Name
    </label>
    <input class='input form-control' name='data[Ability][name]' type='text' placeholder="Enter new name here..." />
    </div>
        
        
        <div class='col-md-4'>
            
            <input style='margin-top: 24px;' type='submit' class='btn btn-success' value='Add Ability' />
        </div>
    </div>
    
        
    
</form>
    </div>
</div>