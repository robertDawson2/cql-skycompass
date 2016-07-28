<?php
	$i = 1;
	foreach ($form as $field) {
		echo '<div class="row form-group">';
		if ($field['field'] == 'input' || $field['field'] == 'date' || $field['field'] == 'time')
			echo $this->Form->input('Form.' . $i, array('div' => 'col-md-6', 'label' => $field['label'] . ($field['required'] ? ' <span style="color: #c00;">*</span>' : ''), 'class' => 'input form-control'));
		if ($field['field'] == 'multi')
			echo $this->Form->input('Form.' . $i, array('div' => 'col-md-6', 'rows' => '6', 'label' => $field['label'] . ($field['required'] ? ' <span style="color: #c00;">*</span>' : ''), 'class' => 'textarea form-control'));
		if ($field['field'] == 'dropdown')
			echo $this->Form->input('Form.' . $i, array('div' => 'col-md-6', 'options' => array('' => 'Please select...') + explode("\n", $field['options']), 'label' => $field['label'] . ($field['required'] ? ' <span style="color: #c00;">*</span>' : ''), 'class' => 'input form-control'));
		if ($field['field'] == 'checkbox')
			echo $this->Form->input('Form.' . $i, array('div' => 'col-md-6 checkbox', 'type' => 'checkbox', 'label' => $field['label'] . ($field['required'] ? ' <span style="color: #c00;">*</span>' : ''), 'class' => 'checkbox form-control'));
			
		echo '</div>';
		$i++;
	}
	echo $this->Form->submit();

?>