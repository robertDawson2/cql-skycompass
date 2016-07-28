<?php
	echo (!empty($person['prefix']) ? $person['prefix'] . ' ' : '') . $person['first_name'] . ' ' . (!empty($person['middle_initial']) ? $person['middle_initial'] . '. ' : '') . $person['last_name'] . (!empty($person['suffix']) ? ', ' . $person['suffix'] : '');
?>