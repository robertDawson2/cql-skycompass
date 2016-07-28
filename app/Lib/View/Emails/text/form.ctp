<?php
	foreach ($data as $field => $value) {
		if (!in_array($field, array('data'))) {
			if (is_array($value)) {
				echo Inflector::humanize(Inflector::underscore($field)) . PHP_EOL;
				foreach ($value as $f => $v)
					echo PHP_EOL . $v;
				echo PHP_EOL . PHP_EOL;
			} else {
				echo Inflector::humanize(Inflector::underscore($field)) . PHP_EOL . (!empty($value) ? $value : '(blank)') . PHP_EOL . PHP_EOL;
			}
		}
	}
?>

Date Submitted
<?php echo date('F j, Y \a\t g:i a'); ?>