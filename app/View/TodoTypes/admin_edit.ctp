<div class='row'>
    <div class='col-md-8'>
        <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Todo Type</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <?= $this->Form->create('edit'); ?>
    <div class='row'>
    <div class='col-md-6'>
        <?= $this->Form->input('TodoType.name', array('class' => 'input form-control')); ?>
    
    </div>
<div class='col-md-6'>
        <label>
            Icon <small><a href="http://fontawesome.io/cheatsheet/" target="_BLANK">View icon options...</a></small>
        </label>
        <?= $this->Form->input('TodoType.fa_icon', array('class' => 'input form-control', 'placeholder' => 'Choose icon code from website...', 'label' => false)); ?>
    
    </div>
    </div>
    
    <div class='row'>
        <div class='col-md-12'>
             <input style='margin-top: 24px;' type='submit' class='btn btn-success' value='Save Changes' />
        </div>
    </div>
    
        <?= $this->Form->hidden('TodoType.id'); ?>
    
<?= $this->Form->end(); ?>
            </div>
            <!-- /.box-body -->
          </div>
    </div>
</div>
