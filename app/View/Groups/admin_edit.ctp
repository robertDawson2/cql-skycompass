<div class='row'>
    <div class='col-md-8'>
        <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Group</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <?= $this->Form->create('edit'); ?>
    <div class='row'>
    <div class='col-md-12'>
        <?= $this->Form->input('Group.name', array('class' => 'input form-control')); ?>
    
    </div>
    </div>
    <div class='row'>
        <div class='col-md-5 col-md-offset-1'>
    <?= $this->Form->input('Group.is_contact', array('type'=>'checkbox', 'div' => 'checkbox', 'label' => 'Used For Contacts?')); ?>
    
    </div>
        
        <div class='col-md-5'>
            <?= $this->Form->input('Group.is_customer', array('type' => 'checkbox', 'div' => 'checkbox', 'label' => 'Used For Organizations?')); ?>
           
        </div>
    </div>
    <div class='row'>
        <div class='col-md-12'>
             <input style='margin-top: 24px;' type='submit' class='btn btn-success' value='Save Changes' />
        </div>
    </div>
    
        <?= $this->Form->hidden('Group.id'); ?>
    
<?= $this->Form->end(); ?>
            </div>
            <!-- /.box-body -->
          </div>
    </div>
</div>
