
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
            <div class='col-md-8'>

        <div class='row'>
            <div class='col-md-4'>
                    <input type='checkbox' id='expired' />
                    Expired Accreditations
                    
                </div> 
                <div class='col-md-4'>
                    <input type='checkbox' id='expiring' />
                    Expiring Accreditations Before:
                    <input type='text' class='input form-control datepicker' />
                </div>
                <div class='col-md-4'>
                    <input type='checkbox' id='visit1' />
                    Visit 1 Due Before: 
                    <input type='text' class='input form-control datepicker' />
                </div>
        </div>
                <div class='row'>
                    <div class='col-md-4'>
                    <input type='checkbox' id='visit2' />
                    Visit 2 Due Before: 
                    <input type='text' class='input form-control datepicker' />
                </div>
                <div class='col-md-4'>
                    <input type='checkbox' id='visit3' />
                    Visit 3 Due Before: 
                    <input type='text' class='input form-control datepicker' />
                </div>
                <div class='col-md-4'>
                    <input type='checkbox' id='9mo' />
                    9 Month Follow-up Due Before: 
                    <input type='text' class='input form-control datepicker' />
                </div>
                </div>
                <div class='row'>
                    <div class='col-md-4'>
                    <input type='checkbox' id='18mo' />
                    18 Month On-site Due Before: 
                    <input type='text' class='input inline form-control datepicker' />
                </div>
                <div class='col-md-4'>
                    <input type='checkbox' id='notes' />
                    Notes contain: 
                    <input type='text' class='input form-control' />
                </div>
                </div>
                
                 </div>
            <div class='col-md-4'>
                <h4>Accreditation Types</h4>
                <a role='button' id='checkAll'>Check all</a> &nbsp; <a role='button' id='uncheckAll'>Uncheck all</a>
                <div class='row'>
                    <div class='col-md-12'>
                <select style="height: 200px;" class='input form-control' multiple="multiple" id='accredTypes'>
                    <?php foreach($accredTypes as $i => $type): ?>
                    <option value='<?= $i; ?>'><?= $type; ?></option>
                    
                    <?php endforeach; ?>
                </select>
                    </div>
                </div>
            </div>
        </div>
            

    </div>
</div>

<?php $this->append('jquery-scripts'); ?>
var dataarray = [<?php $first = true; foreach($accredTypes as $i => $type): 
    if(!$first)
        echo ", ";
    
    $first = false;
    echo "'" . $i . "'";
    endforeach; ?>]
$("#accredTypes").val(dataarray);


<?php $this->end(); ?>