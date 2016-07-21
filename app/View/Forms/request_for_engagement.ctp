<?php $this->set('title_for_layout', 'Request for Engagement'); ?>

<article style="width: 100%; float: none;">
<?php if (!isset($step)) $step = 1; ?>

<?php if ($step < 10): ?>
<h1 style="margin: 20px 0;">Accreditation Request for Engagement</h1>
<div><img src="/img/headers/request-for-engagement.jpg" /></div>
<p>Please contact Becky Hansen, Vice President of Accreditation and Training, with any questions at <a href="mailto:bhansen@thecouncil.org">bhansen@thecouncil.org</a> or 605.743.4442. All fields are required.</p>
<h1 style="margin: 20px 0; font-size: 34px;">Request for Engagement <small>Section <?php echo $step; ?> OF 9</small></h1>

<?php if (isset($this->validationErrors['RFEForm']) && !empty($this->validationErrors['RFEForm'])): ?>
<p style="font-weight: bold; color: #c00;">There was a problem with the information you entered. Please review the information in the form below.</p>
<?php endif; ?>

<div class="progress">
  <div class="progress-bar progress-bar-cql" role="progressbar" aria-valuenow="<?php echo $step * 11; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $step == 9 ? '100' : $step * 11; ?>%">
    <span class="sr-only">Step <?php echo $step; ?> of 9</span>
  </div>
</div>
<?php else: ?>
<h1 style="margin: 20px 0;">CQL Person-Centered Excellence Accreditation</h1>
<div><img src="/img/headers/request-for-engagement.jpg" /></div>
<h2 style="margin-top: 20px;">Request for Engagement Form Completed</h2>
<p>You will receive a Letter of Engagement with the onsite dates and cost of accreditation along with a Preparation Packet once the request has been processed.</p>
<?php endif; ?>

<form role="form" method="post" action="/forms/requestForEngagement">

<?php if ($step == 1): ?>
<div class="panel panel-default">
	<div class="panel-heading"></div>
	<div class="panel-body">

<h3 style="margin-top: 16px;">Organization</h3>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.organization_name', array('div' => 'col-md-12', 'class' => 'input form-control', 'label' => 'Name')); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.organization_address', array('div' => 'col-md-12', 'class' => 'input form-control', 'label' => 'Address')); ?>
</div>
<div class="row form-group">	
	<?php echo $this->Form->input('RFEForm.organization_city', array('div' => 'col-md-7', 'class' => 'input form-control', 'label' => 'City')); ?>
	<?php echo $this->Form->input('RFEForm.organization_state', array('div' => 'col-md-3', 'options' => $states, 'class' => 'input form-control', 'label' => 'State')); ?>
	<?php echo $this->Form->input('RFEForm.organization_zip', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => 'Zip')); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.organization_phone', array('div' => 'col-md-6', 'class' => 'input form-control', 'label' => 'Phone')); ?>
	<?php echo $this->Form->input('RFEForm.organization_fax', array('div' => 'col-md-6', 'class' => 'input form-control', 'label' => 'Fax')); ?>
</div>

<h3 style="margin-top: 26px;">Chief Executive Officer/Executive Director/Owner</h3>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.ceo_name', array('div' => 'col-md-6', 'class' => 'input form-control', 'label' => 'Name')); ?>
	<?php echo $this->Form->input('RFEForm.ceo_title', array('div' => 'col-md-6', 'class' => 'input form-control', 'label' => 'Title')); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.ceo_email', array('div' => 'col-md-6', 'class' => 'input form-control', 'label' => 'Email Address')); ?>
	<?php echo $this->Form->input('RFEForm.ceo_phone', array('div' => 'col-md-6', 'class' => 'input form-control', 'label' => 'Phone Number')); ?>
</div>

<h3 style="margin-top: 26px;">Billing</h3>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.billing_name', array('div' => 'col-md-6', 'class' => 'input form-control', 'label' => 'Name')); ?>
	<?php echo $this->Form->input('RFEForm.billing_title', array('div' => 'col-md-6', 'class' => 'input form-control', 'label' => 'Title')); ?>
</div>
<div class="row">
	<?php echo $this->Form->input('RFEForm.billing_email', array('div' => 'col-md-6', 'class' => 'input form-control', 'label' => 'Email Address')); ?>
	<?php echo $this->Form->input('RFEForm.billing_phone', array('div' => 'col-md-6', 'class' => 'input form-control', 'label' => 'Phone Number')); ?>
</div>

<h2 style="margin-top: 36px;">Scheduling Preference <small>must have at least 3 months for preparation</small></h2>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.schedule_first_choice', array('div' => 'col-md-4', 'class' => 'input form-control', 'label' => '1st Choice')); ?>
	<?php echo $this->Form->input('RFEForm.schedule_second_choice', array('div' => 'col-md-4', 'class' => 'input form-control', 'label' => '2nd Choice')); ?>
	<?php echo $this->Form->input('RFEForm.schedule_third_choice', array('div' => 'col-md-4', 'class' => 'input form-control', 'label' => '3rd Choice')); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.schedule_unavailable_weeks', array('div' => 'col-md-12', 'class' => 'input form-control', 'rows' => '2', 'label' => 'Please list any weeks during these months that are unavailable for the review:')); ?>
</div>

<div class="row form-group">
	<div class="col-md-12">
<h2 style="margin-top: 36px;">Types of Services Provided by the Organization</small></h2>
<p>Please check all that apply:</p>
<?php echo $this->Form->input('RFEForm.provided_1', array('type' => 'checkbox', 'label' => 'Direct and/or support services for young children and their families (ages 0-5).')); ?>
<?php echo $this->Form->input('RFEForm.provided_2', array('type' => 'checkbox', 'label' => 'Direct and/or support services for children and youth (ages 6-18).')); ?>
<?php echo $this->Form->input('RFEForm.provided_3', array('type' => 'checkbox', 'label' => 'Direct and/or support services for adults (ages 19 and above) with disabilities.')); ?>
<?php echo $this->Form->input('RFEForm.provided_4', array('type' => 'checkbox', 'label' => 'Direct and/or support services for adults (ages 19 and above) with behavioral health issues as 
their primary diagnosis.')); ?>
<?php echo $this->Form->input('RFEForm.provided_5', array('type' => 'checkbox', 'label' => 'Service coordination/Case Management services for young children and their families 
(ages 0-5)')); ?>
<?php echo $this->Form->input('RFEForm.provided_6', array('type' => 'checkbox', 'label' => 'Service coordination/Case Management services for children and youth (ages 6-18).')); ?>
<?php echo $this->Form->input('RFEForm.provided_7', array('type' => 'checkbox', 'label' => 'Service coordination/Case Management services for adults (ages 19 and above)')); ?>
<?php echo $this->Form->input('RFEForm.provided_8', array('type' => 'checkbox', 'label' => 'Other services not falling under these categories (describe briefly):')); ?>
	</div>
</div> 

<div class="row">
	<?php echo $this->Form->input('RFEForm.organization_operating_budget', array('div' => 'col-md-6', 'options' => array('' => 'Please select...', '$0-$1,000,000' => '$0-$1,000,000', '$1,000,001 - $2,500,000' => '$1,000,001 - $2,500,000', '$2,500,001 - $5,000,000' => '$2,500,001 - $5,000,000', '$5,000,001+' => '$5,000,001+'), 'class' => 'input form-control', 'label' => 'Organization\'s Operating Budget')); ?>
</div>

	</div>
</div>
<?php endif; ?>

<?php if ($step == 2): ?>
<div class="panel panel-default">
	<div class="panel-heading"></div>
	<div class="panel-body">
<h2 style="margin-top: 0;">Selection of Desired Accreditation</h2>
<p>Please select the accreditation model that best suits the needs of your organization from the options below.  Please note that the accreditation model selected for your organization must be approved by CQL.</p>
<?php if (0): ?><p>Descriptions of the accreditation models can be found in <a href="#">Addendum A</a>.</p><?php endif; ?>
		
<?php echo $this->Form->input('RFEForm.desired_accreditation_1', array('type' => 'checkbox', 'label' => '<b>CQL Systems Accreditation</b>')); ?>
<?php echo $this->Form->input('RFEForm.desired_accreditation_2', array('type' => 'checkbox', 'label' => '<b>CQL Quality Assurances&reg; Accreditation</b>')); ?>
<?php echo $this->Form->input('RFEForm.desired_accreditation_3', array('type' => 'checkbox', 'label' => '<b>CQL Person-Centered Excellence Accreditation</b>')); ?>
<?php echo $this->Form->input('RFEForm.desired_accreditation_4', array('type' => 'checkbox', 'label' => '<b>CQL Person-Centered Excellence Accreditation with Distinction Accreditation</b>')); ?>
	</div>
</div>
<?php endif; ?>

<?php if ($step == 3): ?>
<div class="panel panel-default">
	<div class="panel-heading"></div>
	<div class="panel-body">
<h2 style="margin-top: 0;">Additional Information</h2>
<p>In addition to the completion of all forms, please submit the following information:</p>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.currently_accredited', array('div' => 'col-md-12', 'class' => 'input form-control', 'options' => array('' => 'Please select...', 'Yes' => 'Yes', 'No' => 'No'), 'label' => 'Is your organization currently accredited by CQL?')); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.name_changed', array('div' => 'col-md-12', 'class' => 'input form-control', 'options' => array('' => 'Please select...', 'Yes' => 'Yes', 'No' => 'No'), 'label' => 'Has your organization changed names, merged, split, or otherwise structurally changed since the last review? If so, please clearly identify those changes.')); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.additional_comments', array('div' => 'col-md-12', 'class' => 'input form-control', 'rows' => '2', 'label' => 'Comments:')); ?>
</div>

<div class="row form-group">
		<?php echo $this->Form->input('RFEForm.organization_mission_statement', array('div' => 'col-md-12', 'class' => 'input form-control', 'rows' => '4', 'label' => 'Please provide your organization\'s Mission Statement &ndash; Values and Vision Statement.')); ?>
</div>

<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.organizational_biography', array('div' => 'col-md-12', 'class' => 'input form-control', 'rows' => '4', 'label' => 'Please provide an organizational biography and expectations for CQL Accreditation:')); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.pom_currently_in_use', array('div' => 'col-md-12', 'class' => 'input form-control', 'options' => array('' => 'Please select...', 'Yes' => 'Yes', 'No' => 'No'), 'label' => 'Does the organization currently utilize CQL\'s Personal Outcome Measures&reg;?')); ?>
</div>

<div class="panel panel-default">
	<div class="panel-heading" style="font-weight: bold;">If Yes:</div>
	<div class="panel-body">
		<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.information_gathering_process', array('div' => 'col-md-12', 'class' => 'input form-control', 'rows' => '4', 'label' => 'Describe the information gathering process and how data is collected and analyzed:')); ?>
		</div>
		<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.how_information_is_used', array('div' => 'col-md-12', 'class' => 'input form-control', 'rows' => '4', 'label' => 'Describe how information gleaned from Personal Outcome Measures&reg; is used at your organization (i.e. individual discovery and planning, organizational evaluation and planning, etc.):')); ?>
		</div>
		<div class="row">
	<?php echo $this->Form->input('RFEForm.certified_pom_staff', array('div' => 'col-md-12', 'class' => 'input form-control', 'rows' => '4', 'label' => 'Please list any Certified Personal Outcome Measures&reg; Trainers or Interviewers currently employed at the organization.:')); ?>
		</div>
	</div>
</div>

<div class="row">
	<?php echo $this->Form->input('RFEForm.information_gathering_comments', array('div' => 'col-md-12', 'class' => 'input form-control', 'rows' => '4', 'label' => 'Comments:')); ?>
</div>
	</div>
</div>
<?php endif; ?>


<?php if ($step == 4): ?>
<div class="panel panel-default">
	<div class="panel-heading"></div>
	<div class="panel-body">
<h2 style="margin-top: 0;">Information about People Served by the Organization</h2>
<p>Please indicate the number of people served by the organization in each of the categories listed.</p>
		
<div class="row form-group" style="margin-bottom: 8px;">
	<div class="col-md-3"></div>
	<div class="col-md-9">
		<div class="col-md-2" style="text-align: center;">Below<br>Age 6</div>
		<div class="col-md-2" style="text-align: center;">Age<br>6-18</div>
		<div class="col-md-2" style="text-align: center;">Age<br>19-55</div>
		<div class="col-md-2" style="text-align: center;">Over<br>Age 55</div>
		<div class="col-md-2" style="text-align: center;">TOTAL</div>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<div class="col-md-3" style="padding-top: 8px;">Male</div>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_1_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_1_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_1_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_1_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_1_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<div class="col-md-3" style="padding-top: 8px;">Female</div>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_2_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_2_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_2_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_2_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_2_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group">
	<div class="col-md-3" style="padding-top: 8px;">Total</div>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_3_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_3_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_3_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_3_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_3_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<div class="col-md-12"><b>Primary Diagnosis</b></div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<div class="col-md-3" style="padding-top: 8px;">Autism</div>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_4_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_4_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_4_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_4_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_4_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<div class="col-md-3" style="padding-top: 8px;">Cerebral Palsy</div>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_5_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_5_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_5_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_5_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_5_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<div class="col-md-3" style="padding-top: 8px;">Intellectual Disability</div>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_6_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_6_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_6_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_6_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_6_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<?php echo $this->Form->input('RFEForm.info_7_name', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_7_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_7_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_7_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_7_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_7_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<?php echo $this->Form->input('RFEForm.info_8_name', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_8_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_8_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_8_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_8_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_8_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<?php echo $this->Form->input('RFEForm.info_9_name', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_9_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_9_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_9_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_9_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_9_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<?php echo $this->Form->input('RFEForm.info_10_name', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_10_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_10_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_10_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_10_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_10_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<div class="col-md-3" style="padding-top: 8px;">Seizure Disorder</div>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_11_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_11_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_11_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_11_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_11_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<div class="col-md-3" style="padding-top: 8px;">Mental Illness <small>(Please Specify)</small></div>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_12_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_12_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_12_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_12_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_12_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<?php echo $this->Form->input('RFEForm.info_13_name', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_13_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_13_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_13_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_13_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_13_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<?php echo $this->Form->input('RFEForm.info_14_name', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_14_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_14_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_14_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_14_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_14_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<?php echo $this->Form->input('RFEForm.info_15_name', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_15_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_15_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_15_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_15_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_15_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<div class="col-md-3" style="padding-top: 8px;">Other Disabilities <small>(Please Specify)</small></div>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_16_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_16_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_16_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_16_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_16_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<?php echo $this->Form->input('RFEForm.info_17_name', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_17_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_17_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_17_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_17_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_17_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<?php echo $this->Form->input('RFEForm.info_18_name', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_18_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_18_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_18_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_18_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_18_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>
<div class="row form-group" style="margin-bottom: 8px;">
	<?php echo $this->Form->input('RFEForm.info_19_name', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<div class="col-md-9">
		<?php echo $this->Form->input('RFEForm.info_19_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_19_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_19_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_19_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
		<?php echo $this->Form->input('RFEForm.info_19_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
	</div>
</div>


	</div>
</div>
<?php endif; ?>


<?php if ($step == 5): ?>
<div class="panel panel-default">
	<div class="panel-heading"></div>
	<div class="panel-body">
<h2 style="margin-top: 0;">Certification of Compliance</h2>
<div class="row form-group">
	<div class="col-md-12">
<p style="margin-top: 0;">I certify that <?php echo $this->Form->input('RFEForm.organization_name_compliance', array('style' => 'width: 400px; display: inline;', 'div' => false, 'class' => 'input form-control', 'label' => false, 'placeholder' => 'Organization Name')); ?> is in compliance with all required local, state, and federal regulations relevant to the supports and services we provide including:</p>
<ul>
	<li>Licensing and certification requirements;
	<li>Sanitation/fire and safety codes;
	<li>Reporting compliance for incidents, abuse and/or neglect; and
	<li>Any other that may apply.
</ul>

<p>I affirm that there are no current open or unresolved issues related to:</p>
<ul>
	<li>Outstanding fiscal or legal sanctions;
	<li>Non-compliance with regulations;
	<li>Licensing exceptions;
	<li>Unfavorable third party reviews;
	<li>Abuse, neglect, or other circumstances being investigated by local, state or federal entities; and
	<li>Any related circumstances that require a plan of correction in order to remain licensed, certified, or funded.
</ul>

<p>I confirm that we have:</p>
<ul>
	<li>current external monitoring reports and responses for all services and supports provided;</li>
	<li>current external monitoring reports and responses for all licensed buildings showing that all required safety/compliance standards are met;</li>
	<li>clear policies that state the procedures for meeting local, state, funding, and federal requirements;</li>
	<li>current plans of correction showing all outstanding issues have been (or are being) addressed.</li>
</ul>

<p style="margin-bottom: 0;">I agree to provide completed copies of evidence of compliance for any external monitoring reports and if appropriate, approved corrective plans to CQL as requested.</p>
	</div>
</div>

<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.certification_name', array('div' => 'col-md-6', 'class' => 'input form-control', 'label' => 'Name')); ?>
	<?php echo $this->Form->input('RFEForm.certification_date', array('div' => 'col-md-6', 'class' => 'input form-control', 'label' => 'Date')); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.certification_signature', array('div' => 'col-md-12', 'class' => 'input form-control', 'label' => 'CEO Signature')); ?>
</div>

<?php echo $this->Form->input('RFEForm.certification_checkbox', array('type' => 'checkbox', 'label' => '<span style="color: #f00; font-weight: bold;">Please type name above and check this box to confirm that this electronically-submitted document is a binding agreement without the actual signature of the Chief Executive Officer.</span>')); ?>
	</div>
</div>
<?php endif; ?>


<?php if ($step == 6): ?>
<div class="panel panel-default">
	<div class="panel-heading"></div>
	<div class="panel-body">
<h2 style="margin-top: 0;">List of Organization Leadership Staff</h2>
<p><b>Purpose:</b><br />This list will be used to coordinate a Pre-engagement Planning Call which is designed to provide the organization’s leadership staff with information about the accreditation process, preparation activities and responsibilities.</p>
		
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_1', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => 'Name')); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_1', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => 'Title')); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_1', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => 'Phone')); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_1', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => 'Email Address')); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_2', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_2', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_2', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_2', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_3', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_3', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_3', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_3', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_4', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_4', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_4', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_4', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_5', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_5', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_5', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_5', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_6', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_6', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_6', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_6', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_7', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_7', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_7', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_7', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_8', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_8', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_8', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_8', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_9', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_9', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_9', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_9', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_10', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_10', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_10', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_10', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_11', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_11', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_11', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_11', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_12', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_12', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_12', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_12', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_13', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_13', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_13', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_13', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_14', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_14', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_14', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_14', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.leadership_name_15', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_title_15', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_phone_15', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.leadership_email_15', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
</div>
	</div>
</div>
<?php endif; ?>

<?php if ($step == 7): ?>
<div class="panel panel-default">
	<div class="panel-heading"></div>
	<div class="panel-body">
<h2 style="margin-top: 0;">Travel Advice for CQL Team</h2>
<h3 style="margin-top: 16px;">Airport Information</h3>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.nearest_airport', array('div' => 'col-md-8', 'class' => 'input form-control', 'label' => 'Nearest Major Airport')); ?>
	<?php echo $this->Form->input('RFEForm.airport_distance', array('div' => 'col-md-4', 'class' => 'input form-control', 'label' => 'Distance from Organization (miles)')); ?>
</div>
<h3 style="margin-top: 16px;">Lodging Information</h3>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.travel_hotel_1', array('div' => 'col-md-4', 'class' => 'input form-control', 'label' => 'Hotel/Motel')); ?>
	<?php echo $this->Form->input('RFEForm.travel_city_1', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => 'City')); ?>
	<?php echo $this->Form->input('RFEForm.travel_phone_1', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => 'Telephone')); ?>
	<?php echo $this->Form->input('RFEForm.travel_distance_1', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => 'Distance to Org')); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.travel_hotel_2', array('div' => 'col-md-4', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_city_2', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_phone_2', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_distance_2', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.travel_hotel_3', array('div' => 'col-md-4', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_city_3', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_phone_3', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_distance_3', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.travel_hotel_4', array('div' => 'col-md-4', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_city_4', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_phone_4', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_distance_4', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.travel_hotel_5', array('div' => 'col-md-4', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_city_5', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_phone_5', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_distance_5', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.travel_hotel_6', array('div' => 'col-md-4', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_city_6', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_phone_6', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_distance_6', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.travel_hotel_7', array('div' => 'col-md-4', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_city_7', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_phone_7', array('div' => 'col-md-3', 'class' => 'input form-control', 'label' => false)); ?>
	<?php echo $this->Form->input('RFEForm.travel_distance_7', array('div' => 'col-md-2', 'class' => 'input form-control', 'label' => false)); ?>
</div>
	</div>
</div>
<?php endif; ?>



<?php if ($step == 8): ?>
<div class="panel panel-default">
	<div class="panel-heading"></div>
	<div class="panel-body">
<h2 style="margin-top: 0;">Engagement Agreement</h2>
<div class="row form-group">
	<div class="col-md-12">
<p style="margin-top: 0;">The undersigned hereby agrees to work with CQL | The Council on Quality and Leadership for CQL accreditation and/or related activities, agrees to pay the established fee, and grants permission to licensing agencies and any other relevant examining or reviewing entity or group to release official records and information to CQL for its consideration during the accreditation process.</p>
<p>Upon receipt, CQL will process this request and set dates for the initial accreditation.  In the event that the scheduled accreditation is canceled or postponed by the organization, the organization is responsible for payment of any expenses incurred by CQL.</p>
	</div>
</div>

<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.agreement_organization_name', array('div' => 'col-md-6', 'class' => 'input form-control', 'label' => 'Organization Name')); ?>
	<?php echo $this->Form->input('RFEForm.agreement_date', array('div' => 'col-md-6', 'class' => 'input form-control', 'label' => 'Date')); ?>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.agreement_signature', array('div' => 'col-md-12', 'class' => 'input form-control', 'label' => 'Signature')); ?>
</div>

<?php echo $this->Form->input('RFEForm.agreement_checkbox', array('type' => 'checkbox', 'label' => '<span style="color: #f00; font-weight: bold;">Please type name above and check this box to confirm that this electronically-submitted document is a binding agreement without the actual signature of the Chief Executive Officer.</span>')); ?>
	</div>
</div>
<?php endif; ?>



<?php if ($step == 9): ?>
<div class="panel panel-default">
	<div class="panel-heading"></div>
	<div class="panel-body">
<h2 style="margin-top: 0;">Checklist</h2>
<div class="row form-group">
	<div class="col-md-12">
<?php echo $this->Form->input('RFEForm.checklist_1', array('type' => 'checkbox', 'label' => 'All sections that apply are completed.')); ?>
<?php echo $this->Form->input('RFEForm.checklist_2', array('type' => 'checkbox', 'label' => 'CEO signature or electronic signature box is checked as confirmation of Certification of Compliance and Engagement Agreement')); ?>
	</div>
</div>
<div class="row form-group">
	<?php echo $this->Form->input('RFEForm.engagement_fee', array('div' => 'col-md-12', 'class' => 'input form-control', 'label' => 'Engagement Fee of $2,500 <b>(NON-REFUNDABLE)</b>', 'options' => array('' => 'Please select how you will pay...', 'check' => 'Pay by check', 'paypal' => 'Pay by credit card via PayPal', 'credit' => 'Pay by credit card'))); ?>
</div>

<h2 style="margin-top: 26px;">Next Steps</h2>
<p style="margin-bottom: 0; margin-top: 0;">You will receive a Letter of Engagement with the onsite dates and cost of accreditation along with a Preparation Packet once the request has been processed.</p>
	</div>
</div>
<?php endif; ?>






<?php if ($step > 1): ?>Please use your browser's <b>Back</b> button to go back to a previous page.<?php endif; ?>
<?php if ($step < 9): ?><button type="submit" class="btn btn-cql pull-right">Next <i class="fa fa-angle-right"></i></button><?php endif; ?>
<?php if ($step == 9): ?><button type="submit" class="btn btn-cql pull-right">Submit <i class="fa fa-check"></i></button><?php endif; ?>

<?php echo $this->Form->hidden('step', array('value' => $step)); ?>
<?php echo $this->Form->hidden('formData', array('value' => $formData)); ?>
</form>

</article>