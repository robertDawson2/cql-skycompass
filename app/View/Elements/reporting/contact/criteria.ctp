
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
           <div class='col-md-3'>
                <h4>Comparison By Section:</h4>
                <select id='overallAndOr' class='input form-control'>
                    <option val='AND'>AND</option>
                    <option val='OR'>OR</option>
                </select>
                   
            </div>
            <div class='col-md-3'>
                <h4>Comparison for each general search attribute:</h4>
                <select id='searchAndOr' class='input form-control'>
                    <option val='OR'>OR</option>
                    <option val='AND'>AND</option>
                    
                </select>
                   
            </div>
        </div>
        <div class='row'>
           
            <div class='col-md-6'>
                
        <div class='row'>
            
            <div class='col-md-4'>
                    <input class='dateBefore' type='checkbox' id='checkGroups' />
                    By Group
  
                </div> 
            <div class='col-md-8'>
                <div style = "float: right; "><a role='button' class='deepcategoryCheckAll'>Check</a> &nbsp;/  <a role='button' class='deepcategoryUncheckAll'>Uncheck</a></div>
                <?= $this->Form->input('Group.groups', array('options' => $groups, 'multiple', 'class' => 'input form-control data')); ?>
            </div>
        </div>
                
            </div>
            
            <div class='col-md-6'>
                
        <div class='row'>
            
            <div class='col-md-4'>
                    <input class='dateBefore' type='checkbox' id='checkOrgs' />
                    By Linked Organization
  
                </div> 
            <div class='col-md-8'>
                <div style = "float: right; "><a role='button' class='deepcategoryCheckAll'>Check</a> &nbsp;/  <a role='button' class='deepcategoryUncheckAll'>Uncheck</a></div>
                <?= $this->Form->input('Customer.customers', array('options' => $organizations, 'multiple', 'class' => 'input form-control data')); ?>
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
                <?= $this->Form->input('Source.sources', array('options' => $contactSources, 'multiple', 'class' => 'input form-control data')); ?>
            </div>
        </div>
                
            </div>
            
            <div class='col-md-6'>
                
        
                
        <div class='row'>
            
            <div class='col-md-4'>
                    <input class='dateBefore' type='checkbox' id='checkTypes' />
                    By Contact Type(s)
  
                </div> 
            <div class='col-md-8'> 
                <div style = "float: right; "><a role='button' class='deepcategoryCheckAll'>Check</a> &nbsp;/  <a role='button' class='deepcategoryUncheckAll'>Uncheck</a></div>
                <?= $this->Form->input('Type.types', array('options' => $types, 'multiple', 'class' => 'input form-control data')); ?>
            </div>
        </div>
            
            </div>
            </div>
            <hr>
            <div class='row'>
            <div class='col-md-6'>
                <div class='row'>
                    <div class='col-md-5'>
                    <input class='dateBefore' type='checkbox' id='checkMarketingEmails' />
                    By 'Opting Out For Marketing'
                    </div>
                    <div class='col-md-7'>
                    <?= $this->Form->input('marketingEmailsDropdown', array(
                        'class' => 'input form-control data',
                        'label' => false,
                        'options' => array(0 => 'No', 1 => 'Yes'))); ?>
                        </div>
                </div>
                </div> 
           
            </div>
            
            
           
       
                
           
        
        
       

    </div>
</div>

