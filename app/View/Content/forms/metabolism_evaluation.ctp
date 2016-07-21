<article>
<h1>Metabolism Evaluation</h1>
<form method="post" action="/contact">	
	<?php echo $this->Form->input('MetabolismEvaluationForm.name', array('size' => '60', 'label' => 'Name')); ?>
	<?php echo $this->Form->input('MetabolismEvaluationForm.email', array('div' => 'input inline', 'size' => '60', 'label' => 'Email')); ?>
	<?php echo $this->Form->input('MetabolismEvaluationForm.phone_number', array('div' => 'input lastinline', 'size' => '60', 'label' => 'Phone Number')); ?>
	<?php echo $this->Form->input('MetabolismEvaluationForm.best_time_to_call', array('size' => '60', 'label' => 'What is the best time to call you?')); ?>
	<?php echo $this->Form->input('MetabolismEvaluationForm.how_did_you_hear_about_us', array('size' => '60', 'label' => 'How did you hear about Thin &amp; Healthy Total Solutions?')); ?>
	<?php echo $this->Form->input('MetabolismEvaluationForm.are_you_a_current_member', array('options' => array('Yes' => 'Yes', 'No' => 'No'), 'label' => 'Are you a current member of The Arena Club?')); ?>

	<p>Please rank in order from 1-3 the level of importance in obtaining fitness goals:</p>
	<?php echo $this->Form->input('MetabolismEvaluationForm.lose_weight', array('size' => '1', 'div' => 'input style2', 'format' => array('before', 'input', 'between', 'label', 'after', 'error'))); ?>
	<?php echo $this->Form->input('MetabolismEvaluationForm.build_add_muscle', array('size' => '1', 'div' => 'input style2', 'format' => array('before', 'input', 'between', 'label', 'after', 'error'), 'label' => 'Build/Add muscle')); ?>
	<?php echo $this->Form->input('MetabolismEvaluationForm.wear_smaller_clothing', array('size' => '1', 'div' => 'input style2', 'format' => array('before', 'input', 'between', 'label', 'after', 'error'), 'label' => 'Wear smaller clothing (firm/tone)')); ?>
	<?php echo $this->Form->submit(); ?>
</form>
</article>