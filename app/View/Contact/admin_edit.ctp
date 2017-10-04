<style>
    .box {
        float: left;
        position: absolute;
    }
    .ui-menu {z-index: 99999;}
    .active > .box {
        z-index: 9999;
    
    }
    .deleted {
        color: #afafaf;
        background-color: #888;
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
    .phone-row, .customer-row, .address-row, .cert-row, .group-row, .doc-row {
        width: 400px;
        background-color: #fafafa;
        border-bottom: 1px solid white;
        padding: 5px 15px;
       
    }
    .phone-row:nth-child(2n), .customer-row:nth-child(2n), .doc-row:nth-child(2n), .address-row:nth-child(2n), .cert-row:nth-child(2n), .group-row:nth-child(2n)
    {
        background-color: #efefef;
    }
    .add-remove-list {
        list-style-type: none;
        
    }
    
    </style>
<div class="row">
    <div class='col-md-3'>
        <ul class="nav nav-pills nav-stacked">
  <li class="active"><a data-toggle="pill" href="#general">General Information</a></li>
  <li><a data-toggle="pill" href="#customers">Linked Organizations</a></li>
  <li><a data-toggle="pill" href="#groups">Groups</a></li>
  <li><a data-toggle="pill" href="#addresses">Addresses</a></li>
  <li><a data-toggle="pill" href="#phones">Phone Numbers</a></li>
  <li><a data-toggle="pill" href="#certifications">Certifications</a></li>
  <li><a data-toggle="pill" href="#portal">Portal Subscriptions</a></li>
  <li><a data-toggle="pill" href="#files">Linked Docs</a></li>
  
</ul>
       
            <a id="submitAll" style="margin-top: 30px;" href="#" class="btn btn-lg btn-success"><i class="fa fa-floppy-o"></i> Save Contact</a>
        
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
                <?= $this->Form->input('Contact.first_name', array('div'=>'col-md-7', 'required', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('Contact.middle_name', array('div'=>'col-md-5', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('Contact.last_name', array('div'=>'col-md-12', 'required', 'class'=>'input form-control')); ?>
            </div>
            <div class="row">
                <?= $this->Form->input('Contact.title', array('div'=>'col-md-5', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('Contact.department', array('div'=>'col-md-5', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('Contact.birthday', array('div'=>'col-md-4', 'type' => 'text', 'required', 'class'=>'datepicker input form-control')); ?>
            </div>
            <div class="row">
                <?= $this->Form->input('Contact.email', array('div'=>'col-md-6', 'type'=>'email', 'required', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('Contact.alt_email', array('div'=>'col-md-6', 'type' => 'email', 'label' => 'Alternate Email', 'class'=>'input form-control')); ?>
               
            </div>
            <div class="row">
                <?= $this->Form->input('Contact.source', array('div'=>'col-md-6', 'options'=>$sources, 'required', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('Contact.types', array('selected' => explode("|", $current['Contact']['contact_type']), 'div' => 'col-md-6', 'type'=>'select', 'multiple'=>'multiple', 
                   'class' => 'input form-control', 'label' => 'Contact Type(s)', 'options' => $custTypes)); ?>
               
            </div>
            <h3>Options:</h3>
            <div class="row">
                <div class="col-md-4 col-md-offset-2">
                <?= $this->Form->input('Contact.default_contact', array('type'=>'checkbox', 'label' => 'Set as default contact', 'class'=>'checkbox')); ?>
                </div>
                <div class="col-md-4">
                <?= $this->Form->input('Contact.marketing_opt_out', array('type'=>'checkbox', 'label' => 'Opt out of Marketing Emails', 'class'=>'checkbox')); ?>
                </div>
            </div>
            <?= $this->Form->hidden('Contact.id'); ?>
           <?= $this->Form->hidden('Phone.list'); ?>
            <?= $this->Form->hidden('Customer.list'); ?>
            <?= $this->Form->hidden('Group.list'); ?>
            <?= $this->Form->hidden('Address.list'); ?> 
            <?= $this->Form->hidden('Cert.list'); ?>
            <?= $this->Form->hidden('Doc.list'); ?>
            <?= $this->Form->hidden('Portal.list'); ?>
            <?= $this->Form->hidden('Phone.removed'); ?>
            <?= $this->Form->hidden('Customer.removed'); ?>
            <?= $this->Form->hidden('Group.removed'); ?>
            <?= $this->Form->hidden('Address.removed'); ?> 
            <?= $this->Form->hidden('Cert.removed'); ?>
            <?= $this->Form->hidden('Doc.removed'); ?>
            <?= $this->Form->hidden('Portal.removed'); ?>
            
        </div>
        
        <?php echo $this->Form->end(); ?>
 </div>
        </div>
        
     <div class="tab-pane fade" id="customers">   
       <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Link to Organization(s)
            </h3>

          
        </div>
        <!-- /.box-header -->
       
        <div class="box-body">
      
            <div class="row">
                <div class="col-md-12">
                    <ul id='customersAddList' class='add-remove-list'>
                        <?php
                       
                            $emptyCustomers = true;
                        if(!empty($current['Customer'])) {
                            $emptyCustomers = false;
                            foreach($current['Customer'] as $customer):
                                if($customer['ContactCustomer']['archived'] === NULL):
                                ?>
                        <li data-saved-id="<?= $customer['id']; ?>" data-id="<?= $customer['id']; ?>" class="customer-row saved">
                        <?= $customer['name']; ?> <i onclick="removeRow(this);" class="fa fa-remove remove-row"></i>
                        <i onclick="revertRow(this);" class="fa fa-refresh revert-row" style='display: none;'></i></li>

                        <?php 
                        endif;
                            endforeach;
                        } ?>
                    </ul>
                    <div class='empty-message' <?php if(!$emptyCustomers) { echo "style='display: none;'"; }?>>
                    <strong><em>--- No organizations linked ---</em></strong>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-8">
                <div class='form-group'>
                      <label>Organization</label>
                      <select id='customerList'  data-placeholder='Select an organization...' class="form-control select2 validation" data-required='required' name='data[TimeEntry][customer_id]'>
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
                    <a href="#" id='addCustomerSubmit' class="btn btn-primary"><i class="fa fa-plus"></i> Add Organization Link</a>
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
                        if(!empty($current['ContactGroup'])) {
                            $emptyCustomers = false;
                            foreach($current['ContactGroup'] as $customer):
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
                            $emptyCustomers = true;
                        if(!empty($current['ContactAddress'])) {
                            $emptyCustomers = false;
                            foreach($current['ContactAddress'] as $address):
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
                <?= $this->Form->input('Address.type', array('div'=>'col-md-6', 'options' => $addressTypes, 'required', 'class'=>'input form-control')); ?>
               
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
                            $emptyCustomers = true;
                        if(!empty($current['ContactPhone'])) {
                            $emptyCustomers = false;
                            foreach($current['ContactPhone'] as $phone):
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
                <?= $this->Form->input('Phone.type', array('div'=>'col-md-6', 'class'=>'input form-control', 'options' => $phoneTypes)); ?>
               
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
            <h3 class="box-title"><i class="fa fa-trophy"></i> Certifications
            </h3>

          
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <table id='certList' <?php if(count($current['ContactCertification']) == 0) { ?> style='display: none;' <?php } ?> class='table table-bordered table-responsive table-striped'>
                        <thead>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Level</th>
                        <th>Start</th>
                        <th>Recert</th>
                        <th>Renewal</th>
                        <th>Reliability</th>
                        <th>Notes</th>
                        <th></th></thead>
                        <?php
                            $emptyCustomers = true;
                        if(!empty($current['ContactCertification'])) {
                            $emptyCustomers = false;
                            foreach($current['ContactCertification'] as $cert):
                                ?>
                    <tr data-saved-id='<?=$cert['id']; ?>' 
                        data-type="<?= $cert['certification_id']; ?>" 
                        data-level="<?= $cert['level']; ?>" 
                        data-name="<?= $cert['name']; ?>" 
                        data-renewal_date="<?= $cert['renewal_date']; ?>" 
                        data-reliability_score="<?= $cert['reliability_score']; ?>" 
                        data-notes="<?= $cert['notes']; ?>" 
                        data-start_date="<?= $cert['start_date']; ?>" 
                        data-recertification_date="<?= $cert['recertification_date']; ?>" 
                        class="cert-row saved">
                        <td><?=$certTypes[$cert['certification_id']]; ?></td>
                        <td><?= $cert['name']; ?></td>
                        <td><?= $cert['level']; ?></td>
                        <td><?= $cert['start_date']; ?></td>
                        <td><?= $cert['recertification_date']; ?></td>
                        <td><?= $cert['renewal_date']; ?></td>
                        <td><?= $cert['reliability_score']; ?></td>
                        <td><?= $cert['notes']; ?></td>
                        <td>
                         <i onclick="removeCertRow(this);" class="fa fa-remove remove-row"></i> 
                             <i onclick="editCertRow(this);" class="fa fa-edit edit-row"></i>
                        <i onclick="revertCertRow(this);" class="fa fa-refresh revert-row" style='display: none;'></i>
                        </td>
                    </tr>

                        <?php 
                            endforeach;
                        } ?>
                   
                        
                    </table>
                    
                    <div class='empty-message' <?php if(!$emptyCustomers) { echo "style='display: none;'"; }?>>
                    <strong><em>--- No certifications listed ---</em></strong>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <?= $this->Form->input('Certification.name', array('div'=>'col-md-4', 'class'=>'input form-control')); ?>
                <?= $this->Form->input('Certification.type', array('div'=>'col-md-4', 'class'=>'input form-control', 'options' => $certTypes
                        )); ?>
               <?= $this->Form->input('Certification.level', array('label' => 'Certified As','div'=>'col-md-4', 'class'=>'input form-control', 'options' => $certLevels
                        )); ?>
            </div>
            <div class="row">
                <?= $this->Form->input('Certification.start_date', array('label' => 'Certified Since','div'=>'col-md-4', 'class'=>'datepicker input form-control')); ?>
                <?= $this->Form->input('Certification.recertification_date', array('label' => 'Recertification Date','div'=>'col-md-4', 'class'=>'datepicker input form-control')); ?>
                <?= $this->Form->input('Certification.renewal_date', array('label' => 'Renewal Date','div'=>'col-md-4', 'class'=>'datepicker input form-control')); ?>
              
            </div>
            <div class="row">
                  <?= $this->Form->input('Certification.reliability_score', array('div'=>'col-md-4', 'class'=>'input form-control'
                        )); ?>
                <?= $this->Form->input('Certification.notes', array('label' => 'Notes','div'=>'col-md-8', 'class'=>'input form-control', 'type' => 'textarea'
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
        
        <div class="tab-pane fade" id="portal">   
         <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-globe"></i> Portal System
            </h3>

          
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <table id='portalList' 
                           <?php if(count($current['Portal']) == 0) { ?> style='display: none;' <?php } ?> 
                           class='table table-bordered table-responsive table-striped'>
                        <thead>
                        <th>Access Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Notes</th>
                        <th></th>
                        
                        </thead>
                    
                    
                    <?php
                            $emptyCustomers = true;
                        if(!empty($current['Portal'])) {
                            $emptyCustomers = false;
                            foreach($current['Portal'] as $cert):
                                ?>
                    
                    <tr data-saved-id ="<?= $cert['id']; ?>"
                        data-access_type ="<?= $cert['access_type']; ?>" 
                        data-notes = "<?= $cert['notes']; ?>" 
                        data-start_date ="<?= $cert['start_date']; ?>" 
                        data-end_date ="<?= $cert['end_date']; ?>" 
                        class="portal-row saved">
                        <td><?=$cert['access_type']; ?></td>
                        <td><?= date('m/d/Y', strtotime($cert['start_date'])); ?></td>
                        <td><?= date('m/d/Y', strtotime($cert['end_date'])); ?></td>
                        <td><?= $cert['notes']; ?></td>
                        <td>
                         <i onclick="removePortalRow(this);" class="fa fa-remove remove-row"></i> 
                             <i onclick="editPortalRow(this);" class="fa fa-edit edit-row"></i>
                        <i onclick="revertPortalRow(this);" class="fa fa-refresh revert-row" style='display: none;'></i>
                        </td>
                    </tr>

                        <?php 
                            endforeach;
                        } ?>
                    </table>
                    <div class='empty-message' <?php if(!$emptyCustomers) { echo "style='display: none;'"; }?>>
                    <strong><em>--- No portal subscriptions listed ---</em></strong>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <?= $this->Form->input('Portal.access_type', array('div'=>'col-md-4', 'class'=>'input form-control', 'options' => $accessTypes)); ?>
                <?= $this->Form->input('Portal.start_date', array('type' => 'text','div'=>'col-md-4', 'class'=>'datepicker input form-control'
                        )); ?>
               <?= $this->Form->input('Portal.end_date', array('type'=>'text','div'=>'col-md-4', 'class'=>'datepicker input form-control'
                        )); ?>
            </div>
            
            <div class="row">
                 
                <?= $this->Form->input('Portal.notes', array('label' => 'Notes','div'=>'col-md-12', 'class'=>'input form-control', 'type' => 'textarea'
                        )); ?>
                 
            </div>
            <div class='row'>
                <div class='col-md-3'>
                    <input style='margin-top: 25px;' id='portalSubmit' type="submit" class='btn btn-primary' value='Add New Portal Subscription' />
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
                        if(!empty($current['ContactFile'])) {
                            $emptyCustomers = false;
                            foreach($current['ContactFile'] as $customer):
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

$("#portalSubmit").click(function(e) {
    e.preventDefault();
    console.log("here");
    $html = "<tr data-access_type='";
    $html += $("#PortalAccessType option:selected").text();
    $html += "' data-start_date='" + $("#PortalStartDate").val();
    $html += "' data-end_date='" + $("#PortalEndDate").val();
    
    $html += "' data-notes='" + $("#PortalNotes").val();
    $html += "' class='portal-row'>";
    $html += "<td>" + $("#PortalAccessType option:selected").text() + "</td>";
    $html += "<td>" + $("#PortalStartDate").val() + "</td>";
    $html += "<td>" + $("#PortalEndDate").val() + "</td>"; 
    $html += "<td>" + $("#PortalNotes").val() + "</td>"; 
    $html += "<td><i onclick='removePortalRow(this);' class='fa fa-remove remove-row'></i></td></tr>";
    $("#portalList").append($html);
    if($(".portal-row").size() > 0)
    {
        $("#portalList").parent().children(".empty-message").fadeOut();
        $("#portalList").fadeIn();
    }
});

$("#certSubmit").click(function(e) {
    e.preventDefault();
    
    $html = "<tr data-type='";
    $html += $("#CertificationType option:selected").val();
    $html += "' data-level='" + $("#CertificationLevel option:selected").text();
    $html += "' data-name='" + $("#CertificationName").val();
    $html += "' data-renewal_date='" + $("#CertificationRenewalDate").val();
    $html += "' data-reliability_score='" + $("#CertificationReliabilityScore").val();
    $html += "' data-notes='" + $("#CertificationNotes").val();
    $html += "' data-start_date='" + $("#CertificationStartDate").val() + "' data-recertification_date='";
    $html += $("#CertificationRecertificationDate").val() + "' class='cert-row'>";
    $html += "<td>" + $("#CertificationType option:selected").text() + "</td>";
    $html += "<td>" + $("#CertificationName").val() + "</td>";
    $html += "<td>" + $("#CertificationLevel option:selected").text() + "</td>"; 
    $html += "<td>" + $("#CertificationStartDate").val() + "</td>";
    $html += "<td>" + $("#CertificationRecertificationDate").val() + "</td>"; 
    $html += "<td>" + $("#CertificationRenewalDate").val() + "</td>";
    $html += "<td>" + $("#CertificationReliabilityScore").val() + "</td>";
    $html += "<td>" + $("#CertificationNotes").val() + "</td>"; 
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
    $html += $("#customerList").val();
    $html += "'";
    $html += " class='customer-row'>";
    $html +=  $("#customerList option:selected").text() + "<i onclick='removeRow(this);' class='fa fa-remove remove-row'></i>";
    $("#customersAddList").append($html);
    if($(".customer-row").size() > 0)
    {
        $("#customersAddList").parent().children(".empty-message").fadeOut();
    }
});

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
    
    $("#CertificationName").val($(me).parent().parent().data('name'));
    $("#CertificationType").val($(me).parent().parent().data('type'));
    $("#CertificationLevel").val($(me).parent().parent().data('level'));
    $("#CertificationStartDate").val($(me).parent().parent().data('start_date'));
    $("#CertificationRecertificationDate").val($(me).parent().parent().data('recertification_date'));
    $("#CertificationRenewalDate").val($(me).parent().parent().data('renewal_date'));
    $("#CertificationReliabilityScore").val($(me).parent().parent().data('reliability_score'));
    $("#CertificationNotes").val($(me).parent().parent().data('notes'));
    removeCertRow(me);
    $(me).parent().parent().fadeOut('fast');
}
function editPortalRow(me) {
    $(me).parent().children('.remove-row').hide();
    
    $("#PortalAccessType").val($(me).parent().parent().data('access_type'));
    $("#PortalStartDate").val($(me).parent().parent().data('start_date'));
    $("#PortalEndDate").val($(me).parent().parent().data('end_date'));
    $("#PortalNotes").val($(me).parent().parent().data('notes'));
    removePortalRow(me);
    $(me).parent().parent().fadeOut('fast');
}

function removePortalRow(me) {
    if(!$(me).parent().parent().hasClass('saved'))
    $(me).parent().parent().fadeOut().delay(500).remove();
    else
    {
        $(me).parent().parent().addClass('deleted');
        $(me).fadeOut('fast');
        $(me).parent().children('.edit-row').fadeOut('fast');
        $(me).parent().children('.revert-row').fadeIn('fast');
    }
 if($(".portal-row").size() === 0)
    {
        $("#portalList").parent().children(".empty-message").fadeIn();
        $("#portalList").fadeOut();
    }   
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
function revertPortalRow(me) {
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
            $newCust = {
                'id' : $(this).data('id'),
                
        };
        if(!$(this).hasClass('saved'))
        $customers.push($newCust);
    
    if($(this).hasClass('deleted'))
        {
            $deleted.push($(this).data('saved-id'));
        }
    });
   
    $("#CustomerList").val(JSON.stringify($customers));
    $("#CustomerRemoved").val(JSON.stringify($deleted));
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
            $newPhone = {
                'name' : $(this).data('name'),
                'certification_type_id' : $(this).data('type'),
                'level' : $(this).data('level'),   
                'start_date' : $(this).data('start_date'),
                'recertification_date' : $(this).data('recertification_date'),
                'renewal_date' : $(this).data('renewal_date'),   
                'reliability_score' : $(this).data('reliability_score'),
                'notes' : $(this).data('notes')
                               
        };
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
    
    if($(".portal-row").size() > 0)
    {
        $phone = [];
        $deleted = [];
        $(".portal-row").each(function() {
            $newPhone = {
                'access_type' : $(this).data('access_type'),
                'start_date' : $(this).data('start_date'),
                'end_date' : $(this).data('end_date'),
                'notes' : $(this).data('notes')
                               
        };
         if(!$(this).hasClass('saved'))
        $phone.push($newPhone);
    
    if($(this).hasClass('deleted'))
        {
            $deleted.push($(this).data('saved-id'));
        }
    });
   
    $("#PortalList").val(JSON.stringify($phone));
    $("#PortalRemoved").val(JSON.stringify($deleted));
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