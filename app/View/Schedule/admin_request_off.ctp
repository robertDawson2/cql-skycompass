<h1>Request Time Off</h1>
<?php echo $this->Form->create(); ?>
<h3>Enter your time below, along with a description:</h3>
<div class="row">
<?php echo $this->Form->input('start_date', array('class' => 'input form-control datepicker', 'div'=>'col-md-2')); ?>
<?php echo $this->Form->input('end_date', array('class' => 'input form-control datepicker', 'div'=>'col-md-2')); ?>
<?php echo $this->Form->input('type', array('class' => 'input form-control', 'div'=>'col-md-3', 'options' => array(
    'vacation' => 'Vacation',
    'sick_leave' => 'Sick Leave',
    'unpaid' => 'Unpaid Personal'
))); ?>
<?php echo $this->Form->input('notes', array('class' => 'input form-control', 'div'=>'col-md-5')); ?>
</div>
<div class="row" style="margin-top: 20px;">
<div class="col-md-2 col-md-offset-10">
    <input type="submit" value="Submit Request" class="btn btn-info btn-lg" />
</div>
</div>