<h3>Your Information</h3>
<?php echo $this->Form->input('FormData.first_name', array('div' => 'input inline', 'size' => '60', 'label' => 'First Name')); ?>
<?php echo $this->Form->input('FormData.last_name', array('div' => 'input lastinline', 'size' => '60', 'label' => 'Last Name')); ?>
<?php echo $this->Form->input('FormData.email', array('div' => 'input inline', 'size' => '60', 'label' => 'Email')); ?>
<?php echo $this->Form->input('FormData.phone_number', array('div' => 'input lastinline', 'size' => '60', 'label' => 'Phone Number')); ?>

<?php echo $this->Form->input('FormData.day_to_come_in', array('div' => 'select inline', 'options' => array('' => 'Please select...', 'Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday'), 'label' => 'What day of the week is most convenient for you to come in?')); ?>

<?php echo $this->Form->input('FormData.time_to_come_in', array('div' => 'time-dropdown select lastinline', 'options' => array('' => 'Please select...', 'Morning' => 'Morning', 'Afternoon' => 'Afternoon', 'Evening'), 'label' => 'What time is most convenient for you to come in?')); ?>

<?php echo $this->Form->input('FormData.how_did_you_hear_about_us', array('size' => '60', 'label' => 'How did you hear about us?')); ?>

<h3 style="margin-top: 28px;">What are you interested in?</h3>
<div class="row">
	<div class="col-md-4">
		<?php echo $this->Form->input('FormData.interests.weight_loss', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.loss_in_inches', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.stress_reduction', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.better_overall_health', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.improved_sleep', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.better_movement', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.increased_energy', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.family_activities', array('type' => 'checkbox')); ?>
	</div>
	<div class="col-md-4">
		<?php echo $this->Form->input('FormData.interests.muscle_weight_gain', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.variety_of_fitness_recreation', array('type' => 'checkbox', 'label' => 'Variety of fitness/recreation')); ?>
		<?php echo $this->Form->input('FormData.interests.fun_classes', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.sports_training', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.kids_programs', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.toning_firming', array('type' => 'checkbox', 'label' => 'Toning/firming')); ?>
		<?php echo $this->Form->input('FormData.interests.meet_new_friends', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.pools', array('type' => 'checkbox')); ?>
	</div>
	<div class="col-md-4">
		<?php echo $this->Form->input('FormData.interests.medical_reasons', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.increase_bone_density', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.lower_blood_pressure', array('type' => 'checkbox',)); ?>
		<?php echo $this->Form->input('FormData.interests.lower_cholesterol', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.post_surgical', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.cardivoascular_health', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.eliminate_medications', array('type' => 'checkbox')); ?>
		<?php echo $this->Form->input('FormData.interests.therapy', array('type' => 'checkbox')); ?>
	</div>
</div>