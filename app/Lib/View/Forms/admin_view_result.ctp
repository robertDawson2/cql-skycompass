<?php $this->set('title_for_layout', 'View Details'); ?>

<?php
	$data = unserialize($result['FormResult']['data']);
	foreach ($data as $field => $value) {
		if (!in_array($field, array('data'))) {
			if (is_array($value)) {
				echo '<p><b>' . Inflector::humanize(Inflector::underscore($field)) . '</b>';
				foreach ($value as $f => $v)
					echo '<br />' . Inflector::humanize(Inflector::underscore($f)) . ': ' . ($v == 1 ? 'Yes' : 'No');
				echo '</p>';
			} else {
				echo '<p><b>' . Inflector::humanize(Inflector::underscore($field)) . '</b><br />' . (!empty($value) ? $value : '(blank)') . '</p>';
			}
		}
	}
?>