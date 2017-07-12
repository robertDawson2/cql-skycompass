<style>
    .contact-star {
        color: lightgray;
        margin-right: 10px;
        cursor: pointer;
        transition: 300ms;
    }
    .contact-star:hover {
        transition: 300ms;
        text-shadow: -2px 0 goldenrod, 0 2px goldenrod, 2px 0 goldenrod, 0 -2px goldenrod;
    }
   .primary-contact, .primary-contact > .contact-star {
        color: gold;
    }
    .box {
        float: left;
        position: absolute;
    }
    .ui-menu {z-index: 99999;}
    .active > .box {
        z-index: 9999;
    
    }
    .edit-row {
        float: right;
        color: blue;
        text-decoration: none;
        font-size: 12px;
        cursor: pointer;
        margin-left: 10px;
        margin-right: 10px;
    }
    .edit-row:hover {
        color: darkblue;
        
    }
    .remove-row {
        float: right;
        color: red;
        text-decoration: none;
        font-size: 12px;
        cursor: pointer;
    }
    .remove-row:hover {
        color: darkred;
    }
    .phone-row, .customer-row, .address-row, .cert-row, .group-row {
        width: 400px;
        background-color: #fafafa;
        border-bottom: 1px solid white;
        padding: 5px 15px;
       
    }
    .phone-row:nth-child(2n), .customer-row:nth-child(2n), .address-row:nth-child(2n), .cert-row:nth-child(2n), .group-row:nth-child(2n)
    {
        background-color: #efefef;
    }
    .add-remove-list {
        list-style-type: none;
        
    }
    .deleted {
        color: #afafaf;
        //background-color: #aaa;
        text-decoration: line-through;
    }
    .revert-row {
        float: right;
        color: green;
        text-decoration: none;
        font-size: 12px;
        cursor: pointer;
    }
    .revert-row:hover {
        color: darkgreen;
    }
    
    </style>
<div class="row">
    <div class='col-md-3'>
        <ul class="nav nav-pills nav-stacked">
  <li class="active"><a data-toggle="pill" href="#general">General Information</a></li>
  <li><a data-toggle="pill" href="#customers">Linked Contacts</a></li>
  <li><a data-toggle="pill" href="#groups">Groups</a></li>
  <li><a data-toggle="pill" href="#addresses">Addresses</a></li>
  <li><a data-toggle="pill" href="#phones">Phone Numbers</a></li>
  <li><a data-toggle="pill" href="#certifications">Accreditations</a></li>
  <li><a data-toggle="pill" href="#files">Linked Docs</a></li>
  
</ul>
       
            <a id="submitAll" style="margin-top: 30px;" href="#" class="btn btn-lg btn-success"><i class="fa fa-floppy-o"></i> Save Organization</a>
        
    </div>
    <div class="col-md-9">
        <div id="general" class="tab-pane fade in active">
<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-question-circle"></i> General Information
            </h3>

          
        </div>
        <!-- /.box-header -->
        <?php echo $this->Form->create('edit'); ?>
        
        <div class="box-body">
      
            <div class="row">
                <?= $this->Form->input('Customer.name', array('div'=>'col-md-12', 'required', 'class'=>'input form-control')); ?>
                </div>
            <div class="row">
                <?= $this->Form->input('Customer.website', array('div'=>'col-md-12','type'=>'text', 'class'=>'input form-control')); ?>
                </div>
            
            <div class="row">
                <?= $this->Form->input('Customer.email', array('div'=>'col-md-6', 'type'=>'email', 'required', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('Customer.alt_email', array('div'=>'col-md-6', 'type' => 'email', 'label' => 'Alternate Email', 'class'=>'input form-control')); ?>
               
            </div>
            <div class="row">
                <?= $this->Form->input('Customer.source', array('div'=>'col-md-6', 'options'=>$sources, 'required', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('Customer.contract_expiration', array('div'=>'col-md-6', 'type' => 'text', 'label' => 'Contract Expiration', 'class'=>'datepicker input form-control')); ?>
               
            </div>
            <div class='row'>
               <?= $this->Form->input('Customer.types', array('selected' => explode("|", $current['Customer']['organization_type']), 'div' => 'col-md-6', 'type'=>'select', 'multiple'=>'multiple', 
                   'class' => 'input form-control', 'label' => 'Organization Type(s)', 'options' => $custTypes)); ?>
                <?= $this->Form->input('Customer.notes', array('div' => 'col-md-6', 'class' => 'input form-control')); ?>
            </div>
            <h3>Options:</h3>
            <div class="row">
               
                <div class="col-md-4 col-md-offset-4">
                <?= $this->Form->input('Customer.email_opt_out', array('type'=>'checkbox', 'label' => 'Opt out of Marketing Emails', 'class'=>'checkbox')); ?>
                </div>
            </div>
             <?= $this->Form->hidden('Customer.id'); ?>
           <?= $this->Form->hidden('Phone.list'); ?>
            <?= $this->Form->hidden('Contact.list'); ?>
            <?= $this->Form->hidden('Group.list'); ?>
            <?= $this->Form->hidden('Address.list'); ?> 
            <?= $this->Form->hidden('Cert.list'); ?>
            <?= $this->Form->hidden('Doc.list'); ?>
            <?= $this->Form->hidden('Phone.removed'); ?>
            <?= $this->Form->hidden('Contact.removed'); ?>
            <?= $this->Form->hidden('Group.removed'); ?>
            <?= $this->Form->hidden('Address.removed'); ?> 
            <?= $this->Form->hidden('Cert.removed'); ?>
            <?= $this->Form->hidden('Doc.removed'); ?>
            
        </div>
        
        <?php echo $this->Form->end(); ?>
 </div>
        </div>
        
     <div class="tab-pane fade" id="customers">   
       <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Link to Contact(s)
            </h3>

          
        </div>
        <!-- /.box-header -->
       
        <div class="box-body">
      
            <div class="row">
                <div class="col-md-12">
                    <ul id='customersAddList' class='add-remove-list'>
                        
                    <?php
                       
                            $emptyCustomers = true;
                        if(!empty($current['Contact'])) {
                            $emptyCustomers = false;
                            foreach($current['Contact'] as $customer):
                                if($customer['ContactCustomer']['archived'] === NULL):
                                ?>
                        <li data-saved-id="<?= $customer['id']; ?>" data-id="<?= $customer['id']; ?>" class="customer-row saved <?= $customer['id'] === $current['Customer']['primary_contact_id'] ? 'primary-contact' : ''; ?>">
                            <i onclick='selectPrimary(this);' title='Assign As Primary Contact' class='fa fa-star contact-star'></i> 
                        <?= $customer['first_name'] . " " . $customer['last_name']; ?> <i onclick="removeRow(this);" class="fa fa-remove remove-row"></i>
                        <i onclick="revertRow(this);" class="fa fa-refresh revert-row" style='display: none;'></i></li>

                        <?php 
                        endif;
                            endforeach;
                        } ?>
                    </ul>
                    <div class='empty-message' <?php if(!$emptyCustomers) { echo "style='display: none;'"; }?>>
                    <strong><em>--- No contacts linked ---</em></strong>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-8">
                <div class='form-group'>
                      <label>Contact List</label>
                      <select id='contactList'  data-placeholder='Select a contact...' class="form-control select2 validation" data-required='required' name='data[Contact][contact_id]'>
                   <option></option>
                   <?php foreach($contacts as $p): ?>
                    <?php $selected = "";
                    
                    ?>
                    <option class="parent" value="<?= $p['Contact']['id']; ?>" <?= $selected; ?>>
                        <?= $p['Contact']['first_name'] . " " . $p['Contact']['last_name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                  </div> 
                </div>
                <div class="col-md-4" style="padding-top: 20px;">
                    <a href="#" id='addCustomerSubmit' class="btn btn-primary"><i class="fa fa-plus"></i> Add Contact Link</a>
                     </div>
            </div>
           
        </div>
        

 </div>
     </div>
        
          <div class="tab-pane fade" id="groups">   
       <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-users"></i> Add To Group(s)
            </h3>

          
        </div>
        <!-- /.box-header -->
       
        <div class="box-body">
      
            <div class="row">
                <div class="col-md-12">
                    <ul id='groupAddList' class='add-remove-list'>
                        <?php
                            $emptyCustomers = true;
                        if(!empty($current['CustomerGroup'])) {
                            $emptyCustomers = false;
                            foreach($current['CustomerGroup'] as $customer):
                                ?>
                        <li data-saved-id='<?=$customer['id']; ?>' data-id="<?= $customer['group_id']; ?>" class="group-row saved">
                        <?= $groupTypes[$customer['group_id']]; ?> <i onclick="removeRow(this);" class="fa fa-remove remove-row"></i>
                        <i onclick="revertRow(this);" class="fa fa-refresh revert-row" style='display: none;'></i></li>

                        <?php 
                            endforeach;
                        } ?>
                    </ul>
                    <div class='empty-message' <?php if(!$emptyCustomers) { echo "style='display: none;'"; }?>>
                    <strong><em>--- No groups linked ---</em></strong>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-8">
                <div class='form-group'>
                      <label>Group Name</label>
                      <select id='groupList' class="form-control" name='data[Group][id]'>
                  
                   <?php foreach($groupTypes as $i => $p): ?>
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
                    <a href="#" id='addGroupSubmit' class="btn btn-primary"><i class="fa fa-plus"></i> Add To Group</a>
                     </div>
            </div>
           
        </div>
        

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
                    <ul id='addressList' class='add-remove-list'>
                    <?php
                        $addressTypes = array('physical' => 'Physical',
                    'shipping' => 'Shipping',
                    'billing' => 'Billing',
                    'other' => 'Other');
                            $emptyCustomers = true;
                        if(!empty($current['CustomerAddress'])) {
                            $emptyCustomers = false;
                            foreach($current['CustomerAddress'] as $address):
                                ?>
                        <li data-saved-id='<?=$address['id']; ?>' data-type="<?= $address['type']; ?>" 
                            data-addr1="<?= $address['address_1']; ?>" 
                             data-addr2="<?= $address['address_2']; ?>" 
                              data-city="<?= $address['city']; ?>" 
                               data-state="<?= $address['state']; ?>" 
                            data-zip="<?= $address['zip']; ?>" class="address-row saved">
                            <strong><?= $addressTypes[$address['type']]; ?></strong><br />
                            <?= $address['address_1']; ?>
                            <?php if(!empty($address['address_2'])) { ?>
                            <br />
                            <?= $address['address_2']; ?><?php } ?>
                            <br />
                            <?= $address['city']; ?>, <?= $address['state']; ?> <?= $address['zip']; ?> <i onclick="removeRow(this);" class="fa fa-remove remove-row"></i> 
                             <i onclick="editAddressRow(this);" class="fa fa-edit edit-row"></i>
                        <i onclick="revertRow(this);" class="fa fa-refresh revert-row" style='display: none;'></i></li>

                        <?php 
                            endforeach;
                        } ?>
                    </ul>
                    <div class='empty-message' <?php if(!$emptyCustomers) { echo "style='display: none;'"; }?>>
                    <strong><em>--- No addresses listed ---</em></strong>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <?= $this->Form->input('Address.type', array('div'=>'col-md-6', 'options' => array(
                    'physical' => 'Physical',
                    'shipping' => 'Shipping',
                    'billing' => 'Billing',
                    'other' => 'Other'
                ), 'required', 'class'=>'input form-control')); ?>
               
            </div>
            <div class="row">
                <?= $this->Form->input('addr_1', array('div'=>'col-md-12', 'label' => 'Address', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('addr_2', array('div'=>'col-md-12', 'label' => false, 'class'=>'input form-control')); ?>
            </div>
            <div class="row">
                <?= $this->Form->input('city', array('div'=>'col-md-5', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('state', array('div'=>'col-md-3', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('zip', array('div'=>'col-md-4', 'class'=>'input form-control')); ?>
            </div>
            
            <div class='row'>
                <div class='col-md-offset-8 col-md-2'>
                    <input style="margin-top: 25px;" id='addressSubmit' type="submit" class='btn btn-primary' value='Add New Address' />
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
                    <ul id='phoneNumberList' class='add-remove-list'>
                      <?php
                      $phoneTypes = array(
                          'business' => 'Business',
                    'isdn' => 'ISDN',
                    'toll-free' => 'Toll Free',
                    'fax' => 'Fax',
                    'other' => 'Other'
                      );
                            $emptyCustomers = true;
                        if(!empty($current['CustomerPhone'])) {
                            $emptyCustomers = false;
                            foreach($current['CustomerPhone'] as $phone):
                                ?>
                        <li data-saved-id='<?=$phone['id']; ?>' data-type="<?= $phone['type']; ?>" 
                            data-phone="<?= $phone['phone']; ?>" 
                            data-ext="<?= $phone['ext']; ?>" class="phone-row saved">
                            <?= $phone['phone']; ?><?php if(!empty($phone['ext']))
                                echo " ext. " . $phone['ext']; ?>
                             (<?= $phoneTypes[$phone['type']]; ?>) <i onclick="removeRow(this);" class="fa fa-remove remove-row"></i> 
                             <i onclick="editPhoneRow(this);" class="fa fa-edit edit-row"></i>
                        <i onclick="revertRow(this);" class="fa fa-refresh revert-row" style='display: none;'></i></li>

                        <?php 
                            endforeach;
                        } ?>
                    </ul>
                    <div class='empty-message' <?php if(!$emptyCustomers) { echo "style='display: none;'"; }?>>
                    <strong><em>--- No phone numbers listed ---</em></strong>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <?= $this->Form->input('Phone.type', array('div'=>'col-md-6', 'class'=>'input form-control', 'options' => array(
                    'business' => 'Business',
                    'isdn' => 'ISDN',
                    'toll-free' => 'Toll Free',
                    'fax' => 'Fax',
                    'other' => 'Other'
                ))); ?>
               
            </div>
            <div class="row">
                <?= $this->Form->input('phone_number', array('div'=>'col-md-6', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('extension', array('div'=>'col-md-3', 'class'=>'input form-control')); ?>
              
           
                <div class='col-md-3'>
                    <input style='margin-top: 25px;' id='phoneSubmit' type="submit" class='btn btn-primary' value='Add New Phone' />
                </div>
            </div>
        </div>
        
        
 </div>
        </div>
        
        <div class="tab-pane fade" id="certifications">   
         <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-trophy"></i> Accreditations
            </h3>

          
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <table id='certList' <?php if(count($current['CustomerAccreditation']) == 0) { ?> style='display: none;' <?php } ?> class='table table-bordered table-responsive table-striped'>
                        <thead>
                        <th>Type</th>
                        <th>Details</th>
                        <th>Dates</th>
                        <th></th>
                        <th>Notes</th>
                        <th></th>
                        
                        </thead>
                        <tbody>
                            <?php
                            $emptyCustomers = true;
                        if(!empty($current['CustomerAccreditation'])) {
                            $emptyCustomers = false;
                            foreach($current['CustomerAccreditation'] as $cert):
                                ?>
                            <tr data-saved-id='<?=$cert['id']; ?>' 
                                data-services_provided = '<?= $cert['services_provided']; ?>' 
                        data-accreditation_id="<?= $cert['accreditation_id']; ?>" 
                        data-terms="<?= $cert['terms']; ?>" 
                        data-expiration_date ="<?= $cert['expiration_date'] === null ? "" : date('m/d/Y', strtotime($cert['expiration_date'])); ?>" 
                        data-extension_date ="<?= $cert['extension_date'] === null ? "" : date('m/d/Y', strtotime($cert['extension_date'])); ?>" 
                        data-people_served ="<?= $cert['people_served']; ?>" 
                        data-visit_1_start_date ="<?= $cert['visit_1_start_date'] === null ? "" : date('m/d/Y', strtotime($cert['visit_1_start_date'])); ?>" 
                        data-visit_1_end_date ="<?= $cert['visit_1_end_date'] === null ? "" : date('m/d/Y', strtotime($cert['visit_1_end_date'])); ?>" 
                        data-visit_2_18_mo ="<?= $cert['visit_2_18_mo'] === null ? "" : date('m/d/Y', strtotime($cert['visit_2_18_mo'])); ?>" 
                        data-visit_2_start_date ="<?= $cert['visit_2_start_date'] === null ? "" : date('m/d/Y', strtotime($cert['visit_2_start_date'])); ?>" 
                        data-visit_2_end_date ="<?= $cert['visit_2_end_date'] === null ? "" : date('m/d/Y', strtotime($cert['visit_2_end_date'])); ?>" 
                        data-visit_3_required ="<?= $cert['visit_3_required']; ?>" 
                        data-visit_3_36_mo ="<?= $cert['visit_3_36_mo'] === null ? "" : date('m/d/Y', strtotime($cert['visit_3_36_mo'])); ?>" 
                        data-notes ="<?= $cert['notes']; ?>" 
                        data-visit_3_start_date ="<?= $cert['visit_3_start_date'] === null ? "" : date('m/d/Y', strtotime($cert['visit_3_start_date'])); ?>" 
                        data-visit_3_end_date ="<?= $cert['visit_3_end_date'] === null ? "" : date('m/d/Y', strtotime($cert['visit_3_end_date'])); ?>" 
                        data-pce_start_date ="<?= $cert['pce_start_date'] === null ? "" : date('m/d/Y', strtotime($cert['pce_start_date'])); ?>" 
                        data-pce_end_date ="<?= $cert['pce_end_date'] === null ? "" : date('m/d/Y', strtotime($cert['pce_end_date'])); ?>"  
                        data-9_mo_followup_required ="<?= $cert['9_mo_followup_required']; ?>" 
                        data-9_mo_due_date ="<?= $cert['9_mo_due_date'] === null ? "" : date('m/d/Y', strtotime($cert['9_mo_due_date'])); ?>" 
                        data-9_mo_actual_date ="<?= $cert['9_mo_actual_date'] === null ? "" : date('m/d/Y', strtotime($cert['9_mo_actual_date'])); ?>" 
                        data-18_mo_onsite_required ="<?= $cert['18_mo_onsite_required']; ?>" 
                        data-18_mo_due_date ="<?= $cert['18_mo_due_date'] === null ? "" : date('m/d/Y', strtotime($cert['18_mo_due_date'])); ?>" 
                        data-18_mo_actual_date ="<?= $cert['18_mo_actual_date'] === null ? "" : date('m/d/Y', strtotime($cert['18_mo_actual_date'])); ?>"
                        data-people_interviewed ="<?= $cert['people_interviewed']; ?>" 
                        class="cert-row saved">
                        <td><?=$accredTypes[$cert['accreditation_id']]; ?></td>
                        <td><strong>Services: </strong><?= $cert['services_provided']; ?>
                        <br />
                        <strong>Terms: </strong><?= ucwords($cert['terms']); ?>
                        <br />
                        <strong>Exp: </strong><?= $cert['expiration_date'] === null ? '' : date('m/d/Y', strtotime($cert['expiration_date'])); ?>
                        <br />
                        <strong>Ext: </strong><?= $cert['extension_date'] === null ? '' : date('m/d/Y', strtotime($cert['extension_date'])); ?>
                        <br />
                        <strong>Served: </strong><?= $cert['people_served']; ?>
                        <br />
                        <strong>Interviewed: </strong><?= $cert['people_interviewed']; ?>
                        </td>
                        
                        <td>
                            <strong>Visit 1</strong>
                            <br />
                            <em>Start: <?= $cert['visit_1_start_date'] === null ? '' : date('m/d/Y', strtotime($cert['visit_1_start_date'])); ?>
                                <br />
                                End: <?= $cert['visit_1_end_date'] === null ? '' : date('m/d/Y', strtotime($cert['visit_1_end_date'])); ?>
                     
                            </em>
                            <br />
                            <br />
                            <strong>Visit 2</strong>
                            <br />
                            
                            <em>Due <?= $cert['visit_2_18_mo'] === null ? '' : date('m/d/Y', strtotime($cert['visit_2_18_mo'])); ?>
                                <br />
                                Start: <?= $cert['visit_2_start_date'] === null ? '' : date('m/d/Y', strtotime($cert['visit_2_start_date'])); ?>
                                <br />
                                End: <?= $cert['visit_2_end_date'] === null ? '' : date('m/d/Y', strtotime($cert['visit_2_end_date'])); ?>
                            
                            </em>
                            <?php if($cert['visit_3_required']) { ?>
                            <br /><br />
                            <strong>Visit 3 Required</strong>
                            <br />
                             <em>Due <?= $cert['visit_3_36_mo'] === null ? '' : date('m/d/Y', strtotime($cert['visit_3_36_mo'])); ?>
                                <br />
                                Start: <?= $cert['visit_3_start_date'] === null ? '' : date('m/d/Y', strtotime($cert['visit_3_start_date'])); ?>
                                <br />
                                End: <?= $cert['visit_3_end_date'] === null ? '' : date('m/d/Y', strtotime($cert['visit_3_end_date'])); ?>
                            
                            </em>
                            <?php } ?>
                        </td>
                        <td>
                             <strong>PCE</strong>
                            <br />
                            <em>Start: <?= $cert['pce_start_date'] === null ? '' : date('m/d/Y', strtotime($cert['pce_start_date'])); ?>
                                <br />
                                End: <?= $cert['pce_end_date'] === null ? '' : date('m/d/Y', strtotime($cert['pce_end_date'])); ?>
                     
                            </em>
                            <?php if($cert['9_mo_followup_required']) { ?>
                            <br /><br />
                            <strong>9 Mo Follow-Up Required</strong>
                            <br />
                             <em>Due <?= $cert['9_mo_due_date'] === null ? '' : date('m/d/Y', strtotime($cert['9_mo_due_date'])); ?>
                                <br />
                                Actual: <?= $cert['9_mo_actual_date'] === null ? '' : date('m/d/Y', strtotime($cert['9_mo_actual_date'])); ?>
                               
                            
                            </em>
                            <?php } ?>
                            <?php if($cert['18_mo_onsite_required']) { ?>
                            <br /><br />
                            <strong>18 Mo On-Site Required</strong>
                            <br />
                             <em>Due <?= $cert['18_mo_due_date'] === null ? '' : date('m/d/Y', strtotime($cert['18_mo_due_date'])); ?>
                                <br />
                                Actual: <?= $cert['18_mo_actual_date'] === null ? '' : date('m/d/Y', strtotime($cert['18_mo_actual_date'])); ?>
                               
                            
                            </em>
                            <?php } ?>
                        </td>
                        <td><?= $cert['notes']; ?></td>
                        <td>
                         <i onclick="removeCertRow(this);" class="fa fa-remove remove-row"></i> 
                             <i onclick="editCertRow(this);" class="fa fa-edit edit-row"></i>
                        <i onclick="revertCertRow(this);" class="fa fa-refresh revert-row" style='display: none;'></i>
                        </td>
                            
                        <?php endforeach; }?>
                        </tbody>
                    </table>
                    <div class='empty-message' <?php if(!$emptyCustomers) { echo "style='display: none;'"; }?>>>
                    <strong><em>--- No accreditations listed ---</em></strong>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <?= $this->Form->input('Accreditation.type', array('div'=>'col-md-4', 'class'=>'input form-control', 'options' => $accredTypes
                        )); ?>
               <?= $this->Form->input('Accreditation.services_provided', array('label' => 'Services Provided','div'=>'col-md-4', 'class'=>'input form-control', 'options' => array('IDD','BEH'), 'multiple' => 'multiple'
                        )); ?>
                <?= $this->Form->input('Accreditation.terms', array('label' => 'Terms of Accreditation','div'=>'col-md-4', 'class'=>'input form-control', 'options' => $accredTerms
                        )); ?>
            </div>
            <div class="row">
                <?= $this->Form->input('Accreditation.expiration_date', array('label' => 'Expiration','div'=>'col-md-3', 'class'=>'datepicker input form-control')); ?>
                <?= $this->Form->input('Accreditation.extension_date', array('label' => 'Extension','div'=>'col-md-3', 'class'=>'datepicker input form-control')); ?>
                <?= $this->Form->input('Accreditation.people_served', array('label' => 'People Served','div'=>'col-md-3', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('Accreditation.people_interviewed', array('label' => 'People Interviewed','div'=>'col-md-3', 'class'=>'input form-control')); ?>
              
            </div>
            <hr>
            <div class="row">
                <div class='col-md-4'>
                    <h4>Visit 1</h4>
                    <div class='row'>
                        <?= $this->Form->input('Accreditation.visit_1_start_date', array('label' => 'Start','div'=>'col-md-6', 'class'=>'datepicker input form-control')); ?>
                        <?= $this->Form->input('Accreditation.visit_1_end_date', array('label' => 'End','div'=>'col-md-6', 'class'=>'datepicker input form-control')); ?>
                        
                    </div>
                </div>
                <div class='col-md-4'>
                    <h4>Visit 2</h4>
                    <div class='row'>
                        <?= $this->Form->input('Accreditation.visit_2_18_mo', array('label' => 'Due (18 mo)','div'=>'col-md-6', 'class'=>'datepicker input form-control')); ?>
                        <?= $this->Form->input('Accreditation.visit_2_start_date', array('label' => 'Start','div'=>'col-md-6', 'class'=>'datepicker input form-control')); ?>
                        <?= $this->Form->input('Accreditation.visit_2_end_date', array('label' => 'End','div'=>'col-md-6', 'class'=>'datepicker input form-control')); ?>
                        
                    </div>
                </div>
                <div class='col-md-4'>
                    <h4>Visit 3</h4>
                    <div class='row'>
                        <?= $this->Form->input('Accreditation.visit_3_required', array('label' => 'Required?','div'=>'col-md-6', 'class'=>'input form-control','options'=> array('0' => 'No', '1' => 'Yes'))); ?>
                        <?= $this->Form->input('Accreditation.visit_3_36_mo', array('label' => 'Due (36 mo)','div'=>'col-md-6', 'class'=>'datepicker input form-control')); ?>
                        <?= $this->Form->input('Accreditation.visit_3_start_date', array('label' => 'Start','div'=>'col-md-6', 'class'=>'datepicker input form-control')); ?>
                        <?= $this->Form->input('Accreditation.visit_3_end_date', array('label' => 'End','div'=>'col-md-6', 'class'=>'datepicker input form-control')); ?>
                        
                    </div>
                </div>
                  
                
            </div>
            <hr>
            <div class="row">
                <div class='col-md-4'>
                    <h4>PCE</h4>
                    <div class='row'>
                        <?= $this->Form->input('Accreditation.pce_start_date', array('label' => 'Start','div'=>'col-md-6', 'class'=>'datepicker input form-control')); ?>
                        <?= $this->Form->input('Accreditation.pce_end_date', array('label' => 'End','div'=>'col-md-6', 'class'=>'datepicker input form-control')); ?>
                        
                    </div>
                </div>
                <div class='col-md-4'>
                    <h4>9 Month Follow-Up</h4>
                    <div class='row'>
                        <?= $this->Form->input('Accreditation.9_mo_followup_required', array('label' => 'Required?','div'=>'col-md-6','options'=> array('0' => 'No', '1' => 'Yes'), 'class'=>'input form-control')); ?>
                        <?= $this->Form->input('Accreditation.9_mo_due_date', array('label' => 'Due','div'=>'col-md-6', 'class'=>'datepicker input form-control')); ?>
                        <?= $this->Form->input('Accreditation.9_mo_actual_date', array('label' => 'Actual','div'=>'col-md-6', 'class'=>'datepicker input form-control')); ?>                        
                        
                    </div>
                </div>
                <div class='col-md-4'>
                    <h4>18 Month On-Site</h4>
                    <div class='row'>
                        <?= $this->Form->input('Accreditation.18_mo_onsite_required', array('label' => 'Required?','div'=>'col-md-6','options'=> array('0' => 'No', '1' => 'Yes'), 'class'=>'input form-control')); ?>
                        <?= $this->Form->input('Accreditation.18_mo_due_date', array('label' => 'Due','div'=>'col-md-6', 'class'=>'datepicker input form-control')); ?>
                        <?= $this->Form->input('Accreditation.18_mo_actual_date', array('label' => 'Actual','div'=>'col-md-6', 'class'=>'datepicker input form-control')); ?>
                        
                        
                    </div>
                </div>
                  
                
            </div>
            
            <div class='row'>
                <?= $this->Form->input('Accreditation.notes', array('label' => 'Notes','div'=>'col-md-12', 'class'=>'input form-control', 'type' => 'textarea'
                        )); ?>
                 
            </div>
            <div class='row'>
                <div class='col-md-3'>
                    <input style='margin-top: 25px;' id='certSubmit' type="submit" class='btn btn-primary' value='Add New Certification' />
                </div>
            </div>
        </div>
        
        
 </div>
        </div>
        
               <div class="tab-pane fade" id="files">   
         <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Linked Docs
            </h3>

          
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <ul id='docAddList' class='add-remove-list'>
                        <?php
                            $emptyCustomers = true;
                        if(!empty($current['CustomerFile'])) {
                            $emptyCustomers = false;
                            foreach($current['CustomerFile'] as $customer):
                                ?>
                        <li data-saved-id='<?=$customer['id']; ?>' data-file="<?= $customer['file']; ?>" class="doc-row saved">
                        <?= $customer['file']; ?> <i onclick="removeRow(this);" class="fa fa-remove remove-row"></i>
                        <i onclick="revertRow(this);" class="fa fa-refresh revert-row" style='display: none;'></i></li>

                        <?php 
                            endforeach;
                        } ?>
                    </ul>
                    <div class='empty-message' <?php if(!$emptyCustomers) { echo "style='display: none;'"; }?>>
                    <strong><em>--- No linked documents ---</em></strong>
                    </div>
                </div>
            </div>
            <hr>
            
            <div class="row">
                <div class="col-md-6 col-md-offset-1">
               <?php echo $this->Form->input('newDoc', array('div' => 'input-group', 'label' => 'Choose/Upload Doc', 'class' => 'input form-control', 'after' => '<span class="input-group-btn" style="padding-top: 25px;"><button class="btn btn-primary" id="bgImageBrowse" type="button">Browse</button></span>')); ?>
                </div>
                <div class='col-md-3'>
                    <input style="margin-top: 25px;" id='addDocSubmit' type="submit" class='btn btn-primary' value='Add' />
                </div>
            </div>
        </div>
        
        <?php echo $this->Form->end(); ?>
 </div>
        </div>
        
    </div>
</div>
  <?php $this->start('jquery-scripts'); ?>
	
	$("#bgImageBrowse").on('click', function() {
        
		$('#roxyCustomPanel2').dialog({modal:true, width:875,height:600});
	});
	
<?php $this->end(); ?>  
<?php $this->append('scripts'); ?>
<script>
function closeCustomRoxy2(){
	$('#roxyCustomPanel2').dialog('close');
}

$("#certSubmit").click(function(e) {
    e.preventDefault();
    $services = "";

    $counter = 0;
    $("#AccreditationServicesProvided option:selected").each(function() {
        if($counter>0) {
            $services += "|";
          
        }
        $services += $(this).text();

        $counter++;
    });
    $html = "<tr data-accreditation_id='";
    $html += $("#AccreditationType option:selected").val();
    $html += "' data-services_provided='" + $services;
    $html += "' data-terms='" + $("#AccreditationTerms option:selected").text();
    $html += "' data-expiration_date='" + $("#AccreditationExpirationDate").val();
    $html += "' data-extension_date='" + $("#AccreditationExtensionDate").val();
    $html += "' data-people_served='" + $("#AccreditationPeopleServed").val();
    $html += "' data-visit_1_end_date='" + $("#AccreditationVisit1EndDate").val();
    $html += "' data-visit_2_18_mo='" + $("#AccreditationVisit218Mo").val();
    $html += "' data-visit_2_start_date='" + $("#AccreditationVisit2StartDate").val();
    $html += "' data-visit_2_end_date='" + $("#AccreditationVisit2EndDate").val();
    $html += "' data-visit_3_required='" + $("#AccreditationVisit3Required option:selected").val();
    
    $html += "' data-visit_3_36_mo='" + $("#AccreditationVisit336Mo").val();
    $html += "' data-notes='" + $("#AccreditationNotes").val();
    $html += "' data-visit_3_start_date='" + $("#AccreditationVisit3StartDate").val();
    $html += "' data-visit_3_end_date='" + $("#AccreditationVisit3EndDate").val();
    $html += "' data-pce_start_date='" + $("#AccreditationPceStartDate").val();
    $html += "' data-pce_end_date='" + $("#AccreditationPceEndDate").val();
    
    $html += "' data-9_mo_followup_required='" + $("#Accreditation9MoFollowupRequired option:selected").val();
    $html += "' data-9_mo_due_date='" + $("#Accreditation9MoDueDate").val();
    $html += "' data-9_mo_actual_date='" + $("#Accreditation9MoActualDate").val();

$html += "' data-18_mo_onsite_required='" + $("#Accreditation18MoOnsiteRequired option:selected").val();
    $html += "' data-18_mo_due_date='" + $("#Accreditation18MoDueDate").val();
    $html += "' data-18_mo_actual_date='" + $("#Accreditation18MoActualDate").val();
    
    $html += "' data-people_interviewed='" + $("#AccreditationPeopleInterviewed").val() + "' data-visit_1_start_date='";
    $html += $("#AccreditationVisit1StartDate").val() + "' class='cert-row'>";
    $html += "<td>" + $("#AccreditationType option:selected").text() + "</td>";
    $html += "<td><b>Services:</b> " + $services + "<br />";
    $html += "<b>Terms:</b> " + $("#AccreditationTerms option:selected").text() + "<br />"; 
    $html += "<b>Exp:</b> " + $("#AccreditationExpirationDate").val() + "<br /><b>Ext:</b> " + $("#AccreditationExtensionDate").val() + "<br />"; 
    $html += "<b>Served:</b> " + $("#AccreditationPeopleServed").val() + "<br /><b>Interviewed:</b> " + $("#AccreditationPeopleInterviewed").val() +  "</td>";
    $html += "<td><b><i>Visit 1</i></b><br /><i>Start: </i>" + $("#AccreditationVisit1StartDate").val() + "<br />";
    $html += "<i>End: </i>" + $("#AccreditationVisit1EndDate").val() + "<br /><br />";
    $html += "<b><i>Visit 2</i></b><br /><i>Due: </i>" + $("#AccreditationVisit218Mo").val() + "<br />";
    $html += "<i>Start: </i>" + $("#AccreditationVisit2StartDate").val() + "<br />";
    $html += "<i>End: </i>" + $("#AccreditationVisit2EndDate").val() + "";
    if($("#AccreditationVisit3Required option:selected").val() === "1")
    {
        $html += "<br /><br /><b><i>Visit 3 Required</i></b><br /><i>Due: </i>" + $("#AccreditationVisit336Mo").val() + "<br />";
    $html += "<i>Start: </i>" + $("#AccreditationVisit3StartDate").val() + "<br />";
    $html += "<i>End: </i>" + $("#AccreditationVisit3EndDate").val() + "";
    }
    $html += "</td>";
    
    $html += "<td><b><i>PCE</i></b><br /><i>Start: </i>" + $("#AccreditationPceStartDate").val() + "<br />";
    $html += "<i>End: </i>" + $("#AccreditationPceEndDate").val() + "";
    if($("#Accreditation9MoFollowupRequired option:selected").val() === "1")
    {
    $html += "<br /><br /><b><i>9 Mo Followup Required</i></b><br /><i>Due: </i>" + $("#Accreditation9MoDueDate").val() + "<br />";
    $html += "<i>Actual: </i>" + $("#Accreditation9MoActualDate").val() + "";
    }
    if($("#Accreditation18MoOnsiteRequired option:selected").val() === "1")
    {
        $html += "<br /><br /><b><i>18 Mo Onsite Required</i></b><br /><i>Due: </i>" + $("#Accreditation18MoDueDate").val() + "<br />";
    $html += "<i>Start: </i>" + $("#Accreditation18MoActualDate").val() + "";
   
    }
    $html += "</td>";
    $html += "<td>" + $("#AccreditationNotes").val() + "</td>"; 
    $html += "<td><i onclick='removeCertRow(this);' class='fa fa-remove remove-row'></i></td></tr>";
    $("#certList").append($html);
    if($(".cert-row").size() > 0)
    {
        $("#certList").parent().children(".empty-message").fadeOut();
        $("#certList").fadeIn();
    }
});

$("#phoneSubmit").click(function(e) {
    e.preventDefault();
    $extension = "";
    if($("#extension").val().trim() !== "")
        $extension = " ext. " + $("#extension").val();
    $html = "<li data-type='";
    $html += $("#PhoneType option:selected").val();
    $html += "' data-ext='" + $("#extension").val() + "' data-phone='";
    $html += $("#phone_number").val() + "' class='phone-row'>";
    $html += $("#phone_number").val() + $extension + " (" + $("#PhoneType option:selected").text() + ")<i onclick='removeRow(this);' class='fa fa-remove remove-row'></i>";
    $("#phoneNumberList").append($html);
    if($(".phone-row").size() > 0)
    {
        $("#phoneNumberList").parent().children(".empty-message").fadeOut();
    }
});
$("#addressSubmit").click(function(e) {
    e.preventDefault();
    $addr2 = "";
    if($("#addr_2").val().trim() !== "")
        $addr2 = "<br />" + $("#addr_2").val();
    $html = "<li data-type='";
    $html += $("#AddressType").val();
    $html += "' data-addr1='" + $("#addr_1").val() + "' data-addr2='" + $("#addr_2").val() + "' data-city='";
    $html += $("#city").val() + "' data-state='" + $("#state").val() + "' data-zip='" + $("#zip").val() +"' class='address-row'>";
    $html += "<strong>" + $("#AddressType option:selected").text() + "</strong><br />" + $("#addr_1").val() + $addr2 + "<br />" + $("#city").val() +", " + $("#state").val() +  " " + $("#zip").val() + "<i onclick='removeRow(this);' class='fa fa-remove remove-row'></i>";
    $("#addressList").append($html);
    if($(".address-row").size() > 0)
    {
        $("#addressList").parent().children(".empty-message").fadeOut();
    }
});
$("#addCustomerSubmit").click(function(e) {
    e.preventDefault();
    
    $html = "<li data-id='";
    $html += $("#contactList").val();
    $html += "'";
    $html += " class='customer-row'>";
        $html += "<i onclick='selectPrimary(this);' title='Assign As Primary Contact' class='fa fa-star contact-star'></i> ";
    $html +=  $("#contactList option:selected").text() + "<i onclick='removeRow(this);' class='fa fa-remove remove-row'></i>";
    $("#customersAddList").append($html);
    if($(".customer-row").size() > 0)
    {
        $("#customersAddList").parent().children(".empty-message").fadeOut();
    }
});

function selectPrimary(clicked)
{
    if($(clicked).parent().hasClass('primary-contact'))
    {
        $(clicked).parent().removeClass('saved');
        $(clicked).parent().removeClass('primary-contact');
        return true;
    }
    
    $(".primary-contact").removeClass('primary-contact');
    $(clicked).parent().removeClass('saved');
    $(clicked).parent().addClass('primary-contact');
}

$("#addGroupSubmit").click(function(e) {
    e.preventDefault();
    
    $html = "<li data-id='";
    $html += $("#groupList").val();
    $html += "'";
    $html += " class='group-row'>";
    $html +=  $("#groupList option:selected").text() + "<i onclick='removeRow(this);' class='fa fa-remove remove-row'></i>";
    $("#groupAddList").append($html);
    if($(".group-row").size() > 0)
    {
        $("#groupAddList").parent().children(".empty-message").fadeOut();
    }
});

$("#addDocSubmit").click(function(e) {
    e.preventDefault();
    
    $html = "<li data-file='";
    $html += $("#newDoc").val();
    $html += "'";
    $html += " class='doc-row'>";
    $html +=  $("#newDoc").val() + "<i onclick='removeRow(this);' class='fa fa-remove remove-row'></i>";
    $("#docAddList").append($html);
    if($(".doc-row").size() > 0)
    {
        $("#docAddList").parent().children(".empty-message").fadeOut();
    }
});

function editPhoneRow(me) {
    $(me).parent().children('.remove-row').hide();
    
    $("#phone_number").val($(me).parent().data('phone'));
    $("#extension").val($(me).parent().data('ext'));
    $("#PhoneType").val($(me).parent().data('type'));
    removeRow(me);
    $(me).parent().fadeOut('fast');
}
function editAddressRow(me) {
    $(me).parent().children('.remove-row').hide();
    
    $("#AddressType").val($(me).parent().data('type'));
    $("#addr_1").val($(me).parent().data('addr1'));
    $("#addr_2").val($(me).parent().data('addr2'));
    $("#city").val($(me).parent().data('city'));
    $("#state").val($(me).parent().data('state'));
    $("#zip").val($(me).parent().data('zip'));
    removeRow(me);
    $(me).parent().fadeOut('fast');
}
function editCertRow(me) {
    $(me).parent().children('.remove-row').hide();
    
    $("#AccreditationType").val($(me).parent().parent().data('accreditation_id'));
    $services = $(me).parent().parent().data('services_provided').split("|");
    

$("#AccreditationServicesProvided option:selected").removeAttr("selected");


    $("#AccreditationServicesProvided option").each(function() {

        if($services.indexOf($(this).text()) > -1)
        {
        
            $(this).attr('selected', 'selected');
        }
       
       
    });
    
    $("#AccreditationTerms options:contains(" + $(me).parent().parent().data('terms') + ")").attr('selected','selected');
    $("#AccreditationExpirationDate").val($(me).parent().parent().data('expiration_date'));
    $("#AccreditationExtensionDate").val($(me).parent().parent().data('extension_date'));
    $("#AccreditationPeopleServed").val($(me).parent().parent().data('people_served'));
    $("#AccreditationPeopleInterviewed").val($(me).parent().parent().data('people_interviewed'));
    $("#AccreditationNotes").val($(me).parent().parent().data('notes'));
     $("#AccreditationVisit1StartDate").val($(me).parent().parent().data('visit_1_start_date'));
    $("#AccreditationVisit1EndDate").val($(me).parent().parent().data('visit_1_end_date'));
     $("#AccreditationVisit218Mo").val($(me).parent().parent().data('visit_2_18_mo'));
    $("#AccreditationVisit2StartDate").val($(me).parent().parent().data('visit_2_start_date'));
    $("#AccreditationVisit2EndDate").val($(me).parent().parent().data('visit_2_end_date'));
    $("#AccreditationVisit3Required").val($(me).parent().parent().data('visit_3_required'));
     $("#AccreditationVisit336Mo").val($(me).parent().parent().data('visit_3_36_mo'));
    $("#AccreditationVisit3StartDate").val($(me).parent().parent().data('visit_3_start_date'));
    $("#AccreditationVisit3EndDate").val($(me).parent().parent().data('visit_3_end_date'));
    $("#AccreditationPceStartDate").val($(me).parent().parent().data('pce_start_date'));
    $("#AccreditationPceEndDate").val($(me).parent().parent().data('pce_end_date'));
    $("#Accreditation9MoFollowupRequired").val($(me).parent().parent().data('9_mo_followup_required'));
     $("#Accreditation9MoDueDate").val($(me).parent().parent().data('9_mo_due_date'));
    $("#Accreditation9MoActualDate").val($(me).parent().parent().data('9_mo_actual_date'));
    $("#Accreditation18MoOnsiteRequired").val($(me).parent().parent().data('18_mo_onsite_required'));
     $("#Accreditation18MoDueDate").val($(me).parent().parent().data('18_mo_due_date'));
    $("#Accreditation18MoActualDate").val($(me).parent().parent().data('18_mo_actual_date'));
    
    removeCertRow(me);
    $(me).parent().parent().fadeOut('fast');
}

function removeCertRow(me) {
    if(!$(me).parent().parent().hasClass('saved'))
    $(me).parent().parent().fadeOut().delay(500).remove();
    else
    {
        $(me).parent().parent().addClass('deleted');
        $(me).fadeOut('fast');
        $(me).parent().children('.edit-row').fadeOut('fast');
        $(me).parent().children('.revert-row').fadeIn('fast');
    }
 if($(".cert-row").size() === 0)
    {
        $("#certList").parent().children(".empty-message").fadeIn();
        $("#certList").fadeOut();
    }   
}
function revertCertRow(me) {
    $(me).parent().parent().removeClass('deleted');
    $(me).fadeOut('fast');
    $(me).parent().children('.remove-row').fadeIn('fast');
    $(me).parent().children('.edit-row').fadeIn('fast');
}
function revertRow(me) {
    $(me).parent().removeClass('deleted');
    $(me).fadeOut('fast');
    $(me).parent().children('.remove-row').fadeIn('fast');
    $(me).parent().children('.edit-row').fadeIn('fast');
}
function removeRow(me) {

    if(!$(me).parent().hasClass('saved'))
        $(me).parent().fadeOut().delay(500).remove();
    else
    {
        $(me).parent().addClass('deleted');
        $(me).fadeOut('fast');
        $(me).parent().children('.edit-row').fadeOut('fast');
        $(me).parent().children('.revert-row').fadeIn('fast');
    }
    if($(".phone-row").size() === 0)
    {
        $("#phoneNumberList").parent().children(".empty-message").fadeIn();
    }
    if($(".customer-row").size() === 0)
    {
        $("#customersAddList").parent().children(".empty-message").fadeIn();
    }
    if($(".address-row").size() === 0)
    {
        $("#addressList").parent().children(".empty-message").fadeIn();
    }
     if($(".group-row").size() === 0)
    {
        $("#groupList").parent().children(".empty-message").fadeIn();
    }
     if($(".doc-row").size() === 0)
    {
        $("#docList").parent().children(".empty-message").fadeIn();
    }
};

$("#submitAll").click(function(e) {
    e.preventDefault();
    if($(".phone-row").size() > 0)
    {
        $phone = [];
        $deleted = [];
        $(".phone-row").each(function() {
            $newPhone = {
                'type' : $(this).data('type'),
                'phone' : $(this).data('phone'),
                'ext' : $(this).data('ext')                
        };
        if(!$(this).hasClass('saved'))
            $phone.push($newPhone);
        
        if($(this).hasClass('deleted'))
        {
            $deleted.push($(this).data('saved-id'));
        }
    });
    
    $("#PhoneList").val(JSON.stringify($phone));
    $("#PhoneRemoved").val(JSON.stringify($deleted));
    }
    if($(".customer-row").size() > 0)
    {
        $customers = [];
        $deleted = [];
        $(".customer-row").each(function() {
            if($(this).hasClass('primary-contact'))
            {
                $newCust = {
                'id' : $(this).data('id'),
                'primary' : true
        };
    }
    else {
         $newCust = {
                'id' : $(this).data('id')       
            };
        }
        // have to still add all - just clear them when submitted. this is the only way to easily handle
        // primary contact changes.
        
        if(!$(this).hasClass('deleted'))
            $customers.push($newCust);
    
    if($(this).hasClass('deleted'))
        {
            $deleted.push($(this).data('saved-id'));
        }
        
        
    });
   
    $("#ContactList").val(JSON.stringify($customers));
    $("#ContactRemoved").val(JSON.stringify($deleted));
    }
    if($(".address-row").size() > 0)
    {
        $phone = [];
        $deleted = [];
        $(".address-row").each(function() {
            $newPhone = {
                'type' : $(this).data('type'),
                'addr1' : $(this).data('addr1'),
                'addr2' : $(this).data('addr2'),
                'city' : $(this).data('city'),
                'state' : $(this).data('state'),
                'zip' : $(this).data('zip')
        };
        if(!$(this).hasClass('saved'))
        $phone.push($newPhone);
    
    if($(this).hasClass('deleted'))
        {
            $deleted.push($(this).data('saved-id'));
        }
    });
   
    $("#AddressList").val(JSON.stringify($phone));
    $("#AddressRemoved").val(JSON.stringify($deleted));
    }
    if($(".cert-row").size() > 0)
    {
        $phone = [];
        $deleted = [];
        $(".cert-row").each(function() {
            
            $newPhone = $(this).data();
            
                               
        
        if(!$(this).hasClass('saved'))
        $phone.push($newPhone);
    
    if($(this).hasClass('deleted'))
        {
            $deleted.push($(this).data('saved-id'));
        }
    
    });
   
    $("#CertList").val(JSON.stringify($phone));
    $("#CertRemoved").val(JSON.stringify($deleted));
    }
    if($(".doc-row").size() > 0)
    {
        $phone = [];
        $deleted = [];
        $(".doc-row").each(function() {
            $newPhone = {
                'file' : $(this).data('file')              
        };
        if(!$(this).hasClass('saved'))
        $phone.push($newPhone);
    
     if($(this).hasClass('deleted'))
        {
            $deleted.push($(this).data('saved-id'));
        }
        
    });
   
    $("#DocList").val(JSON.stringify($phone));
    $("#DocRemoved").val(JSON.stringify($deleted));
    }
    
    if($(".group-row").size() > 0)
    {
        $phone = [];
        $deleted = [];
        $(".group-row").each(function() {
            $newPhone = {
                'group_type_id' : $(this).data('id')              
        };
        if(!$(this).hasClass('saved'))
        $phone.push($newPhone);
    
        if($(this).hasClass('deleted'))
        {
            $deleted.push($(this).data('saved-id'));
        }
    });
   
    $("#GroupList").val(JSON.stringify($phone));
    $("#GroupRemoved").val(JSON.stringify($deleted));
    }
    
    $("#editAdminEditForm").submit();
    
});
</script>
<?php $this->end(); ?>


<div id="roxyCustomPanel2" style="display: none; z-index: 100000;">
  <iframe src="/_/plugins/fileman/index.html?integration=custom&type=files&txtFieldId=newDoc" style="width:100%;height:100%" frameborder="0">
  </iframe>
</div>