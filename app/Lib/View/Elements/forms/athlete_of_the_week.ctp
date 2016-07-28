<?php echo $this->Form->input('FormData.first_name', array('div' => 'input inline', 'size' => '60', 'label' => 'Athlete\'s First Name')); ?>
<?php echo $this->Form->input('FormData.last_name', array('div' => 'input lastinline', 'size' => '60', 'label' => 'Athlete\'s Last Name')); ?>
<?php echo $this->Form->input('FormData.high_school_or_team', array('div' => 'input inline', 'size' => '60', 'label' => 'High School or Team Affiliation')); ?>
<?php echo $this->Form->input('FormData.grade', array('div' => 'input lastinline', 'size' => '60', 'label' => 'Grade')); ?>
<?php echo $this->Form->input('FormData.sport', array('div' => 'input inline', 'size' => '60', 'label' => 'Sport')); ?>
<?php echo $this->Form->input('FormData.level', array('div' => 'select lastinline', 'label' => 'Level', 'options' => array('' => 'Please select...', 'JV' =>  'JV', 'Varisty' => 'Varsity'))); ?>

<?php echo $this->Form->input('FormData.parent_name', array('div' => 'input inline', 'size' => '60', 'label' => 'Parent Name')); ?>
<?php echo $this->Form->input('FormData.parent_phone', array('div' => 'input lastinline', 'size' => '60', 'label' => 'Parent Phone Nmber')); ?>
<?php echo $this->Form->input('FormData.coach_name', array('div' => 'input inline', 'size' => '60', 'label' => 'Coach Name')); ?>
<?php echo $this->Form->input('FormData.coach_phone', array('div' => 'input lastinline', 'size' => '60', 'label' => 'Coach Phone Number')); ?>

<?php echo $this->Form->input('FormData.reason_for_nomnation', array('rows' => '6', 'label' => 'Reason for Nomination / Recent Accomplishment')); ?>