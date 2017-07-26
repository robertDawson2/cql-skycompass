<?php

class Content extends AppModel {
		
    public $name = 'Content';
    public $useTable = 'content';
	public $actsAs = array('Tree');

	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val['Content']['title']) && isset($val['Content']['link']) && !empty($val['Content']['link'])) {
				if (substr($val['Content']['link'], -4) == '.pdf')
					$results[$key]['Content']['title'] = $results[$key]['Content']['title'] . ' (PDF)';
			}
		}
		return $results;
	}

}

?>