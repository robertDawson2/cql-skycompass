<style>
    .box {
        position: absolute;
    }
    </style>
<div class="row">
    <div class='col-md-3'>
        <ul class="nav nav-pills nav-stacked">
  <li class="active"><a data-toggle="pill" href="#general">General Information</a></li>
  <li><a data-toggle="pill" href="#customers">Linked Customers</a></li>
  <li><a data-toggle="pill" href="#addresses">Addresses</a></li>
  <li><a data-toggle="pill" href="#phones">Phone Numbers</a></li>
  <li><a data-toggle="pill" href="#files">Linked Docs</a></li>
  
</ul>
       
            <a style="margin-top: 30px;" href="#" class="btn btn-lg btn-success"><i class="fa fa-floppy-o"></i> Save Contact</a>
        
    </div>
    <div class="col-md-9">
        <div id="general" class="tab-pane fade in active">
<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-question-circle"></i> General Information
            </h3>

          
        </div>
        <!-- /.box-header -->
        <?php echo $this->Form->create('add'); ?>
        <div class="box-body">
      
            <div class="row">
                <?= $this->Form->input('first_name', array('div'=>'col-md-7', 'required', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('middle_name', array('div'=>'col-md-5', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('last_name', array('div'=>'col-md-12', 'required', 'class'=>'input form-control')); ?>
            </div>
            <div class="row">
                <?= $this->Form->input('title', array('div'=>'col-md-5', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('department', array('div'=>'col-md-5', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('birthday', array('div'=>'col-md-4', 'required', 'class'=>'datepicker input form-control')); ?>
            </div>
            <div class="row">
                <?= $this->Form->input('email', array('div'=>'col-md-6', 'type'=>'email', 'required', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('alt_email', array('div'=>'col-md-6', 'type' => 'email', 'label' => 'Alternate Email', 'class'=>'input form-control')); ?>
               
            </div>
            <h3>Options:</h3>
            <div class="row">
                <div class="col-md-4 col-md-offset-2">
                <?= $this->Form->input('default_contact', array('type'=>'checkbox', 'label' => 'Set as default contact', 'class'=>'checkbox')); ?>
                </div>
                <div class="col-md-4">
                <?= $this->Form->input('marketing_opt_out', array('type'=>'checkbox', 'label' => 'Opt out of Marketing Emails', 'class'=>'checkbox')); ?>
                </div>
            </div>
           
        </div>
        
        <?php echo $this->Form->end(); ?>
 </div>
        </div>
        
     <div class="tab-pane fade" id="customers">   
       <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Link to Customer(s)
            </h3>

          
        </div>
        <!-- /.box-header -->
        <?php echo $this->Form->create('add'); ?>
        <div class="box-body">
      
            <div class="row">
                <div class="col-md-12">
                    <strong><em>--- No Customers Linked ---</em></strong>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-8">
                <div class='form-group'>
                      <label>Customer/Job</label>
                      <select id='customerList'  data-placeholder='Select a customer or job...' class="form-control select2 validation" data-required='required' name='data[TimeEntry][customer_id]'>
                   <option></option>
                   <?php foreach($customers as $i => $p): ?>
                    <?php $selected = "";
                    
                    ?>
                    <option class="parent" value="<?= $i; ?>" <?= $selected; ?>>
                        <?= $p; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                  </div> 
                </div>
                <div class="col-md-4" style="padding-top: 20px;">
                    <a href="#" class="btn btn-primary"><i class="fa fa-plus"></i> Add Customer Link</a>
                     </div>
            </div>
           
        </div>
        
        <?php echo $this->Form->end(); ?>
 </div>
     </div>
        <div class="tab-pane fade" id="addresses">   
        <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Addresses
            </h3>

          
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <strong><em>--- No addresses listed ---</em></strong>
                </div>
            </div>
            <hr>
            <div class="row">
                <?= $this->Form->input('type', array('div'=>'col-md-6', 'options' => array(
                    'home' => 'Home',
                    'work' => 'Work',
                    'mailing' => 'Mailing',
                    'other' => 'Other'
                ), 'required', 'class'=>'input form-control')); ?>
               
            </div>
            <div class="row">
                <?= $this->Form->input('addr_1', array('div'=>'col-md-12', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('addr_2', array('div'=>'col-md-12', 'class'=>'input form-control')); ?>
            </div>
            <div class="row">
                <?= $this->Form->input('city', array('div'=>'col-md-5', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('state', array('div'=>'col-md-3', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('zip', array('div'=>'col-md-4', 'class'=>'input form-control')); ?>
            </div>
            
            <div class='row'>
                <div class='col-md-offset-8 col-md-2'>
                    <input id='submitButton' type="submit" class='btn btn-primary' value='Add New Address' />
                </div>
            </div>
        </div>
        
        <?php echo $this->Form->end(); ?>
 </div>
        </div>
        <div class="tab-pane fade" id="phones">   
         <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Phone Numbers
            </h3>

          
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <strong><em>--- No phone numbers listed ---</em></strong>
                </div>
            </div>
            <hr>
            <div class="row">
                <?= $this->Form->input('type', array('div'=>'col-md-8', 'class'=>'input form-control', 'options' => array(
                    'home' => 'Home',
                    'work' => 'Work',
                    'mobile' => 'Mobile',
                    'fax' => 'Fax',
                    'other' => 'Other'
                ))); ?>
               
            </div>
            <div class="row">
                <?= $this->Form->input('phone_number', array('div'=>'col-md-8', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('extension', array('div'=>'col-md-4', 'class'=>'input form-control')); ?>
              
            </div>
            
            <div class='row'>
                <div class='col-md-offset-8 col-md-2'>
                    <input id='submitButton' type="submit" class='btn btn-primary' value='Add New Phone' />
                </div>
            </div>
        </div>
        
        <?php echo $this->Form->end(); ?>
 </div>
        </div>
        
    </div>
</div>
    
<?php $this->append('scripts'); ?>
<script>
 
</script>
<?php $this->end(); ?>