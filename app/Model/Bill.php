<?php

class Bill extends AppModel {
		
    public $name = 'Bill';
    public $useTable = 'bills';
	
    public $hasMany = array('BillExpense', 'BillItem');
    
    
    
    public $belongsTo = array('Vendor', 'Customer');


}

?>