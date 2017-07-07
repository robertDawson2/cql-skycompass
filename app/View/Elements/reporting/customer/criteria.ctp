
<div class="box box-danger collapsed-box">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-edit"></i>

              <h3 class="box-title">Search Criteria</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
               <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">
       
        <div class='row'>
            <h4>Limit...</h4>
            <div class='col-md-6'>
                
        <div class='row'>
            
            <div class='col-md-4'>
                    <input class='dateBefore' type='checkbox' id='checkGroups' />
                    By Group
  
                </div> 
            <div class='col-md-8'>
                <div style = "float: right; "><a role='button' class='deepcategoryCheckAll'>Check</a> &nbsp;/  <a role='button' class='deepcategoryUncheckAll'>Uncheck</a></div>
                <?= $this->Form->input('Group.groups', array('options' => $groups, 'multiple', 'class' => 'input form-control')); ?>
            </div>
        </div>
                
            </div>
            
            <div class='col-md-6'>
                
        <div class='row'>
            
            <div class='col-md-4'>
                    <input class='dateBefore' type='checkbox' id='checkTypes' />
                    By Customer Type
  
                </div> 
            <div class='col-md-8'>
                <div style = "float: right; "><a role='button' class='deepcategoryCheckAll'>Check</a> &nbsp;/  <a role='button' class='deepcategoryUncheckAll'>Uncheck</a></div>
                <?= $this->Form->input('Type.types', array('options' => $customerTypes, 'multiple', 'class' => 'input form-control')); ?>
            </div>
        </div>
                
            </div>
        </div>
        <hr>
            <div class='row'>

            <div class='col-md-6'>
                
        <div class='row'>
            
            <div class='col-md-4'>
                    <input class='dateBefore' type='checkbox' id='checkSources' />
                    By Source
  
                </div> 
            <div class='col-md-8'>
                <div style = "float: right; "><a role='button' class='deepcategoryCheckAll'>Check</a> &nbsp;/  <a role='button' class='deepcategoryUncheckAll'>Uncheck</a></div>
                <?= $this->Form->input('Source.sources', array('options' => $customerSources, 'multiple', 'class' => 'input form-control')); ?>
            </div>
        </div>
                
            </div>
            
            <div class='col-md-6'>
                
        <div class='row'>
            
            <div class='col-md-6'>
                    <input class='dateBefore' type='checkbox' id='checkMarketingEmails' />
                    By 'Opting Out For Marketing'
                    <?= $this->Form->input('marketingEmailsDropdown', array(
                        'class' => 'input form-control',
                        'label' => false,
                        'options' => array(0 => 'No', 1 => 'Yes'))); ?>
                </div> 
           
        
            
            <div class='col-md-6'>
                    <input class='dateBefore' type='checkbox' id='checkContractExpiration' />
                    By Contract Expiration
                    <?= $this->Form->input('contractExpiration', array(
                        'class' => 'input form-control datepicker')); ?>
                </div> 
           
        </div>
                
            </div>
        </div>

    </div>
</div>

