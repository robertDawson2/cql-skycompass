           <?php if($context === 'reminders') { ?>
<h4>Please attend to the following reminders</h4>
           <?php } else { ?>
<h4 style="color: red;">The following are past their due date!</h4>
           <?php } ?>
<hr>
<table class='table table-condensed table-striped table-hover'>
    <thead>
        <tr>
    <th>
        Priority
    </th>
    <th>
        Due
    </th>
    <th>
        Reminder
    </th>
    <th>
        Description
    </th>
    <th>
        Type
    </th>
    <th>
        Linked Record
    </th>
        <tr>
    </thead>
    <tbody>
        <?php foreach($expired as $record): ?>
        <tr onclick="location.href = '/admin/todo';">
            <td>
                <?php 
        $color = null;
        switch($record['Todo']['priority'])
        {
           case "1": 
               $color = 'red';
               break;
           case "2":
               $color = 'orange';
               break;
           case "3":
               $color = 'yellow';
               break;
           case "4":
               $color = 'green';
               break;
           case "5":
               $color = 'gray';
               break;
           default:
               $color = 'black';
               break;
           
        }
        
        ?>
        <i class='fa fa-2x <?= $context === "reminders" ? 'fa-hourglass-half' : 'fa-warning'; ?>' style='color: <?= $color; ?>;' title='Priority <?= $record['Todo']['priority']; ?>'></i>
            </td>
            <td >
                <?= $record['Todo']['due_date'] === null ? "" : date('m/d/Y h:i A', strtotime($record['Todo']['due_date'])); ?>
            </td>
            <td>
                <?= $record['Todo']['reminder_date'] === null ? "" : date('m/d/Y h:i A', strtotime($record['Todo']['reminder_date'])); ?>
            </td>
            <td>
                <?= $record['Todo']['description']; ?>
            </td>
            <td>
                <i class='fa <?= $record['TodoType']['fa_icon']; ?>'></i> <?= $record['TodoType']['name']; ?>
            </td>
            <td>
                <?php if($record['Todo']['contact_id'] !== null): ?>
                <strong>Contact: </strong><br>
                <em><?= $record['Contact']['first_name'] . " " . $record['Contact']['last_name']; ?></em><br>
                <a href='/admin/contacts/view/<?= $record['Contact']['id']; ?>' class='btn btn-xs btn-info' role='button'>
                    <i class='fa fa-lg fa-eye'></i> View</a>
                <?php endif; ?>
                <?php if($record['Todo']['customer_id'] !== null): ?>
                <strong>Organization: </strong><br>
                <em><?= $record['Customer']['name']; ?></em><br />
                <a href='/admin/customers/view/<?= $record['Customer']['id']; ?>' class='btn btn-xs btn-success' role='button'>
                    <i class='fa fa-lg fa-eye'></i> View</a>
                <?php endif; ?>
            </td>
        </tr>
        
        <?php endforeach; ?>
    </tbody>
</table>