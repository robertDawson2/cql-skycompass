<?php echo $this->Form->input('FormData.first_name', array('div' => 'input inline', 'size' => '60', 'label' => 'First Name')); ?>
<?php echo $this->Form->input('FormData.last_name', array('div' => 'input lastinline', 'size' => '60', 'label' => 'Last Name')); ?>
<?php echo $this->Form->input('FormData.email', array('div' => 'input inline', 'size' => '60', 'label' => 'Email')); ?>
<?php echo $this->Form->input('FormData.phone_number', array('div' => 'input lastinline', 'size' => '60', 'label' => 'Phone Number')); ?>

<?php echo $this->Form->input('FormData.age', array('div' => 'input inline', 'size' => '60', 'label' => 'Age')); ?>
<?php echo $this->Form->input('FormData.gender', array('div' => 'select lastinline', 'label' => 'Gender', 'options' => array('' => 'Please select...', 'Male' => 'Male', 'Female' => 'Female'))); ?>

<?php echo $this->Form->input('FormData.school_or_association', array('div' => 'input inline', 'size' => '60', 'label' => 'School and/or Association')); ?>
<?php echo $this->Form->input('FormData.sports_played', array('div' => 'input lastinline', 'size' => '60', 'label' => 'Sports Played')); ?>

<h3 style="margin-top: 28px;">Interested in improving:</h3>
<?php echo $this->Form->input('FormData.interests.speed', array('type' => 'checkbox')); ?>
<?php echo $this->Form->input('FormData.interests.agility', array('type' => 'checkbox')); ?>
<?php echo $this->Form->input('FormData.interests.conditioning', array('type' => 'checkbox')); ?>
<?php echo $this->Form->input('FormData.interests.strength', array('type' => 'checkbox')); ?>
<?php echo $this->Form->input('FormData.interests.quickness', array('type' => 'checkbox')); ?>
<?php echo $this->Form->input('FormData.interests.confidence', array('type' => 'checkbox')); ?>

<?php echo $this->Form->input('FormData.goals', array('cols' => '60', 'rows' => '4', 'label' => 'Goals:')); ?>