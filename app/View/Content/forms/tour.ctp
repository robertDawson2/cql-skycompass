<?php $this->start('jquery-scripts'); ?>
	$("#ScheduleTourFormDayToComeIn").on('change', function() {
		var day = $(this).val();
		$(".time-dropdown").hide();
		if (day == 'Friday')
			$("#ScheduleTourFormTimeToComeInFri").parent().show();
		else if (day == 'Saturday' || day == 'Sunday')
			$("#ScheduleTourFormTimeToComeInSatSun").parent().show();
		else
			$("#ScheduleTourFormTimeToComeInMonThurs").parent().show();
	});
	$("#ScheduleTourFormDayToComeIn").change();
<?php $this->end(); ?>

<article>
<h1>Schedule a Tour</h1>
<p>Let us know when you'd like to visit!</p>
<form method="post" action="/contact">
	<h3>Your Information</h3>
	<?php echo $this->Form->input('ScheduleTourForm.name', array('size' => '60', 'label' => 'Name')); ?>
	<?php echo $this->Form->input('ScheduleTourForm.email', array('div' => 'input inline', 'size' => '60', 'label' => 'Email')); ?>
	<?php echo $this->Form->input('ScheduleTourForm.phone_number', array('div' => 'input lastinline', 'size' => '60', 'label' => 'Phone Number')); ?>
	<?php echo $this->Form->input('ScheduleTourForm.how_did_you_hear_about_us', array('size' => '60', 'label' => 'How did you hear about us?')); ?>

	<?php echo $this->Form->input('ScheduleTourForm.day_to_come_in', array('div' => 'select inline', 'options' => array('Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday'), 'label' => 'What day of the week is most convenient for you to come in?')); ?>

	<?php echo $this->Form->input('ScheduleTourForm.time_to_come_in_mon_thurs', array('div' => 'time-dropdown select lastinline', 'options' => array('8am-12pm' => '8am-12pm', '12pm-4pm' => '12pm-4pm', '4pm-8pm' => '4pm-8pm'), 'label' => '<br>What time?')); ?>
	<?php echo $this->Form->input('ScheduleTourForm.time_to_come_in_fri', array('div' => array('class' => 'time-dropdown select lastinline', 'style' => 'display: none;'), 'options' => array('8am-12pm' => '8am-12pm', '12pm-4pm' => '12pm-4pm', '4pm-7pm' => '4pm-7pm'), 'label' => '<br>What time?')); ?>
	<?php echo $this->Form->input('ScheduleTourForm.time_to_come_in_sat_sun', array('div' => array('class' => 'time-dropdown select lastinline', 'style' => 'display: none;'), 'options' => array('9am-12pm' => '9am-12pm', '12pm-4pm' => '12pm-4pm'), 'label' => '<br>What time?')); ?>

	<?php echo $this->Form->input('ScheduleTourForm.schedule_fitness_planning_appointment', array('options' => array('Yes' => 'Yes', 'No' => 'No'), 'label' => 'Would you like to schedule a FREE fitness planning appointment?')); ?>

	<h3 style="margin-top: 28px;">What are you interested in?</h3>
	<div class="row">
		<div class="col-md-4">
			<?php echo $this->Form->input('ScheduleTourForm.interests.weight_loss', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.loss_in_inches', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.stress_reduction', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.better_overall_health', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.improved_sleep', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.better_movement', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.increased_energy', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.family_activities', array('type' => 'checkbox')); ?>
		</div>
		<div class="col-md-4">
			<?php echo $this->Form->input('ScheduleTourForm.interests.muscle_weight_gain', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.variety_of_fitness_recreation', array('type' => 'checkbox', 'label' => 'Variety of fitness/recreation')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.fun_classes', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.sports_training', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.kids_programs', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.toning_firming', array('type' => 'checkbox', 'label' => 'Toning/firming')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.meet_new_friends', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.pools', array('type' => 'checkbox')); ?>
		</div>
		<div class="col-md-4">
			<?php echo $this->Form->input('ScheduleTourForm.interests.medical_reasons', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.increase_bone_density', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.lower_blood_pressure', array('type' => 'checkbox',)); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.lower_cholesterol', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.post_surgical', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.cardivoascular_health', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.eliminate_medications', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('ScheduleTourForm.interests.therapy', array('type' => 'checkbox')); ?>
		</div>
	</div>

	<?php echo $this->Form->submit(); ?>
</form>
</article>