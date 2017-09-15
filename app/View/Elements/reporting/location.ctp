<?php $states = array(
    'AL'=>'Alabama',
    'AK'=>'Alaska',
    'AZ'=>'Arizona',
    'AR'=>'Arkansas',
    'CA'=>'California',
    'CO'=>'Colorado',
    'CT'=>'Connecticut',
    'DE'=>'Delaware',
    'DC'=>'District of Columbia',
    'FL'=>'Florida',
    'GA'=>'Georgia',
    'HI'=>'Hawaii',
    'ID'=>'Idaho',
    'IL'=>'Illinois',
    'IN'=>'Indiana',
    'IA'=>'Iowa',
    'KS'=>'Kansas',
    'KY'=>'Kentucky',
    'LA'=>'Louisiana',
    'ME'=>'Maine',
    'MD'=>'Maryland',
    'MA'=>'Massachusetts',
    'MI'=>'Michigan',
    'MN'=>'Minnesota',
    'MS'=>'Mississippi',
    'MO'=>'Missouri',
    'MT'=>'Montana',
    'NE'=>'Nebraska',
    'NV'=>'Nevada',
    'NH'=>'New Hampshire',
    'NJ'=>'New Jersey',
    'NM'=>'New Mexico',
    'NY'=>'New York',
    'NC'=>'North Carolina',
    'ND'=>'North Dakota',
    'OH'=>'Ohio',
    'OK'=>'Oklahoma',
    'OR'=>'Oregon',
    'PA'=>'Pennsylvania',
    'RI'=>'Rhode Island',
    'SC'=>'South Carolina',
    'SD'=>'South Dakota',
    'TN'=>'Tennessee',
    'TX'=>'Texas',
    'UT'=>'Utah',
    'VT'=>'Vermont',
    'VA'=>'Virginia',
    'WA'=>'Washington',
    'WV'=>'West Virginia',
    'WI'=>'Wisconsin',
    'WY'=>'Wyoming',
); ?>
<div class="box box-default collapsed-box">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-globe"></i>

              <h3 class="box-title">Limit By Location</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
               <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">
            <div class='row form-group'>
                 <div class="col-md-2">
                    <input class="like" type="radio" name="stateOrZip" id="noLimit" />Do Not Limit Results
                </div>
 <div class="col-md-4">
                <div class='row'>
                    <div class='col-md-12'>
                <input class='like' type='radio' name="stateOrZip" id='limitByState' /> Limit By State
                    </div>
                    <div class='col-md-12'>
                <a role='button' class='deepcategoryCheckAll'>Check</a> /&nbsp; <a role='button' class='deepcategoryUncheckAll'>Uncheck</a>
                <br />
                <select style="height: 200px;" class='input form-control data' multiple="multiple" id='states'>
                    <?php foreach($states as $i => $name): ?>
                    <option value='<?= $i; ?>'><?= $name; ?></option>
                    
                    <?php endforeach; ?>
                </select>
                    </div>
                </div>
            </div>
               <?php if(0): ?>
                 <div class="col-md-4">
                <div class='row'>
                    <div class='col-md-12'>
                <input class='like' type='radio' name="stateOrZip" id='limitByZip' /> Limit By Zip
                    </div>
                    <div class='col-md-12'>
                <div class="row">
                    <div class="col-md-6">
                        <input class="input form-control" type="text" maxlength="11" id="zip" />
                    </div>
                    <div class="col-md-6">
                <select class='input form-control data' id='zipRange'>
                    <option value="99999">---Any---</option>
                    <option value='10'>10 Miles</option>
                    <option value='20'>20 Miles</option>
                    <option value='30'>30 Miles</option>
                    <option value='50'>50 Miles</option>
                    <option value='75'>75 Miles</option>
                    <option value='100'>100 Miles</option>
                    <option value='250'>250 Miles</option>

                </select>
                    </div>
                </div>
                    </div>
                </div>
            </div>
                <?php endif; ?>
                </div>
    </div>
</div>