<article>
<h1>Membership Options</h1>
<p>Are you ready to learn more? Fill out this form so we can tell you more abhout our various membership options.</p>
<form method="post" action="/contact">
	<h3>Your Information</h3>
	<?php echo $this->Form->input('MembershipForm.name', array('size' => '60', 'label' => 'Name')); ?>
	<?php echo $this->Form->input('MembershipForm.phone_number', array('div' => 'input inline', 'size' => '60', 'label' => 'Phone Number')); ?>
	<?php echo $this->Form->input('MembershipForm.email', array('div' => 'input lastinline', 'size' => '60', 'label' => 'Email')); ?>
	<?php echo $this->Form->input('MembershipForm.best_time_to_call', array('size' => '60', 'label' => 'What is the best time to call you?')); ?>
	<?php echo $this->Form->input('MembershipForm.how_did_you_hear_about_us', array('size' => '60', 'label' => 'How did you hear about us?')); ?>
	<?php echo $this->Form->input('MembershipForm.schedule_fitness_planning_appointment', array('options' => array('Yes' => 'Yes', 'No' => 'No'), 'label' => 'Would you like to schedule a FREE fitness planning appointment?')); ?>

	<h3 style="margin-top: 28px;">What are you interested in?</h3>
	<div class="row">
		<div class="col-md-4">
			<?php echo $this->Form->input('MembershipForm.interests.weight_loss', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.loss_in_inches', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.stress_reduction', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.better_overall_health', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.improved_sleep', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.better_movement', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.increased_energy', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.family_activities', array('type' => 'checkbox')); ?>
		</div>
		<div class="col-md-4">
			<?php echo $this->Form->input('MembershipForm.interests.muscle_weight_gain', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.variety_of_fitness_recreation', array('type' => 'checkbox', 'label' => 'Variety of fitness/recreation')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.fun_classes', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.sports_training', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.kids_programs', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.toning_firming', array('type' => 'checkbox', 'label' => 'Toning/firming')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.meet_new_friends', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.pools', array('type' => 'checkbox')); ?>
		</div>
		<div class="col-md-4">
			<?php echo $this->Form->input('MembershipForm.interests.medical_reasons', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.increase_bone_density', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.lower_blood_pressure', array('type' => 'checkbox',)); ?>
			<?php echo $this->Form->input('MembershipForm.interests.lower_cholesterol', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.post_surgical', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.cardivoascular_health', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.eliminate_medications', array('type' => 'checkbox')); ?>
			<?php echo $this->Form->input('MembershipForm.interests.therapy', array('type' => 'checkbox')); ?>
		</div>
	</div>

	<?php echo $this->Form->submit(); ?>
</form>
</article>