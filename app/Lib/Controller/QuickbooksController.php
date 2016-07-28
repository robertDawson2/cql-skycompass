<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
     require_once(APP . 'Vendor' . DS . 'QuickBooks/QuickBooks.php');
    //require_once('QuickBooks/QuickBooks.php');
 
    
    /**
 * Configuration parameter for the quickbooks_config table, used to keep track of the last time the QuickBooks sync ran
 */
define('QB_QUICKBOOKS_CONFIG_LAST', 'last');

/**
 * Configuration parameter for the quickbooks_config table, used to keep track of the timestamp for the current iterator
 */
define('QB_QUICKBOOKS_CONFIG_CURR', 'curr');

/**
 * Maximum number of customers/invoices returned at a time when doing the import
 */
define('QB_QUICKBOOKS_MAX_RETURNED', 10);

/**
 * 
 */
//define('QB_PRIORITY_PURCHASEORDER', 4);

/**
 * Request priorities, items sync first
 */
//define('QB_PRIORITY_ITEM', 3);

/**
 * Request priorities, customers
 */
define('QB_PRIORITY_CUSTOMER', 2);

/**
 * Request priorities, salesorders
 */
//define('QB_PRIORITY_SALESORDER', 1);

/**
 * Request priorities, invoices last... 
 */
//define('QB_PRIORITY_INVOICE', 0);

/**
 * Send error notices to this e-mail address
 */
define('QB_QUICKBOOKS_MAILTO', 'bobby@net2sky.com');

// The next three parameters, $map, $errmap, and $hooks, are callbacks which 
//	will be called when certain actions/events/requests/responses occur within 
//	the framework.
    

    class QuickbooksController extends AppController {

        public $uses = array('Customer', 'CustomerAddress', 'Company');
         
         
        public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('import');
        }
        public function import($hash = null)
        {
            if($hash != "asdn")
           {
               exit('incorrect hash has been supplied');
           }
           
              
    $user = 'quickbooks';
    $pass = 'password';
           
            $map = array(
                QUICKBOOKS_IMPORT_CUSTOMER => array( array($this, '_quickbooks_customer_import_request'),
                    array($this, '_quickbooks_customer_import_response' )), 
	);
            

$errmap = array(
	'*' => array(array($this, '_quickbooks_error_catchall')), 
	);
	
$hooks = array(
	QuickBooks_WebConnector_Handlers::HOOK_LOGINSUCCESS => array(array($this, '_quickbooks_hook_loginsuccess')), 
	);


// Logging level
//$log_level = QUICKBOOKS_LOG_NORMAL;
//$log_level = QUICKBOOKS_LOG_VERBOSE;
//$log_level = QUICKBOOKS_LOG_DEBUG; DEVELOP				// Use this level until you're sure everything works!!!
$log_level = QUICKBOOKS_LOG_DEBUG;

// What SOAP server you're using 
//$soapserver = QUICKBOOKS_SOAPSERVER_PHP;			// The PHP SOAP extension, see: www.php.net/soap
$soapserver = QUICKBOOKS_SOAPSERVER_BUILTIN;		// A pure-PHP SOAP server (no PHP ext/soap extension required, also makes debugging easier)

$soap_options = array(			// See http://www.php.net/soap
	);

$handler_options = array(		// See the comments in the QuickBooks/Server/Handlers.php file
	'deny_concurrent_logins' => false, 
	'deny_reallyfast_logins' => false, 
	);		

$driver_options = array(		// See the comments in the QuickBooks/Driver/<YOUR DRIVER HERE>.php file ( i.e. 'Mysql.php', etc. )
	);

$callback_options = array(
	);

if($_SERVER['SERVER_ADDR'] == '127.0.0.1')
{
    $dsn = 'mysql://cql:St8!VwARH49pW#eh3P@localhost/cql';
}
else
{
$dsn = 'mysql://cqldev:St8!VwARH49pW#eh3P@localhost/cqldev';
}
           
           // If we haven't done our one-time initialization yet, do it now!
if (!QuickBooks_Utilities::initialized($dsn))
{
	
	
	// Create the database tables
	QuickBooks_Utilities::initialize($dsn);
	
	// Add the default authentication username/password
	QuickBooks_Utilities::createUser($dsn, $user, $pass);
}

// Initialize the queue
QuickBooks_WebConnector_Queue_Singleton::initialize($dsn);


// Create a new server and tell it to handle the requests
// __construct($dsn_or_conn, $map, $errmap = array(), $hooks = array(), $log_level = QUICKBOOKS_LOG_NORMAL, $soap = QUICKBOOKS_SOAPSERVER_PHP, $wsdl = QUICKBOOKS_WSDL, $soap_options = array(), $handler_options = array(), $driver_options = array(), $callback_options = array()
$Server = new QuickBooks_WebConnector_Server($dsn, $map, $errmap, $hooks, $log_level, $soapserver, QUICKBOOKS_WSDL, $soap_options, $handler_options, $driver_options, $callback_options);
$response = $Server->handle(true, true);


exit();
        }
        
        
        public function _quickbooks_hook_loginsuccess($requestID, $user, $hook, &$err, $hook_data, $callback_config)
{
            
          
	// For new users, we need to set up a few things
	// Fetch the queue instance
	$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();
	$date = '1983-01-02 12:01:01';
	
	// Set up the invoice imports
	if (!$this->_quickbooks_get_last_run($user, QUICKBOOKS_IMPORT_INVOICE))
	{
		// And write the initial sync time
		$this->_quickbooks_set_last_run($user, QUICKBOOKS_IMPORT_INVOICE, $date);
	}
	
	// Do the same for customers
	if (!$this->_quickbooks_get_last_run($user, QUICKBOOKS_IMPORT_CUSTOMER))
	{
		$this->_quickbooks_set_last_run($user, QUICKBOOKS_IMPORT_CUSTOMER, $date);
	}

	// ... and for sales orders
	if (!$this->_quickbooks_get_last_run($user, QUICKBOOKS_IMPORT_SALESORDER))
	{
		$this->_quickbooks_set_last_run($user, QUICKBOOKS_IMPORT_SALESORDER, $date);
	}
	
	// ... and for items
	if (!$this->_quickbooks_get_last_run($user, QUICKBOOKS_IMPORT_ITEM))
	{
		$this->_quickbooks_set_last_run($user, QUICKBOOKS_IMPORT_ITEM, $date);
	}
	
	// Make sure the requests get queued up
	//$Queue->enqueue(QUICKBOOKS_IMPORT_SALESORDER, 1, QB_PRIORITY_SALESORDER);
	//$Queue->enqueue(QUICKBOOKS_IMPORT_INVOICE, 1, QB_PRIORITY_INVOICE);
	//$Queue->enqueue(QUICKBOOKS_IMPORT_PURCHASEORDER, 1, QB_PRIORITY_PURCHASEORDER);
	$Queue->enqueue(QUICKBOOKS_IMPORT_CUSTOMER, 1, QB_PRIORITY_CUSTOMER);
	//$Queue->enqueue(QUICKBOOKS_IMPORT_ITEM, 1, QB_PRIORITY_ITEM);
}

/**
 * Get the last date/time the QuickBooks sync ran
 * 
 * @param string $user		The web connector username 
 * @return string			A date/time in this format: "yyyy-mm-dd hh:ii:ss"
 */
public function _quickbooks_get_last_run($user, $action)
{
	$type = null;
	$opts = null;
	return QuickBooks_Utilities::configRead(QB_QUICKBOOKS_DSN, $user, md5(__FILE__), QB_QUICKBOOKS_CONFIG_LAST . '-' . $action, $type, $opts);
}

/**
 * Set the last date/time the QuickBooks sync ran to NOW
 * 
 * @param string $user
 * @return boolean
 */
public function _quickbooks_set_last_run($user, $action, $force = null)
{
	$value = date('Y-m-d') . 'T' . date('H:i:s');
	
	if ($force)
	{
		$value = date('Y-m-d', strtotime($force)) . 'T' . date('H:i:s', strtotime($force));
	}
	
	return QuickBooks_Utilities::configWrite(QB_QUICKBOOKS_DSN, $user, md5(__FILE__), QB_QUICKBOOKS_CONFIG_LAST . '-' . $action, $value);
}

/**
 * 
 * 
 */
public function _quickbooks_get_current_run($user, $action)
{
	$type = null;
	$opts = null;
	return QuickBooks_Utilities::configRead(QB_QUICKBOOKS_DSN, $user, md5(__FILE__), QB_QUICKBOOKS_CONFIG_CURR . '-' . $action, $type, $opts);	
}

/**
 * 
 * 
 */
public function _quickbooks_set_current_run($user, $action, $force = null)
{
	$value = date('Y-m-d') . 'T' . date('H:i:s');
	
	if ($force)
	{
		$value = date('Y-m-d', strtotime($force)) . 'T' . date('H:i:s', strtotime($force));
	}
	
	return QuickBooks_Utilities::configWrite(QB_QUICKBOOKS_DSN, $user, md5(__FILE__), QB_QUICKBOOKS_CONFIG_CURR . '-' . $action, $value);	
}

/**
 * Build a request to import customers already in QuickBooks into our application
 */
public function _quickbooks_customer_import_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	// Iterator support (break the result set into small chunks)
	$attr_iteratorID = '';
	$attr_iterator = ' iterator="Start" ';
	if (empty($extra['iteratorID']))
	{
		// This is the first request in a new batch
		$last = $this->_quickbooks_get_last_run($user, $action);
		$this->_quickbooks_set_last_run($user, $action);			// Update the last run time to NOW()
		
		// Set the current run to $last
		$this->_quickbooks_set_current_run($user, $action, $last);
	}
	else
	{
		// This is a continuation of a batch
		$attr_iteratorID = ' iteratorID="' . $extra['iteratorID'] . '" ';
		$attr_iterator = ' iterator="Continue" ';
		
		$last = $this->_quickbooks_get_current_run($user, $action);
	}
	
	// Build the request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="' . $version . '"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<CustomerQueryRq ' . $attr_iterator . ' ' . $attr_iteratorID . ' requestID="' . $requestID . '">
					<MaxReturned>' . QB_QUICKBOOKS_MAX_RETURNED . '</MaxReturned>
					<FromModifiedDate>' . $last . '</FromModifiedDate>
					<OwnerID>0</OwnerID>
				</CustomerQueryRq>	
			</QBXMLMsgsRq>
		</QBXML>';
		
        echo $xml; exit();
       
	return $xml;
        
}

/** 
 * Handle a response from QuickBooks 
 */
public function _quickbooks_customer_import_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	if (!empty($idents['iteratorRemainingCount']))
	{
		// Queue up another request
		
		$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();
		$Queue->enqueue(QUICKBOOKS_IMPORT_CUSTOMER, null, QB_PRIORITY_CUSTOMER, array( 'iteratorID' => $idents['iteratorID'] ));
	}
	
	// This piece of the response from QuickBooks is now stored in $xml. You 
	//	can process the qbXML response in $xml in any way you like. Save it to 
	//	a file, stuff it in a database, parse it and stuff the records in a 
	//	database, etc. etc. etc. 
	//	
	// The following example shows how to use the built-in XML parser to parse 
	//	the response and stuff it into a database. 
	
	// Import all of the records
	$errnum = 0;
	$errmsg = '';
	$Parser = new QuickBooks_XML_Parser($xml);
	if ($Doc = $Parser->parse($errnum, $errmsg))
	{
		$Root = $Doc->getRoot();
		$List = $Root->getChildAt('QBXML/QBXMLMsgsRs/CustomerQueryRs');
		
		foreach ($List->children() as $Customer)
		{
			$arr = array('Customer' => array(
				'list_id' => $Customer->getChildDataAt('CustomerRet ListID'),
				'created' => $Customer->getChildDataAt('CustomerRet TimeCreated'),
				'modified' => $Customer->getChildDataAt('CustomerRet TimeModified'),
				'name' => $Customer->getChildDataAt('CustomerRet Name'),
				'full_name' => $Customer->getChildDataAt('CustomerRet FullName'),
				'first_name' => $Customer->getChildDataAt('CustomerRet FirstName'),
				'middle_name' => $Customer->getChildDataAt('CustomerRet MiddleName'),
				'last_name' => $Customer->getChildDataAt('CustomerRet LastName'),
				'contact' => $Customer->getChildDataAt('CustomerRet Contact'),
                            'CustomerAddress' => array(0 => 
                                array(
                                'address_type' => 'shipping',
                
				'addr1' => $Customer->getChildDataAt('CustomerRet ShipAddress Addr1'),
				'addr2' => $Customer->getChildDataAt('CustomerRet ShipAddress Addr2'),
                                'addr3' => $Customer->getChildDataAt('CustomerRet ShipAddress Addr3'),
				'addr4' => $Customer->getChildDataAt('CustomerRet ShipAddress Addr4'),
				'city' => $Customer->getChildDataAt('CustomerRet ShipAddress City'),
				'state' => $Customer->getChildDataAt('CustomerRet ShipAddress State'),
				'postal_code' => $Customer->getChildDataAt('CustomerRet ShipAddress PostalCode'),
                                'note' => $Customer->getChildDataAt('CustomerRet ShipAddress Note'),
				))
                                
                            ));
                        
                        
			
			QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'Importing customer ' . $arr['FullName'] . ': ' . print_r($arr, true));
			
//			foreach ($arr as $key => $value)
//			{
//				$arr[$key] = mysql_real_escape_string($value);
//			}
//			
                        // Store the invoices in MySQL
			$this->Customer->create();
                        $this->Customer->saveAssociated($arr);
                        
                        return true;
		}
	}
	
	return true;
}


public function _quickbooks_error_catchall($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg)
{
	$message = '';
	$message .= 'Request ID: ' . $requestID . "\r\n";
	$message .= 'User: ' . $user . "\r\n";
	$message .= 'Action: ' . $action . "\r\n";
	$message .= 'ID: ' . $ID . "\r\n";
	$message .= 'Extra: ' . print_r($extra, true) . "\r\n";
	//$message .= 'Error: ' . $err . "\r\n";
	$message .= 'Error number: ' . $errnum . "\r\n";
	$message .= 'Error message: ' . $errmsg . "\r\n";
	
	mail(QB_QUICKBOOKS_MAILTO, 
		'QuickBooks error occured!', 
		$message);
        return false;
}

    }
    
    ?>