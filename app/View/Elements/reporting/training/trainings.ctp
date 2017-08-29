
<div class="box box-success collapsed-box">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-edit"></i>

              <h3 class="box-title">Training Criteria</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
               <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">

        <div class='row'>
            <div class='col-md-8'>

                <div class='row'>
            
                <div class='col-md-4'>
                    <div class='row'>
                        <div class='col-md-12'>
                    <input class='dateBetween' type='checkbox' id='trainingBetween' />
                    Training Dates Between:
                        </div>
                        <div class='col-md-6'>
                    <input type='text' class='input form-control datepicker data start' />
                        </div>
                    <div class='col-md-6'>
                    <input type='text' class='input form-control datepicker data end' />
                        </div>
                    </div>
                </div>
        
                    <div class='col-md-4'>
                
        <div class='row'>
            
            <div class='col-md-12'>
                    <input class='dateBefore' type='checkbox' id='trainingUpsell' />
                    By Training Upsell
  
                </div> 
            <div class='col-md-8'>
                
                <?= $this->Form->input('Training.upsell', array('label'=>false, 'options' => array(1=>'Yes', 2=> 'No'), 'class' => 'input form-control data')); ?>
            </div>
        </div>
                
            </div>
       
                <div class='col-md-4'>
                    <div class='row'>
                        <div class='col-md-12'>
                    <input class='like' type='checkbox' id='trainingNotes' />
                    Notes contain: 
                        </div>
                        <div class='col-md-12'>
                    <input type='text' class='input form-control data' />
                        </div>
                    </div>
                </div>
                
                
                
            </div>
            </div>
            <div class='col-md-4'>
                <div class='row'>
                    <div class='col-md-12'>
                <input class='textCompare' type='checkbox' id='checkTrainingTypes' /> Training Types
                    </div>
                    <div class='col-md-12'>
                <a role='button' class='deepcategoryCheckAll'>Check</a> /&nbsp; <a role='button' class='deepcategoryUncheckAll'>Uncheck</a>
                <br />
                <select style="height: 200px;" class='input form-control data' multiple="multiple" id='trainingTypes'>
                    <?php foreach($trainingTypes as $i => $type): ?>
                    <option value='<?= $i; ?>'><?= $type; ?></option>
                    
                    <?php endforeach; ?>
                </select>
                    </div>
                </div>
                </div>
            
            
        </div>
        </div>
            

    </div>


