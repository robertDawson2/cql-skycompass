<div class='row'>
    <div class='col-md-2'>
        <?php 
        $color = null;
        switch($item['Todo']['priority'])
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
        <i class='fa fa-2x fa-warning' style='color: <?= $color; ?>;' title='Priority <?= $item['Todo']['priority']; ?>'></i>
    </div>
    <div class='col-md-10'>
                <strong><em>
                        <div style='color: #484848; font-size: 120%;' id='todoFullDetails-description'><?= $item['Todo']['description']; ?></div>
                    </em></strong>
    </div>
            </div>
            <div style='margin-top: 15px;' class='row'>
                <div class='col-md-4'>
                    <strong>Category: </strong><div id='todoFullDetails-category'>
                        <i class='fa <?= $item['TodoType']['fa_icon']; ?>'></i> 
                        <?= $item['TodoType']['name']; ?></div>
                </div>
                
                <div class='col-md-4'>
                    <?php if($item['Todo']['due_date'] !== null): ?>
                    <strong>Due Date: </strong><div id='todoFullDetails-due'><?= date('m/d/Y \a\t h:i A', strtotime($item['Todo']['due_date'])); ?></div>
                    <?php endif;?>
                </div>
                <div class='col-md-4'>
                    <?php if($item['Todo']['reminder_date'] !== null): ?>
                    <strong>Reminder: </strong><div id='todoFullDetails-reminder'>
                        <?= date('m/d/Y \a\t h:i A', strtotime($item['Todo']['reminder_date'])); ?></div>
                    <?php endif;?>
                </div>
            </div><p></p>
            <div class='row'>
                <div class='col-md-12'>
                    <strong>Customer/Contact Link:</strong><br />
                    <?php if($item['Todo']['contact_id'] !== null): ?>
                <strong>Contact: </strong> 
                <em><?= $item['Contact']['first_name'] . " " . $item['Contact']['last_name']; ?></em>
                <a href='/admin/contacts/view/<?= $item['Contact']['id']; ?>' class='btn btn-xs btn-info' role='button'>
                    <i class='fa fa-lg fa-eye'></i> View</a>
                <?php endif; ?>
                <?php if($item['Todo']['customer_id'] !== null): ?>
                <strong>Customer: </strong> 
                <em><?= $item['Customer']['name']; ?></em> 
                <a href='/admin/customers/view/<?= $item['Customer']['id']; ?>' class='btn btn-xs btn-success' role='button'>
                    <i class='fa fa-lg fa-eye'></i> View</a>
                <?php endif; ?>
                </div>
            </div>