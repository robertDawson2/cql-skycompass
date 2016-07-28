<?php echo $this->Form->input('FormData.first_name', array('div' => 'input inline', 'size' => '60', 'label' => 'First Name')); ?>
<?php echo $this->Form->input('FormData.last_name', array('div' => 'input lastinline', 'size' => '60', 'label' => 'Last Name')); ?>
<?php echo $this->Form->input('FormData.email', array('div' => 'input inline', 'size' => '60', 'label' => 'Email')); ?>
<?php echo $this->Form->input('FormData.phone_number', array('div' => 'input lastinline', 'size' => '60', 'label' => 'Phone Number')); ?>
<?php echo $this->Form->input('FormData.best_time_to_call', array('size' => '60', 'label' => 'What is the best time to call you?')); ?>
<?php echo $this->Form->input('FormData.how_did_you_hear_about_us', array('size' => '60', 'label' => 'How did you hear about Thin &amp; Healthy Total Solutions?')); ?>
<?php echo $this->Form->input('FormData.are_you_a_current_member', array('options' => array('' => 'Please select...', 'Yes' => 'Yes', 'No' => 'No'), 'label' => 'Are you a current member of The Arena Club?')); ?>

<p>Please rank in order from 1-3 the level of importance in obtaining fitness goals:</p>
<?php echo $this->Form->input('FormData.lose_weight', array('size' => '1', 'div' => 'input style2', 'format' => array('before', 'input', 'between', 'label', 'after', 'error'))); ?>
<?php echo $this->Form->input('FormData.build_add_muscle', array('size' => '1', 'div' => 'input style2', 'format' => array('before', 'input', 'between', 'label', 'after', 'error'), 'label' => 'Build/Add muscle')); ?>
<?php echo $this->Form->input('FormData.wear_smaller_clothing', array('size' => '1', 'div' => 'input style2', 'format' => array('before', 'input', 'between', 'label', 'after', 'error'), 'label' => 'Wear smaller clothing (firm/tone)')); ?>