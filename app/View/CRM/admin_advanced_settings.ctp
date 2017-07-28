<?php $this->Html->script('/_/plugins/tinymce/tinymce.min.js', array('block' => 'scripts')); ?>

<?php $this->start('scripts'); ?>
<script type="text/javascript">
tinymce.init(tinymceSettings);
function closeCustomRoxy2(){
	$('#roxyCustomPanel2').dialog('close');
}
</script>
<?php $this->end(); ?>

<?php $this->start('jquery-scripts'); ?>
	$("#bgImageBrowse").on('click', function() {
		$('#roxyCustomPanel2').dialog({modal:true, width:1050,height:600});
                return false;
	});
	
<?php $this->end(); ?>
<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-gears"></i> Advanced Settings
            </h3>
            <h5><em>Disclaimer: Editing these values are permanent, and can cause unexpected errors within your site. Please use caution when making changes to this screen.</em></h5>

          
        </div>
           
        <!-- /.box-header -->
        <div class="box-body">
            <h4>Site Base Info</h4>
            <form id="configInfoForm" action="/admin/CRM/advancedSettings" method="POST">
            <div class="row">
                <div class="col-md-4">
                    <label>
                        Site Name
                    </label>
                    <input name="data[site.name]" type='text' class='input form-control' value='<?= $config['site.name']; ?>' />
                </div> 
                <div class="col-md-4">
                    <label>
                        Site Full URL
                    </label>
                    <input name="data[site.url]" type='text' class='input form-control' value='<?= $config['site.url']; ?>' />
                </div>
                <div class="col-md-4">
                    <label>
                        Site FAQ File
                    </label>
                    <div class="row">
                        <div class="col-md-8">
                    <input name="data[site.faq_file]" id="siteFAQ" type='text' class='input form-control' value='<?= $config['site.faq_file']; ?>' />
                        </div>
                        <div class="col-md-4">
                    <span class="input-group-btn" ><button class="btn btn-primary" id="bgImageBrowse" type="button">Browse</button></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>
                        Campaign Monitor API Key
                    </label>
                    <input name="data[campaign_monitor.api_key]" type='text' class='input form-control' value='<?= $config['campaign_monitor.api_key']; ?>' />
                </div>
            </div>
            <hr>
            <h4>Payroll Details</h4>
            <div class="row">
                <div class="col-md-6">
                    <label>
                        Primary Approver (Expenses)
                    </label>
                    <select name="data[expenses.primary_approver]" class="input form-control">
                        <?php 
                        $primary = explode(":", $config['expenses.primary_approver'])[0];
                        
                        foreach($users as $id => $user):
                            echo "<option value='" . $id . "'";
                        if($id === $primary)
                            echo " selected='selected'";
                        echo ">" . $user . "</option>";
                        endforeach;  ?>
                    </select>
                </div> 
                <div class="col-md-6">
                    <label>
                        Secondary Approver (Expenses)
                    </label>
                    <select name="data[expenses.secondary_approver]" class="input form-control">
                        <?php
                        $secondary = explode(":", $config['expenses.secondary_approver'])[0];
                        foreach($users as $id => $user):
                            echo "<option value='" . $id . "'";
                        if($id === $secondary)
                            echo " selected='selected'";
                        echo ">" . $user . "</option>";
                        endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label>
                        Current Payroll Start
                    </label>
                    <input name="data[admin.payroll_start]" type='text' class='input form-control datepicker' value='<?= date('m/d/Y', strtotime($config['admin.payroll_start'])); ?>' />
                </div> 
                <div class="col-md-3">
                    <label>
                        Current Payroll End
                    </label>
                    <input name="data[admin.payroll_end]" type='text' class='input form-control datepicker' value='<?= date('m/d/Y', strtotime($config['admin.payroll_end'])); ?>' />
                </div>
                <div class="col-md-3">
                    <label>
                        Current Payroll Cutoff
                    </label>
                    <input name="data[admin.payroll_cutoff]" type='text' class='input form-control datepicker' value='<?= date('m/d/Y', strtotime($config['admin.payroll_cutoff'])); ?>' />
                </div>
                <div class="col-md-3">
                    <label>
                        Current Pay Date
                    </label>
                    <input name="data[admin.pay_date]" type='text' class='input form-control datepicker' value='<?= date('m/d/Y', strtotime($config['admin.pay_date'])); ?>' />
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>
                        Meal Quarter Cost
                    </label>
                    <input name="data[expenses.quarters]" type='text' class='input form-control' value='<?= $config['expenses.quarters']; ?>' />
                </div> 
                <div class="col-md-4">
                    <label>
                        Meal Quarters Upcharge
                    </label>
                    <input name="data[expenses.nyc_quarters_upcharge]" type='text' class='input form-control' value='<?= $config['expenses.nyc_quarters_upcharge']; ?>' />
                </div> 
                <div class="col-md-4">
                    <label>
                        Mileage Reimbursement Amount
                    </label>
                    <input name="data[expenses.mileage]" type='text' class='input form-control' value='<?= $config['expenses.mileage']; ?>' />
                </div> 
                 
        </div>
            <hr>
            <h4>Specific Lists</h4>
            <div class='row'>
                <div class='col-md-6'>
                    <label>
                        Certification Levels <small>(Separate with "|")</small>
                        
                    </label>
                    <input name="data[certification.levels]" type='text' class='input form-control' value='<?= $config['certification.levels']; ?>' />
                </div>
                <div class='col-md-6'>
                    <label>
                        Time Entry Service Items <small>(Separate with ",", be sure to match name EXACTLY)</small>
                        
                    </label>
                    <input name="data[time_entry.service_items]" type='text' class='input form-control' value='<?= $config['time_entry.service_items']; ?>' />
                </div>
            </div>
            
            <div class='row'>
                <div class='col-md-6'>
                    <label>
                        Customer Sources <small>(Separate with "|")</small>
                        
                    </label>
                    <input name="data[customer.sources]" type='text' class='input form-control' value='<?= $config['customer.sources']; ?>' />
                </div>
                <div class='col-md-6'>
                    <label>
                        Accreditation Terms <small>(Separate with "|")</small>
                        
                    </label>
                    <input name="data[accreditation.terms]" type='text' class='input form-control' value='<?= $config['accreditation.terms']; ?>' />
                </div>
            </div>
            
            <div class='row'>
                <div class='col-md-6'>
                    <label>
                        Contact Sources <small>(Separate with "|")</small>
                        
                    </label>
                    <input name="data[contact.sources]" type='text' class='input form-control' value='<?= $config['contact.sources']; ?>' />
                </div>
                
            </div>
            <input class="btn btn-primary btn-lg" type="submit" />
        
            </form>
 </div>
</div>
        
        <div id="roxyCustomPanel2" style="display: none; z-index: 100000;">
  <iframe src="/adminPanel/plugins/filemanager/dialog.php?type=0&field_id=siteFAQ" style="width:100%;height:100%" frameborder="0">
  </iframe>
</div>