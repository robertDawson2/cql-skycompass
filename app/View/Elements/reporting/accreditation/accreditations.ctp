
<div class="box box-success collapsed-box">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-edit"></i>

              <h3 class="box-title">Accreditation Criteria</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
               <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">
        <div class='row'>
            <div class='col-md-3'>
                <h4>Comparison for each attribute:</h4>
                <select id='accreditationAndOr' class='input form-control'>
                    <option val='OR'>OR</option>
                    <option val='AND'>AND</option>
                    
                </select>
                   
            </div>
        </div>
       
        <div class='row'>
            <div class='col-md-8'>

        <div class='row'>
            <div class='col-md-4'>
                <div class='row'>
                    <div class='col-md-12'>
                    <input class='dateBefore' type='checkbox' id='checkAccreditationExpired' />
                    
                    Expired Accreditations
                    </div>
                    <div class='col-md-12'>
                    <input type='hidden' value='<?= date('Y-m-d H:i:s'); ?>' class='datepicker data' />
                    </div>
                </div>
                </div> 
                <div class='col-md-4'>
                    <div class='row'>
                        <div class='col-md-12'>
                    <input class='dateBefore' type='checkbox' id='checkAccreditationExpiring' />
                    Expiring Accreditations Before:
                        </div>
                        <div class='col-md-12'>
                    <input type='text' class='input form-control datepicker data' />
                        </div>
                    </div>
                </div>
                <div class='col-md-4'>
                    <div class='row'>
                        <div class='col-md-12'>
                    <input class='dateBefore' type='checkbox' id='checkAccreditationVisit2' />
                        
                    Visit 2 Due Before: 
                    </div>
                        <div class='col-md-12'>
                    <input type='text' class='input form-control datepicker data' />
                        </div>
                    </div>
                </div>
        </div>
                <div class='row'>
                    <div class='col-md-4'>
                        <div class='row'>
                            <div class='col-md-12'>
                    <input class='dateBefore' type='checkbox' id='checkAccreditationVisit3' />
                    Visit 3 Due Before: 
                            </div>
                            <div class='col-md-12'>
                    <input type='text' class='input form-control datepicker data' />
                            </div>
                        </div>
                </div>
                
                <div class='col-md-4'>
                    <div class='row'>
                        <div class='col-md-12'>
                    <input class='dateBefore' type='checkbox' id='checkAccreditation9Mo' />
                    9 Month Follow-up Due Before: 
                        </div>
                        <div class='col-md-12'>
                    <input type='text' class='input form-control datepicker data' />
                        </div>
                    </div>
                </div>
                </div>
                
                    <div class='col-md-4'>
                        <div class='row'>
                        <div class='col-md-12'>
                    <input class='dateBefore' type='checkbox' id='checkAccreditation18Mo' />
                    18 Month On-site Due Before: 
                        </div>
                            <div class='col-md-12'>
                    <input type='text' class='input inline form-control datepicker data' />
                            </div>
                        </div>
                </div>
                <div class='row'>
                <div class='col-md-4'>
                    <div class='row'>
                        <div class='col-md-12'>
                    <input class='textCompare' type='checkbox' id='checkAccreditationNotes' />
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
                <input class='textCompare' type='checkbox' id='checkAccreditationTypes' /> Accreditation Types
                    </div>
                    <div class='col-md-12'>
                <a role='button' class='deepcategoryCheckAll'>Check</a> /&nbsp; <a role='button' class='deepcategoryUncheckAll'>Uncheck</a>
                <br />
                <select style="height: 200px;" class='input form-control data' multiple="multiple" id='accredTypes'>
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


