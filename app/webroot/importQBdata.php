<?php

/**
 * Example QuickBooks SOAP Server / Web Service
 * 
 * This is an example Web Service which imports Invoices currently stored 
 * within QuickBooks desktop editions and then stores those invoices in a MySQL 
 * database. It communicates with QuickBooks via the QuickBooks Web Connector.  
 * 
 * If you have not already looked at the more basic docs/example_server.php, 
 * you may want to consider looking at that example before you dive into this 
 * example, as the requests and processing are a bit simpler and the 
 * documentation a bit more verbose.
 * 
 * 
 * @author Keith Palmer <keith@consolibyte.com>
 * 
 * @package QuickBooks
 * @subpackage Documentation
 */

// I always program in E_STRICT error mode... 
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// Support URL
if (!empty($_GET['support']))
{
	header('Location: http://www.consolibyte.com/');
	exit;
}

// We need to make sure the correct timezone is set, or some PHP installations will complain
if (function_exists('date_default_timezone_set'))
{
	// * MAKE SURE YOU SET THIS TO THE CORRECT TIMEZONE! *
	// List of valid timezones is here: http://us3.php.net/manual/en/timezones.php
	date_default_timezone_set('America/New_York');
}

// Require the framework
require_once 'QuickBooks/QuickBooks.php';

// A username and password you'll use in: 
//	a) Your .QWC file
//	b) The Web Connector
//	c) The QuickBooks framework
//
// 	NOTE: This has *no relationship* with QuickBooks usernames, Windows usernames, etc. 
// 		It is *only* used for the Web Connector and SOAP server! 
// 
// If you wanted to allow others to log in, you'd create a .QWC file for each 
//	individual user, and add each individual user to the auth database with the 
//	QuickBooks_Utilities::createUser($dsn, $username, $password); static method.
$user = 'quickbooks';
$pass = 'password';

/**
 * Configuration parameter for the quickbooks_config table, used to keep track of the last time the QuickBooks sync ran
 */
define('QB_QUICKBOOKS_CONFIG_LAST', 'last');

/**
 * Configuration parameter for the quickbooks_config table, used to keep track of the timestamp for the current iterator
 */
define('QB_QUICKBOOKS_CONFIG_CURR', 'curr');

// must do way early to make sure that new records are then added when import is run later

define('QB_PRIORITY_TIMETRACKING_ADD', 100);
define('QB_PRIORITY_BILL_ADD', 90);
/**
 * Maximum number of customers/invoices returned at a time when doing the import
 */
define('QB_QUICKBOOKS_MAX_RETURNED', 10);

/**
 * 
 */
define('QB_PRIORITY_EMPLOYEE', 6);
/**
 * 
 */
define('QB_PRIORITY_BILL', 20);

/**
 * 
 */
define('QB_PRIORITY_VENDOR', 8);

/**
 * 
 */
define('QB_PRIORITY_PURCHASEORDER', 4);

/**
 * Request priorities, items sync first
 */
define('QB_PRIORITY_ITEM', 3);

/**
 * Request priorities, customers
 */
define('QB_PRIORITY_CUSTOMER', 2);

/**
 * Request priorities, salesorders
 */
define('QB_PRIORITY_SALESORDER', 1);

/**
 * Request priorities, invoices last... 
 */
define('QB_PRIORITY_INVOICE', 0);

/**
 * Request priorities, invoices last... 
 */
define('QB_PRIORITY_TIMETRACKING', 0);

/**
 * Request priorities, invoices last... 
 */
define('QB_PRIORITY_CLASS', 0);

/**
 * Request priorities, invoices last... 
 */
define('QB_PRIORITY_PAYROLLITEMWAGE', 0);

/**
 * Send error notices to this e-mail address
 */
define('QB_QUICKBOOKS_MAILTO', 'bobby@net2sky.com');

// The next three parameters, $map, $errmap, and $hooks, are callbacks which 
//	will be called when certain actions/events/requests/responses occur within 
//	the framework.

// Map QuickBooks actions to handler functions
$map = array(
	QUICKBOOKS_IMPORT_VENDOR => array( '_quickbooks_vendor_import_request', '_quickbooks_vendor_import_response' ), 
	QUICKBOOKS_IMPORT_PURCHASEORDER => array( '_quickbooks_purchaseorder_import_request', '_quickbooks_purchaseorder_import_response' ),
	QUICKBOOKS_IMPORT_INVOICE => array( '_quickbooks_invoice_import_request', '_quickbooks_invoice_import_response' ),
	QUICKBOOKS_IMPORT_CUSTOMER => array( '_quickbooks_customer_import_request', '_quickbooks_customer_import_response' ), 
	QUICKBOOKS_IMPORT_SALESORDER => array( '_quickbooks_salesorder_import_request', '_quickbooks_salesorder_import_response' ), 
    QUICKBOOKS_IMPORT_BILL => array('_quickbooks_bill_import_request', '_quickbooks_bill_import_response' ), 
	QUICKBOOKS_IMPORT_ITEM => array( '_quickbooks_item_import_request', '_quickbooks_item_import_response' ), 
    QUICKBOOKS_IMPORT_EMPLOYEE => array( '_quickbooks_employee_import_request', '_quickbooks_employee_import_response' ), 
    QUICKBOOKS_IMPORT_TIMETRACKING => array( '_quickbooks_time_tracking_import_request', '_quickbooks_time_tracking_import_response' ), 
    QUICKBOOKS_IMPORT_CLASS => array( '_quickbooks_class_import_request', '_quickbooks_class_import_response' ), 
    QUICKBOOKS_IMPORT_PAYROLLITEMWAGE => array( '_quickbooks_payrollitem_import_request', '_quickbooks_payrollitem_import_response' ), 
    QUICKBOOKS_ADD_TIMETRACKING => array( '_quickbooks_time_add_request', '_quickbooks_time_add_response' ), 
    QUICKBOOKS_ADD_BILL => array( '_quickbooks_bill_add_request', '_quickbooks_bill_add_response' ), 
	);

// Error handlers
$errmap = array(
	500 => '_quickbooks_error_e500_notfound', 			// Catch errors caused by searching for things not present in QuickBooks
	1 => '_quickbooks_error_e500_notfound', 
	'*' => '_quickbooks_error_catchall', 				// Catch any other errors that might occur
	);

// An array of callback hooks
$hooks = array(
	QuickBooks_WebConnector_Handlers::HOOK_LOGINSUCCESS => '_quickbooks_hook_loginsuccess', 	// call this whenever a successful login occurs
	);

// Logging level
//$log_level = QUICKBOOKS_LOG_NORMAL;
//$log_level = QUICKBOOKS_LOG_VERBOSE;
//$log_level = QUICKBOOKS_LOG_DEBUG;				// Use this level until you're sure everything works!!!
$log_level = QUICKBOOKS_LOG_DEVELOP;

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

// * MAKE SURE YOU CHANGE THE DATABASE CONNECTION STRING BELOW TO A VALID MYSQL USERNAME/PASSWORD/HOSTNAME *
// 
// This assumes that:
//	- You are connecting to MySQL with the username 'root'
//	- You are connecting to MySQL with an empty password
//	- Your MySQL server is located on the same machine as the script ( i.e.: 'localhost', if it were on another machine, you might use 'other-machines-hostname.com', or '192.168.1.5', or ... etc. )
//	- Your MySQL database name containing the QuickBooks tables is named 'quickbooks' (if the tables don't exist, they'll be created for you) 
if($_SERVER['SERVER_ADDR'] == '127.0.0.1')
{
    $dsn = 'mysql://cql:St8!VwARH49pW#eh3P@localhost/cql';
}
else
{
$dsn = 'mysql://cqldev:St8!VwARH49pW#eh3P@localhost/cqldev';
}
//$dsn = 'mysql://testuser:testpassword@localhost/testdatabase';

/**
 * Constant for the connection string (because we'll use it in other places in the script)
 */
define('QB_QUICKBOOKS_DSN', $dsn);

// If we haven't done our one-time initialization yet, do it now!
if (!QuickBooks_Utilities::initialized($dsn))
{
	// Create the example tables
	$file = dirname(__FILE__) . '/example.sql';
	if (file_exists($file))
	{
		$contents = file_get_contents($file);	
		foreach (explode(';', $contents) as $sql)
		{
			if (!trim($sql))
			{
				continue;
			}
			
			mysql_query($sql) or die(trigger_error(mysql_error()));
		}
	}
	else
	{
		die('Could not locate "./example.sql" to create the demo SQL schema!');
	}
	
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

/*
// If you wanted, you could do something with $response here for debugging

$fp = fopen('/path/to/file.log', 'a+');
fwrite($fp, $response);
fclose($fp);
*/

/**
 * Login success hook - perform an action when a user logs in via the Web Connector
 *
 * 
 */
function _quickbooks_hook_loginsuccess($requestID, $user, $hook, &$err, $hook_data, $callback_config)
{
	// For new users, we need to set up a few things

	// Fetch the queue instance
	$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();
	$date = '1983-01-02 12:01:01';
	
        // Set up the invoice imports
	if (!_quickbooks_get_last_run($user, QUICKBOOKS_IMPORT_BILL))
	{
		// And write the initial sync time
		_quickbooks_set_last_run($user, QUICKBOOKS_IMPORT_BILL, $date);
	}
        
	// Set up the invoice imports
	if (!_quickbooks_get_last_run($user, QUICKBOOKS_IMPORT_INVOICE))
	{
		// And write the initial sync time
		_quickbooks_set_last_run($user, QUICKBOOKS_IMPORT_INVOICE, $date);
	}
	
	// Do the same for customers
	if (!_quickbooks_get_last_run($user, QUICKBOOKS_IMPORT_CUSTOMER))
	{
		_quickbooks_set_last_run($user, QUICKBOOKS_IMPORT_CUSTOMER, $date);
	}

	// ... and for sales orders
	if (!_quickbooks_get_last_run($user, QUICKBOOKS_IMPORT_SALESORDER))
	{
		_quickbooks_set_last_run($user, QUICKBOOKS_IMPORT_SALESORDER, $date);
	}
	
	// ... and for items
	if (!_quickbooks_get_last_run($user, QUICKBOOKS_IMPORT_ITEM))
	{
		_quickbooks_set_last_run($user, QUICKBOOKS_IMPORT_ITEM, $date);
	}
        
        // ... and for vendors
	if (!_quickbooks_get_last_run($user, QUICKBOOKS_IMPORT_VENDOR))
	{
		_quickbooks_set_last_run($user, QUICKBOOKS_IMPORT_VENDOR, $date);
	}
        
         // ... and for vendors
	if (!_quickbooks_get_last_run($user, QUICKBOOKS_IMPORT_EMPLOYEE))
	{
		_quickbooks_set_last_run($user, QUICKBOOKS_IMPORT_EMPLOYEE, $date);
	}
	
         // ... and for vendors
	if (!_quickbooks_get_last_run($user, QUICKBOOKS_IMPORT_TIMETRACKING))
	{
		_quickbooks_set_last_run($user, QUICKBOOKS_IMPORT_TIMETRACKING, $date);
	}
        
           // ... and for vendors
	if (!_quickbooks_get_last_run($user, QUICKBOOKS_IMPORT_CLASS))
	{
		_quickbooks_set_last_run($user, QUICKBOOKS_IMPORT_CLASS, $date);
	}
        
          // ... and for vendors
	if (!_quickbooks_get_last_run($user, QUICKBOOKS_IMPORT_PAYROLLITEMWAGE))
	{
		_quickbooks_set_last_run($user, QUICKBOOKS_IMPORT_PAYROLLITEMWAGE, $date);
	}
        
        if (!_quickbooks_get_last_run($user, QUICKBOOKS_ADD_TIMETRACKING))
	{
		_quickbooks_set_last_run($user, QUICKBOOKS_ADD_TIMETRACKING, $date);
	}
        
        if (!_quickbooks_get_last_run($user, QUICKBOOKS_ADD_BILL))
	{
		_quickbooks_set_last_run($user, QUICKBOOKS_ADD_BILL, $date);
	}
        
	// Make sure the requests get queued up
        
	$Queue->enqueue(QUICKBOOKS_IMPORT_SALESORDER, 1, QB_PRIORITY_SALESORDER);
	$Queue->enqueue(QUICKBOOKS_IMPORT_INVOICE, 1, QB_PRIORITY_INVOICE);
	$Queue->enqueue(QUICKBOOKS_IMPORT_PURCHASEORDER, 1, QB_PRIORITY_PURCHASEORDER);
	$Queue->enqueue(QUICKBOOKS_IMPORT_CUSTOMER, 1, QB_PRIORITY_CUSTOMER);
	$Queue->enqueue(QUICKBOOKS_IMPORT_ITEM, 1, QB_PRIORITY_ITEM);
        $Queue->enqueue(QUICKBOOKS_IMPORT_VENDOR, 1, QB_PRIORITY_VENDOR);
         $Queue->enqueue(QUICKBOOKS_IMPORT_EMPLOYEE, 1, QB_PRIORITY_EMPLOYEE);
         $Queue->enqueue(QUICKBOOKS_IMPORT_BILL, 1, QB_PRIORITY_BILL);
         $Queue->enqueue(QUICKBOOKS_IMPORT_TIMETRACKING, 1, QB_PRIORITY_TIMETRACKING);
          $Queue->enqueue(QUICKBOOKS_IMPORT_CLASS, 1, QB_PRIORITY_TIMETRACKING);
          $Queue->enqueue(QUICKBOOKS_IMPORT_PAYROLLITEMWAGE, 1, QB_PRIORITY_PAYROLLITEMWAGE);
}

/**
 * Get the last date/time the QuickBooks sync ran
 * 
 * @param string $user		The web connector username 
 * @return string			A date/time in this format: "yyyy-mm-dd hh:ii:ss"
 */
function _quickbooks_get_last_run($user, $action)
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
function _quickbooks_set_last_run($user, $action, $force = null)
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
function _quickbooks_get_current_run($user, $action)
{
	$type = null;
	$opts = null;
	return QuickBooks_Utilities::configRead(QB_QUICKBOOKS_DSN, $user, md5(__FILE__), QB_QUICKBOOKS_CONFIG_CURR . '-' . $action, $type, $opts);	
}

/**
 * 
 * 
 */
function _quickbooks_set_current_run($user, $action, $force = null)
{
	$value = date('Y-m-d') . 'T' . date('H:i:s');
	
	if ($force)
	{
		$value = date('Y-m-d', strtotime($force)) . 'T' . date('H:i:s', strtotime($force));
	}
	
	return QuickBooks_Utilities::configWrite(QB_QUICKBOOKS_DSN, $user, md5(__FILE__), QB_QUICKBOOKS_CONFIG_CURR . '-' . $action, $value);	
}

/**
 * Add any time records and then remove the old from the database so a clean record with a correct ID is imported.
 */
function _quickbooks_bill_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
    
                $xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="13.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<BillAddRq>';           
	$queryString = "SELECT Vendor.id, Vendor.name, Bill.txn_date FROM " .
                "bills as Bill " .
                "INNER JOIN vendors as Vendor on (Vendor.id = Bill.vendor_id) "
                . "WHERE Bill.id = '" . $ID . "';";
                
	 $itemsQuery = "SELECT Item.id, Item.full_name, BillItem.description, BillItem.quantity, BillItem.cost, BillItem.amount, "
                 . "Customer.id, Customer.full_name, Classes.id, Classes.full_name, BillItem.billable, BillItem.txn_date, BillItem.id FROM " .
                 "bill_items as BillItem INNER JOIN " .
                 "items as Item on (Item.id = BillItem.item_id) INNER JOIN "
                 . "customers as Customer on (Customer.id = BillItem.customer_id) INNER JOIN "
                 . "classes as Classes on (Classes.id = BillItem.class_id) "
                 . "WHERE BillItem.bill_id = '" . $ID . "';";
                        
         QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'REQUEST: ' . $requestID . " : ID : " . $ID . ' : Query String : ' . $queryString );
                        $row = mysql_fetch_array(mysql_query($queryString), MYSQL_NUM) or die(trigger_error(mysql_error()));
                        $items = mysql_query($itemsQuery) or die(trigger_error(mysql_error()));
                        
                        QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'ROW : '. print_r($row, true));
                        
                            $xml .= "<BillAdd> <!-- required -->
                                <VendorRef>
                            <ListID >".$row[0] ."</ListID> <!-- optional -->
                            <FullName >".$row[1]."</FullName> <!-- optional -->
                            </VendorRef>
                            <TxnDate >".date('Y-m-d', strtotime($row[2])) ."</TxnDate> <!-- required -->
                                ";
                            
                            while($item = mysql_fetch_array($items, MYSQL_NUM))
                            {
                                $xml .= "<ItemLineAdd>"
                                        . "<ItemRef>"
                                        . "<ListID >".$item[0]."</ListID>"
                                        . "<FullName >".$item[1]."</FullName>"
                                        . "</ItemRef>"
                                        . "<Desc >".$item[2]."\nDate: " . date("m/d/Y", strtotime($item[11])). "\n*^*^*" . $item[12] .  "</Desc>"
                                        . "<Quantity >".$item[3]."</Quantity>"
                                        . "<Cost >".$item[4]."</Cost>"
                                        . "<Amount >".$item[5]."</Amount>"
                                        . "<CustomerRef>"
                                        . "<ListID >".$item[6]."</ListID>"
                                        . "<FullName>".$item[7]."</FullName>"
                                        . "</CustomerRef>"
                                        . "<ClassRef>"
                                        . "<ListID >" . $item[8] . "</ListID>"
                                        . "<FullName >" . $item[9] . "</FullName>"
                                        . "</ClassRef>"
                                        . "<BillableStatus >" . $item[10] . "</BillableStatus>"
                                        
                                        . "</ItemLineAdd>";
                                        
                            }
                            
                            $xml .= "</BillAdd>";
                            
                          
                        
                       
                        $xml .="</BillAddRq>
			</QBXMLMsgsRq>
		</QBXML>";
	QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'BillAdd XML String : ' . $xml );
        

            return $xml;
        
       
}
/**
 * Receive a response from QuickBooks 
 */
function _quickbooks_bill_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{
    $queryString = "
		UPDATE 
			bills 
		SET 
			bills.id = '" . mysql_real_escape_string($idents['TxnID']) . "' 
		WHERE 
			bills.id = '" . $ID . "'";
    QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'QUERY : ' . $queryString . ' ::: ID : ' . $ID . ' ::: ROW : '. print_r($xml, true));
    $result = mysql_query($queryString);
    
        $errnum = 0;
	$errmsg = '';
	$Parser = new QuickBooks_XML_Parser($xml);
	if ($Doc = $Parser->parse($errnum, $errmsg))
	{
		$Root = $Doc->getRoot();
		$List = $Root->getChildAt('QBXML/QBXMLMsgsRs/BillAddRs');
		
		foreach ($List->children() as $Bill)
		{
                    $arr = array(
				'id' => $Bill->getChildDataAt('BillRet TxnID'),
                        'vendor_id' => $Bill->getChildDataAt('BillRet VendorRef ListID'),
                            );
                    
                    foreach ($Bill->children() as $Child)
			{
                        if($Child->name() == 'ItemLineRet')
                                {
                                    $ItemLine = $Child;
					
					$lineitem = array( 
						'bill_id' => $arr['id'], 
                                            'vendor_id' => $arr['vendor_id'],
						'id' => $ItemLine->getChildDataAt('ItemLineRet TxnLineID'), 
						'item_id' => $ItemLine->getChildDataAt('ItemLineRet ItemRef ListID'), 
						'amount' => $ItemLine->getChildDataAt('ItemLineRet Amount'), 
                                            'cost' => $ItemLine->getChildDataAt('ItemLineRet Cost'), 
                                            'quantity' => $ItemLine->getChildDataAt('ItemLineRet Quantity'), 
						'description' => $ItemLine->getChildDataAt('ItemLineRet Desc'), 
						'customer_id' => $ItemLine->getChildDataAt('ItemLineRet CustomerRef ListID'),
						'class_id' => $ItemLine->getChildDataAt('ItemLineRet ClassRef ListID'), 
                                                'billable' => $ItemLine->getChildDataAt('ItemLineRet BillableStatus'),
                                                'approved' => 1
    						);
                                        $originalID = substr($lineitem['description'], strpos($lineitem['description'], "*^*^*")+5);
                                        $notes = str_replace("*^*^*".$originalID, "", $lineitem['description']);
                                        $lineitem['description'] = $notes;
                                        
                                        mysql_query("UPDATE bill_items SET id = '".$lineitem['id']."' WHERE id = '" . $originalID . "';") or die(trigger_error(mysql_error()));
                                        
					QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, "LINEITEMID:::: " . print_r($originalID, true));
					foreach ($lineitem as $key => $value)
					{
						$lineitem[$key] = mysql_real_escape_string($value);
					}
					
                                        $qry = "
						UPDATE 
							bill_items
						SET ";
                                        $counter = 0;
                                        foreach($lineitem as $r => $t)
                                        {
                                            $counter++;
                                            if($counter>1) 
                                                 $qry .= ", ";
                                              
                                            $qry .= $r . "='" . $t . "'";
                                        }
						$qry .= " WHERE id = '". $lineitem['id'] . "';";
                                        QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, "LINEITEM:::: " . $qry);
					// Store the lineitems in MySQL
					mysql_query($qry) or die(trigger_error(mysql_error()));
                                        
                                        
                                        
                                }
                    }
                }
        }
//    $queryString = "
//        
//
//		UPDATE 
//			bill_items 
//		SET 
//			bill_items.bill_id = '" . mysql_real_escape_string($idents['TxnID']) . "'  
//		WHERE 
//			bill_items.bill_id = '" . $ID . "'";
//     $result = mysql_query($queryString);
     
	return $result;	
}

/**
 * Add any time records and then remove the old from the database so a clean record with a correct ID is imported.
 */
function _quickbooks_time_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
    
                $xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="13.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<TimeTrackingAddRq>';           
	
	 $queryString = "
				SELECT `TimeEntry`.`txn_date`,`TimeEntry`.`user_id`,`User`.`full_name`,
                                `TimeEntry`.`customer_id`, `Customer`.`full_name`, `TimeEntry`.`item_id`,
                                `Item`.`full_name`, `TimeEntry`.`duration`, `TimeEntry`.`class_id`,
                                `TimeEntry`.`class_name`, `TimeEntry`.`payroll_item_id`, `TimeEntry`.`payroll_item_name`,
                                `TimeEntry`.`notes`, `TimeEntry`.`billable_status`,`TimeEntry`.`is_billable`,
                                `TimeEntry`.`id` FROM 
					`time_entries` as TimeEntry 
                                        INNER JOIN `users` as User on (User.id = TimeEntry.user_id) 
                                        INNER JOIN `customers` as Customer on (TimeEntry.customer_id = Customer.id) 
                                        INNER JOIN `items` as Item on (TimeEntry.item_id = Item.id) 
				WHERE 
                                `TimeEntry`.`id` = '" . $ID . "';";
                        
         QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'REQUEST: ' . $requestID . " : ID : " . $ID . ' : Query String : ' . $queryString );
                        $row = mysql_fetch_array(mysql_query($queryString), MYSQL_NUM) or die(trigger_error(mysql_error()));
                        
                        QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'ROW : '. print_r($row, true));
                        
                            $xml .= "<TimeTrackingAdd> <!-- required -->
                            <TxnDate >".$row[0] ."</TxnDate> <!-- optional -->
                            <EntityRef> <!-- required -->
                            <ListID >".$row[1]."</ListID> <!-- optional -->
                            <FullName >".$row[2]."</FullName> <!-- optional -->
                            </EntityRef>
                            <CustomerRef> <!-- optional -->
                            <ListID >".$row[3]."</ListID> <!-- optional -->
                            <FullName >".$row[4]."</FullName> <!-- optional -->
                            </CustomerRef>
                            <ItemServiceRef> <!-- optional -->
                            <ListID >".$row[5]."</ListID> <!-- optional -->
                            <FullName >".$row[6]."</FullName> <!-- optional -->
                            </ItemServiceRef>
                            <Duration >".$row[7]."</Duration> <!-- required -->
                            <ClassRef> <!-- optional -->
                            <ListID >".$row[8]."</ListID> <!-- optional -->
                            <FullName >".$row[9]."</FullName> <!-- optional -->
                            </ClassRef>
                            <PayrollItemWageRef> <!-- optional -->
                            <ListID >".$row[10]."</ListID> <!-- optional -->
                            <FullName >".$row[11]."</FullName> <!-- optional -->
                            </PayrollItemWageRef>
                            <Notes >".$row[12]."</Notes> <!-- optional -->
                            <!-- BillableStatus may have one of the following values: Billable, NotBillable, HasBeenBilled -->
                            <BillableStatus >".$row[13]."</BillableStatus> <!-- optional -->
                            
                            </TimeTrackingAdd>";
                            
                          
                        
                       
                        $xml .="</TimeTrackingAddRq>
			</QBXMLMsgsRq>
		</QBXML>";
	QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'XML String : ' . $xml );
        

            return $xml;
        
       
}
/**
 * Receive a response from QuickBooks 
 */
function _quickbooks_time_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{
    $queryString = "
		UPDATE 
			time_entries 
		SET 
			time_entries.id = '" . mysql_real_escape_string($idents['TxnID']) . "',
                            time_entries.imported = 1 
		WHERE 
			id = '" . $ID . "'";
    QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'QUERY : ' . $queryString . ' ::: ID : ' . $ID . ' ::: ROW : '. print_r($idents, true));
    $result = mysql_query($queryString);
	return $result;	
}

/**
 * Build a request to import invoices already in QuickBooks into our application
 */
function _quickbooks_bill_import_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	// Iterator support (break the result set into small chunks)
	$attr_iteratorID = '';
	$attr_iterator = ' iterator="Start" ';
	if (empty($extra['iteratorID']))
	{
		// This is the first request in a new batch
		$last = _quickbooks_get_last_run($user, $action);
		_quickbooks_set_last_run($user, $action);			// Update the last run time to NOW()
		
		// Set the current run to $last
		_quickbooks_set_current_run($user, $action, $last);
	}
	else
	{
		// This is a continuation of a batch
		$attr_iteratorID = ' iteratorID="' . $extra['iteratorID'] . '" ';
		$attr_iterator = ' iterator="Continue" ';
		
		$last = _quickbooks_get_current_run($user, $action);
	}
	
	// Build the request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="' . $version . '"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<BillQueryRq ' . $attr_iterator . ' ' . $attr_iteratorID . ' requestID="' . $requestID . '">
					<MaxReturned>' . QB_QUICKBOOKS_MAX_RETURNED . '</MaxReturned>
					<IncludeLineItems>true</IncludeLineItems>
					<OwnerID>0</OwnerID>
				</BillQueryRq>	
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

/** 
 * Handle a response from QuickBooks 
 */
function _quickbooks_bill_import_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	if (!empty($idents['iteratorRemainingCount']))
	{
		// Queue up another request
		
		$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();
		$Queue->enqueue(QUICKBOOKS_IMPORT_BILL, null, QB_PRIORITY_BILL, array( 'iteratorID' => $idents['iteratorID'] ));
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
		$List = $Root->getChildAt('QBXML/QBXMLMsgsRs/BillQueryRs');
		
		foreach ($List->children() as $Bill)
		{
                    
			$arr = array(
				'id' => $Bill->getChildDataAt('BillRet TxnID'),
				'created' => $Bill->getChildDataAt('BillRet TimeCreated'),
				'modified' => $Bill->getChildDataAt('BillRet TimeModified'),
				'vendor_id' => $Bill->getChildDataAt('BillRet VendorRef ListID'),
				'customer_id' => $Bill->getChildDataAt('BillRet CustomerRef ListID'),
				'ref_number' => $Bill->getChildDataAt('BillRet RefNumber'),
				'txn_date' => $Bill->getChildDataAt('BillRet TxnDate'),
				'amount_due' => $Bill->getChildDataAt('BillRet AmountDue'),
				'terms_id' => $Bill->getChildDataAt('BillRet TermsRef ListID'),
				'memo' => $Bill->getChildDataAt('BillRet Memo'),
				'is_paid' => $Bill->getChildDataAt('BillRet IsPaid'),
				
				);
			
			QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'Importing bill #' . $arr['ref_number'] . ': ' . print_r($arr, true));
			
			foreach ($arr as $key => $value)
			{
				$arr[$key] = mysql_real_escape_string($value);
			}
			
			// Store the invoices in MySQL
			mysql_query("
				REPLACE INTO
					bills
				(
					" . implode(", ", array_keys($arr)) . "
				) VALUES (
					'" . implode("', '", array_values($arr)) . "'
				)") or die(trigger_error(mysql_error()));
			
			// Remove any old line items
			mysql_query("DELETE FROM bill_expenses WHERE bill_expenses.bill_id = '" . mysql_real_escape_string($arr['id']) . "' ") or die(trigger_error(mysql_error()));
			
                        // Remove any old line items
		//	mysql_query("DELETE FROM bill_items WHERE bill_items.bill_id = '" . mysql_real_escape_string($arr['id']) . "' ") or die(trigger_error(mysql_error()));
			
			// Process the line items
                        
			foreach ($Bill->children() as $Child)
			{
                            QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, "CHILD NAME:::: " . $Child->name());
                            
				if ($Child->name() == 'ExpenseLineRet')
				{
					$ExpenseLine = $Child;
					
					$expenselineitem = array( 
						'bill_id' => $arr['id'], 
                                            'vendor_id' => $arr['vendor_id'],
						'id' => $ExpenseLine->getChildDataAt('ExpenseLineRet TxnLineID'), 
						'account_id' => $ExpenseLine->getChildDataAt('ExpenseLineRet AccountRef ListID'), 
						'amount' => $ExpenseLine->getChildDataAt('ExpenseLineRet Amount'), 
						'memo' => $ExpenseLine->getChildDataAt('ExpenseLineRet Memo'), 
						'customer_id' => $ExpenseLine->getChildDataAt('ExpenseLineRet CustomerRef ListID'),
						'class_id' => $ExpenseLine->getChildDataAt('ExpenseLineRet ClassRef ListID'), 
                                                'billable' => $ExpenseLine->getChildDataAt('ExpenseLineRet BillableStatus'),
                                                'approved' => 1
    						);
					
					foreach ($expenselineitem as $key => $value)
					{
						$expenselineitem[$key] = mysql_real_escape_string($value);
					}
					
                                        $qry = "
						INSERT INTO
							bill_expenses
						(
							" . implode(", ", array_keys($expenselineitem)) . "
						) VALUES (
							'" . implode("', '", array_values($expenselineitem)) . "'
						) ";
                                        
					// Store the lineitems in MySQL
					mysql_query($qry) or die(trigger_error(mysql_error()));
                                        
                                        
                                        
				}
                                
                                elseif($Child->name() == 'ItemLineRet')
                                {
                                    $ItemLine = $Child;
					
					$lineitem = array( 
						'bill_id' => $arr['id'], 
                                            'vendor_id' => $arr['vendor_id'],
						'id' => $ItemLine->getChildDataAt('ItemLineRet TxnLineID'), 
						'item_id' => $ItemLine->getChildDataAt('ItemLineRet ItemRef ListID'), 
						'amount' => $ItemLine->getChildDataAt('ItemLineRet Amount'), 
                                            'cost' => $ItemLine->getChildDataAt('ItemLineRet Cost'), 
                                            'quantity' => $ItemLine->getChildDataAt('ItemLineRet Quantity'), 
						'description' => $ItemLine->getChildDataAt('ItemLineRet Desc'), 
						'customer_id' => $ItemLine->getChildDataAt('ItemLineRet CustomerRef ListID'),
						'class_id' => $ItemLine->getChildDataAt('ItemLineRet ClassRef ListID'), 
                                                'billable' => $ItemLine->getChildDataAt('ItemLineRet BillableStatus'),
                                                'approved' => 1
    						);
					QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, "LINEITEM:::: " . print_r($lineitem, true));
					foreach ($lineitem as $key => $value)
					{
						$lineitem[$key] = mysql_real_escape_string($value);
					}
					
                                        $qry = "
						INSERT INTO
							bill_items
						(
							" . implode(", ", array_keys($lineitem)) . "
						) VALUES (
							'" . implode("', '", array_values($lineitem)) . "'
						) ON DUPLICATE KEY ".
                                                "
						UPDATE ";
                                        $counter = 0;
                                        foreach($lineitem as $r => $t)
                                        {
                                            $counter++;
                                            if($counter>1) 
                                                 $qry .= ", ";
                                              
                                            $qry .= $r . "='" . $t . "'";
                                        }
						$qry .= ";";
                                        QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, "LINEITEM:::: " . $qry);
					// Store the lineitems in MySQL
					mysql_query($qry) or die(trigger_error(mysql_error()));
                                        
                                        
                                        
                                }
			}
		}
	}
	
	return true;
}


/**
 * Build a request to import invoices already in QuickBooks into our application
 */
function _quickbooks_invoice_import_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	// Iterator support (break the result set into small chunks)
	$attr_iteratorID = '';
	$attr_iterator = ' iterator="Start" ';
	if (empty($extra['iteratorID']))
	{
		// This is the first request in a new batch
		$last = _quickbooks_get_last_run($user, $action);
		_quickbooks_set_last_run($user, $action);			// Update the last run time to NOW()
		
		// Set the current run to $last
		_quickbooks_set_current_run($user, $action, $last);
	}
	else
	{
		// This is a continuation of a batch
		$attr_iteratorID = ' iteratorID="' . $extra['iteratorID'] . '" ';
		$attr_iterator = ' iterator="Continue" ';
		
		$last = _quickbooks_get_current_run($user, $action);
	}
	
	// Build the request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="' . $version . '"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<InvoiceQueryRq ' . $attr_iterator . ' ' . $attr_iteratorID . ' requestID="' . $requestID . '">
					<MaxReturned>' . QB_QUICKBOOKS_MAX_RETURNED . '</MaxReturned>
					<ModifiedDateRangeFilter>
						<FromModifiedDate>' . $last . '</FromModifiedDate>
					</ModifiedDateRangeFilter>
					<IncludeLineItems>true</IncludeLineItems>
					<OwnerID>0</OwnerID>
				</InvoiceQueryRq>	
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

/** 
 * Handle a response from QuickBooks 
 */
function _quickbooks_invoice_import_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	if (!empty($idents['iteratorRemainingCount']))
	{
		// Queue up another request
		
		$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();
		$Queue->enqueue(QUICKBOOKS_IMPORT_INVOICE, null, QB_PRIORITY_INVOICE, array( 'iteratorID' => $idents['iteratorID'] ));
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
		$List = $Root->getChildAt('QBXML/QBXMLMsgsRs/InvoiceQueryRs');
		
		foreach ($List->children() as $Invoice)
		{
			$arr = array(
				'TxnID' => $Invoice->getChildDataAt('InvoiceRet TxnID'),
				'TimeCreated' => $Invoice->getChildDataAt('InvoiceRet TimeCreated'),
				'TimeModified' => $Invoice->getChildDataAt('InvoiceRet TimeModified'),
				'RefNumber' => $Invoice->getChildDataAt('InvoiceRet RefNumber'),
				'Customer_ListID' => $Invoice->getChildDataAt('InvoiceRet CustomerRef ListID'),
				'Customer_FullName' => $Invoice->getChildDataAt('InvoiceRet CustomerRef FullName'),
				'ShipAddress_Addr1' => $Invoice->getChildDataAt('InvoiceRet ShipAddress Addr1'),
				'ShipAddress_Addr2' => $Invoice->getChildDataAt('InvoiceRet ShipAddress Addr2'),
				'ShipAddress_City' => $Invoice->getChildDataAt('InvoiceRet ShipAddress City'),
				'ShipAddress_State' => $Invoice->getChildDataAt('InvoiceRet ShipAddress State'),
				'ShipAddress_PostalCode' => $Invoice->getChildDataAt('InvoiceRet ShipAddress PostalCode'),
				'BalanceRemaining' => $Invoice->getChildDataAt('InvoiceRet BalanceRemaining'),
				);
			
			QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'Importing invoice #' . $arr['RefNumber'] . ': ' . print_r($arr, true));
			
			foreach ($arr as $key => $value)
			{
				$arr[$key] = mysql_real_escape_string($value);
			}
			
			// Store the invoices in MySQL
			mysql_query("
				REPLACE INTO
					qb_example_invoice
				(
					" . implode(", ", array_keys($arr)) . "
				) VALUES (
					'" . implode("', '", array_values($arr)) . "'
				)") or die(trigger_error(mysql_error()));
			
			// Remove any old line items
			mysql_query("DELETE FROM qb_example_invoice_lineitem WHERE TxnID = '" . mysql_real_escape_string($arr['TxnID']) . "' ") or die(trigger_error(mysql_error()));
			
			// Process the line items
			foreach ($Invoice->children() as $Child)
			{
				if ($Child->name() == 'InvoiceLineRet')
				{
					$InvoiceLine = $Child;
					
					$lineitem = array( 
						'TxnID' => $arr['TxnID'], 
						'TxnLineID' => $InvoiceLine->getChildDataAt('InvoiceLineRet TxnLineID'), 
						'Item_ListID' => $InvoiceLine->getChildDataAt('InvoiceLineRet ItemRef ListID'), 
						'Item_FullName' => $InvoiceLine->getChildDataAt('InvoiceLineRet ItemRef FullName'), 
						'Descrip' => $InvoiceLine->getChildDataAt('InvoiceLineRet Desc'), 
						'Quantity' => $InvoiceLine->getChildDataAt('InvoiceLineRet Quantity'),
						'Rate' => $InvoiceLine->getChildDataAt('InvoiceLineRet Rate'), 
						);
					
					foreach ($lineitem as $key => $value)
					{
						$lineitem[$key] = mysql_real_escape_string($value);
					}
					
					// Store the lineitems in MySQL
					mysql_query("
						INSERT INTO
							qb_example_invoice_lineitem
						(
							" . implode(", ", array_keys($lineitem)) . "
						) VALUES (
							'" . implode("', '", array_values($lineitem)) . "'
						) ") or die(trigger_error(mysql_error()));
				}
			}
		}
	}
	
	return true;
}

/**
 * Build a request to import customers already in QuickBooks into our application
 */
function _quickbooks_customer_import_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	// Iterator support (break the result set into small chunks)
	$attr_iteratorID = '';
	$attr_iterator = ' iterator="Start" ';
	if (empty($extra['iteratorID']))
	{
		// This is the first request in a new batch
		$last = _quickbooks_get_last_run($user, $action);
		_quickbooks_set_last_run($user, $action);			// Update the last run time to NOW()
		
		// Set the current run to $last
		_quickbooks_set_current_run($user, $action, $last);
	}
	else
	{
		// This is a continuation of a batch
		$attr_iteratorID = ' iteratorID="' . $extra['iteratorID'] . '" ';
		$attr_iterator = ' iterator="Continue" ';
		
		$last = _quickbooks_get_current_run($user, $action);
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
		
	return $xml;
}

/** 
 * Handle a response from QuickBooks 
 */
function _quickbooks_customer_import_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
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
                    $job = false;
                    $arr1 = array(
				'id' => $Customer->getChildDataAt('CustomerRet ListID'),
				'created' => $Customer->getChildDataAt('CustomerRet TimeCreated'),
				'modified' => $Customer->getChildDataAt('CustomerRet TimeModified'),
				'name' => $Customer->getChildDataAt('CustomerRet Name'),
                            'company_name' => $Customer->getChildDataAt('CustomerRet CompanyName'),
				'full_name' => $Customer->getChildDataAt('CustomerRet FullName'),
				'first_name' => $Customer->getChildDataAt('CustomerRet FirstName'),
				'middle_name' => $Customer->getChildDataAt('CustomerRet MiddleName'),
				'last_name' => $Customer->getChildDataAt('CustomerRet LastName'),
				'contact' => $Customer->getChildDataAt('CustomerRet Contact'),
				'ship_addr1' => $Customer->getChildDataAt('CustomerRet ShipAddress Addr1'),
				'ship_addr2' => $Customer->getChildDataAt('CustomerRet ShipAddress Addr2'),
				'ship_city' => $Customer->getChildDataAt('CustomerRet ShipAddress City'),
				'ship_state' => $Customer->getChildDataAt('CustomerRet ShipAddress State'),
				'ship_zip' => $Customer->getChildDataAt('CustomerRet ShipAddress PostalCode'),
                            'ship_note' => $Customer->getChildDataAt('CustomerRet ShipAddress Note'),
                            'bill_addr1' => $Customer->getChildDataAt('CustomerRet BillAddress Addr1'),
				'bill_addr2' => $Customer->getChildDataAt('CustomerRet BillAddress Addr2'),
				'bill_city' => $Customer->getChildDataAt('CustomerRet BillAddress City'),
				'bill_state' => $Customer->getChildDataAt('CustomerRet BillAddress State'),
				'bill_zip' => $Customer->getChildDataAt('CustomerRet BillAddress PostalCode'),
                            'bill_note' => $Customer->getChildDataAt('CustomerRet BillAddress Note'),
                            'phone' => $Customer->getChildDataAt('CustomerRet Phone'),
                            'alt_phone' => $Customer->getChildDataAt('CustomerRet AltPhone'),
                            'fax' => $Customer->getChildDataAt('CustomerRet Fax'),
                            'email' => $Customer->getChildDataAt('CustomerRet Email'),
                            'alt_email' => $Customer->getChildDataAt('CustomerRet AltEmail'),
                            'balance' => $Customer->getChildDataAt('CustomerRet Balance'),
                            'total_balance' => $Customer->getChildDataAt('CustomerRet TotalBalance'),
                            'notes' => $Customer->getChildDataAt('CustomerRet Notes')
                            
				);
                    
                    if(strpos($Customer->getChildDataAt('CustomerRet FullName'), ':') !== false)
                    {
                        $job = true;
                        $arr2 =  array('job_status' => $Customer->getChildDataAt('CustomerRet JobStatus'),
				'start_date' => $Customer->getChildDataAt('CustomerRet JobStartDate'),
				'projected_end_date' => $Customer->getChildDataAt('CustomerRet JobProjectedEndDate'),
				'end_date' => $Customer->getChildDataAt('CustomerRet JobEndDate'),
                            'description' => $Customer->getChildDataAt('CustomerRet JobDescription'),
                                'job_type_id' => $Customer->getChildDataAt('CustomerRet JobTypeRef ListID'),
				'job_type' => $Customer->getChildDataAt('CustomerRet JobTypeRef FullName'),
				'customer_id' => null
                                );
			
                    }
                    
                    if($job)
                    {
                        $arr = array_merge($arr1, $arr2);
                        QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'Importing job ' . $arr['full_name'] . ': ' . print_r($arr, true));
			
                        $custName = substr($arr['full_name'],0,strpos($arr['full_name'], ':'));
                       
                        $queryString = "
				SELECT `id` FROM 
					`customers`
				WHERE
                                 `full_name` = '" . $custName . "'";
                        
                        $result = mysql_query($queryString) or die(trigger_error(mysql_error()));
                        
                        
                        $arr['customer_id'] = mysql_fetch_row($result)[0];
                        
			foreach ($arr as $key => $value)
			{
				$arr[$key] = mysql_real_escape_string($value);
			}
			
                        
			// Store the invoices in MySQL
			mysql_query("
				REPLACE INTO
					jobs
				(
					" . implode(", ", array_keys($arr)) . "
				) VALUES (
					'" . implode("', '", array_values($arr)) . "'
				)") or die(trigger_error(mysql_error()));
                    }
                    else
                    {
                        $arr = $arr1;
                        QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'Importing customer ' . $arr['full_name'] . ': ' . print_r($arr, true));
			
			foreach ($arr as $key => $value)
			{
				$arr[$key] = mysql_real_escape_string($value);
			}
			
                        $query = "
				REPLACE INTO
					customers
				(
					" . implode(", ", array_keys($arr)) . "
				) VALUES (
					'" . implode("', '", array_values($arr)) . "'
				)";
                        
                     //   QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'MySQL: ' . ': ' . $query);
			// Store the invoices in MySQL
			mysql_query($query) or die(trigger_error(mysql_error()));
                    }
			
			
		}
	}
	
	return true;
}

/**
 * Build a request to import sales orders already in QuickBooks into our application
 */
function _quickbooks_salesorder_import_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	// Iterator support (break the result set into small chunks)
	$attr_iteratorID = '';
	$attr_iterator = ' iterator="Start" ';
	if (empty($extra['iteratorID']))
	{
		// This is the first request in a new batch
		$last = _quickbooks_get_last_run($user, $action);
		_quickbooks_set_last_run($user, $action);			// Update the last run time to NOW()
		
		// Set the current run to $last
		_quickbooks_set_current_run($user, $action, $last);
	}
	else
	{
		// This is a continuation of a batch
		$attr_iteratorID = ' iteratorID="' . $extra['iteratorID'] . '" ';
		$attr_iterator = ' iterator="Continue" ';
		
		$last = _quickbooks_get_current_run($user, $action);
	}
	
	// Build the request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="' . $version . '"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<SalesOrderQueryRq ' . $attr_iterator . ' ' . $attr_iteratorID . ' requestID="' . $requestID . '">
					<MaxReturned>' . QB_QUICKBOOKS_MAX_RETURNED . '</MaxReturned>
					<ModifiedDateRangeFilter>
						<FromModifiedDate>' . $last . '</FromModifiedDate>
					</ModifiedDateRangeFilter>
					<IncludeLineItems>true</IncludeLineItems>
					<OwnerID>0</OwnerID>
				</SalesOrderQueryRq>	
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

/** 
 * Handle a response from QuickBooks 
 */
function _quickbooks_salesorder_import_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	if (!empty($idents['iteratorRemainingCount']))
	{
		// Queue up another request
		
		$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();
		$Queue->enqueue(QUICKBOOKS_IMPORT_SALESORDER, null, QB_PRIORITY_SALESORDER, array( 'iteratorID' => $idents['iteratorID'] ));
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
		$List = $Root->getChildAt('QBXML/QBXMLMsgsRs/SalesOrderQueryRs');
		
		foreach ($List->children() as $SalesOrder)
		{
			$arr = array(
				'TxnID' => $SalesOrder->getChildDataAt('SalesOrderRet TxnID'),
				'TimeCreated' => $SalesOrder->getChildDataAt('SalesOrderRet TimeCreated'),
				'TimeModified' => $SalesOrder->getChildDataAt('SalesOrderRet TimeModified'),
				'RefNumber' => $SalesOrder->getChildDataAt('SalesOrderRet RefNumber'),
				'Customer_ListID' => $SalesOrder->getChildDataAt('SalesOrderRet CustomerRef ListID'),
				'Customer_FullName' => $SalesOrder->getChildDataAt('SalesOrderRet CustomerRef FullName'),
				'ShipAddress_Addr1' => $SalesOrder->getChildDataAt('SalesOrderRet ShipAddress Addr1'),
				'ShipAddress_Addr2' => $SalesOrder->getChildDataAt('SalesOrderRet ShipAddress Addr2'),
				'ShipAddress_City' => $SalesOrder->getChildDataAt('SalesOrderRet ShipAddress City'),
				'ShipAddress_State' => $SalesOrder->getChildDataAt('SalesOrderRet ShipAddress State'),
				'ShipAddress_PostalCode' => $SalesOrder->getChildDataAt('SalesOrderRet ShipAddress PostalCode'),
				'BalanceRemaining' => $SalesOrder->getChildDataAt('SalesOrderRet BalanceRemaining'),
				);
			
			QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'Importing sales order #' . $arr['RefNumber'] . ': ' . print_r($arr, true));
			
			foreach ($arr as $key => $value)
			{
				$arr[$key] = mysql_real_escape_string($value);
			}
			
			// Store the invoices in MySQL
			mysql_query("
				REPLACE INTO
					qb_example_salesorder
				(
					" . implode(", ", array_keys($arr)) . "
				) VALUES (
					'" . implode("', '", array_values($arr)) . "'
				)") or die(trigger_error(mysql_error()));
			
			// Remove any old line items
			mysql_query("DELETE FROM qb_example_salesorder_lineitem WHERE TxnID = '" . mysql_real_escape_string($arr['TxnID']) . "' ") or die(trigger_error(mysql_error()));
			
			// Process the line items
			foreach ($SalesOrder->children() as $Child)
			{
				if ($Child->name() == 'SalesOrderLineRet')
				{
					$SalesOrderLine = $Child;
					
					$lineitem = array( 
						'TxnID' => $arr['TxnID'], 
						'TxnLineID' => $SalesOrderLine->getChildDataAt('SalesOrderLineRet TxnLineID'), 
						'Item_ListID' => $SalesOrderLine->getChildDataAt('SalesOrderLineRet ItemRef ListID'), 
						'Item_FullName' => $SalesOrderLine->getChildDataAt('SalesOrderLineRet ItemRef FullName'), 
						'Descrip' => $SalesOrderLine->getChildDataAt('SalesOrderLineRet Desc'), 
						'Quantity' => $SalesOrderLine->getChildDataAt('SalesOrderLineRet Quantity'),
						'Rate' => $SalesOrderLine->getChildDataAt('SalesOrderLineRet Rate'), 
						);
					
					foreach ($lineitem as $key => $value)
					{
						$lineitem[$key] = mysql_real_escape_string($value);
					}
					
					// Store the lineitems in MySQL
					mysql_query("
						INSERT INTO
							qb_example_salesorder_lineitem
						(
							" . implode(", ", array_keys($lineitem)) . "
						) VALUES (
							'" . implode("', '", array_values($lineitem)) . "'
						) ") or die(trigger_error(mysql_error()));
				}
			}
		}
	}
	
	return true;
}

/**
 * Build a request to import customers already in QuickBooks into our application
 */
function _quickbooks_time_tracking_import_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	// Iterator support (break the result set into small chunks)
	$attr_iteratorID = '';
	$attr_iterator = ' iterator="Start" ';
	if (empty($extra['iteratorID']))
	{
		// This is the first request in a new batch
		$last = _quickbooks_get_last_run($user, $action);
		_quickbooks_set_last_run($user, $action);			// Update the last run time to NOW()
		
		// Set the current run to $last
		_quickbooks_set_current_run($user, $action, $last);
	}
	else
	{
		// This is a continuation of a batch
		$attr_iteratorID = ' iteratorID="' . $extra['iteratorID'] . '" ';
		$attr_iterator = ' iterator="Continue" ';
		
		$last = _quickbooks_get_current_run($user, $action);
	}
	
	// Build the request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="' . $version . '"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<TimeTrackingQueryRq ' . $attr_iterator . ' ' . $attr_iteratorID . ' requestID="' . $requestID . '">
					<MaxReturned>' . QB_QUICKBOOKS_MAX_RETURNED . '</MaxReturned>
<ModifiedDateRangeFilter>					
<FromModifiedDate>' . $last . '</FromModifiedDate>
    </ModifiedDateRangeFilter>
				</TimeTrackingQueryRq>	
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

/** 
 * Handle a response from QuickBooks 
 */
function _quickbooks_time_tracking_import_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	if (!empty($idents['iteratorRemainingCount']))
	{
		// Queue up another request
		
		$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();
		$Queue->enqueue(QUICKBOOKS_IMPORT_TIMETRACKING, null, QB_PRIORITY_TIMETRACKING, array( 'iteratorID' => $idents['iteratorID'] ));
	}
	
	// Import all of the records
	$errnum = 0;
	$errmsg = '';
	$Parser = new QuickBooks_XML_Parser($xml);
	if ($Doc = $Parser->parse($errnum, $errmsg))
	{
		$Root = $Doc->getRoot();
		$List = $Root->getChildAt('QBXML/QBXMLMsgsRs/TimeTrackingQueryRs');
		
                
		foreach ($List->children() as $Item)
		{
			$ret = 'TimeTrackingRet';
			$arr = array(
				'id' => $Item->getChildDataAt($ret . ' TxnID'),
				'created' => $Item->getChildDataAt($ret . ' TimeCreated'),
				'modified' => $Item->getChildDataAt($ret . ' TimeModified'),
				'txn_number' => $Item->getChildDataAt($ret . ' TxnNumber'),
				'txn_date' => $Item->getChildDataAt($ret . ' TxnDate'),
				'user_id' => $Item->getChildDataAt($ret . ' EntityRef ListID'), 
				'customer_id' => $Item->getChildDataAt($ret . ' CustomerRef ListID'),
				'item_id' => $Item->getChildDataAt($ret . ' ItemServiceRef ListID'),
				'duration' => $Item->getChildDataAt($ret . ' Duration'), 
				'class_name' => $Item->getChildDataAt($ret . ' ClassRef FullName'), 
				'class_id' => $Item->getChildDataAt($ret . ' ClassRef ListID'), 
				'payroll_item_name' => $Item->getChildDataAt($ret . ' PayrollItemWageRef FullName'), 
				'payroll_item_id' => $Item->getChildDataAt($ret . ' PayrollItemWageRef ListID'), 
				'notes' => $Item->getChildDataAt($ret . ' Notes'), 
				'billable_status' => $Item->getChildDataAt($ret . ' BillableStatus'), 
				'is_billable' => $Item->getChildDataAt($ret . ' IsBillable'), 
				'is_billed' => $Item->getChildDataAt($ret . ' IsBilled'),  
				'approved' => 1,  
                                'imported' => 1
				);
			
			
			QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'Importing Time Entry '  . $arr['id'] . ': ' . print_r($arr, true));
			
			foreach ($arr as $key => $value)
			{
				$arr[$key] = mysql_real_escape_string($value);
			}
			
			//print_r(array_keys($arr));
			//trigger_error(print_r(array_keys($arr), true));
			
			// Store the items in MySQL
			mysql_query("
				REPLACE INTO
					time_entries
				(
					" . implode(", ", array_keys($arr)) . "
				) VALUES (
					'" . implode("', '", array_values($arr)) . "'
				)") or die(trigger_error(mysql_error()));
		}
	}
	
	return true;
}


/**
 * Build a request to import customers already in QuickBooks into our application
 */
function _quickbooks_item_import_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	// Iterator support (break the result set into small chunks)
	$attr_iteratorID = '';
	$attr_iterator = ' iterator="Start" ';
	if (empty($extra['iteratorID']))
	{
		// This is the first request in a new batch
		$last = _quickbooks_get_last_run($user, $action);
		_quickbooks_set_last_run($user, $action);			// Update the last run time to NOW()
		
		// Set the current run to $last
		_quickbooks_set_current_run($user, $action, $last);
	}
	else
	{
		// This is a continuation of a batch
		$attr_iteratorID = ' iteratorID="' . $extra['iteratorID'] . '" ';
		$attr_iterator = ' iterator="Continue" ';
		
		$last = _quickbooks_get_current_run($user, $action);
	}
	
	// Build the request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="' . $version . '"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<ItemQueryRq ' . $attr_iterator . ' ' . $attr_iteratorID . ' requestID="' . $requestID . '">
					<MaxReturned>' . QB_QUICKBOOKS_MAX_RETURNED . '</MaxReturned>
					<FromModifiedDate>' . $last . '</FromModifiedDate>
					<OwnerID>0</OwnerID>
				</ItemQueryRq>	
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

/** 
 * Handle a response from QuickBooks 
 */
function _quickbooks_item_import_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	if (!empty($idents['iteratorRemainingCount']))
	{
		// Queue up another request
		
		$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();
		$Queue->enqueue(QUICKBOOKS_IMPORT_ITEM, null, QB_PRIORITY_ITEM, array( 'iteratorID' => $idents['iteratorID'] ));
	}
	
	// Import all of the records
	$errnum = 0;
	$errmsg = '';
	$Parser = new QuickBooks_XML_Parser($xml);
	if ($Doc = $Parser->parse($errnum, $errmsg))
	{
		$Root = $Doc->getRoot();
		$List = $Root->getChildAt('QBXML/QBXMLMsgsRs/ItemQueryRs');
		
		foreach ($List->children() as $Item)
		{
			$type = substr(substr($Item->name(), 0, -3), 4);
			$ret = $Item->name();
			
			$arr = array(
				'id' => $Item->getChildDataAt($ret . ' ListID'),
				'created' => $Item->getChildDataAt($ret . ' TimeCreated'),
				'modified' => $Item->getChildDataAt($ret . ' TimeModified'),
				'name' => $Item->getChildDataAt($ret . ' Name'),
				'full_name' => $Item->getChildDataAt($ret . ' FullName'),
				'type' => $type, 
				'parent_id' => $Item->getChildDataAt($ret . ' ParentRef ListID'),
				'parent_full_name' => $Item->getChildDataAt($ret . ' ParentRef FullName'),
				'manufacturer_part_number' => $Item->getChildDataAt($ret . ' ManufacturerPartNumber'), 
				'sales_tax_code_id' => $Item->getChildDataAt($ret . ' SalesTaxCodeRef ListID'), 
				'sales_tax_code_full_name' => $Item->getChildDataAt($ret . ' SalesTaxCodeRef FullName'), 
				'build_point' => $Item->getChildDataAt($ret . ' BuildPoint'), 
				'reorder_point' => $Item->getChildDataAt($ret . ' ReorderPoint'), 
				'quantity_on_hand' => $Item->getChildDataAt($ret . ' QuantityOnHand'), 
				'average_cost' => $Item->getChildDataAt($ret . ' AverageCost'), 
				'quantity_on_order' => $Item->getChildDataAt($ret . ' QuantityOnOrder'), 
				'quantity_on_sales_order' => $Item->getChildDataAt($ret . ' QuantityOnSalesOrder'),  
				'tax_rate' => $Item->getChildDataAt($ret . ' TaxRate'),  
				);
			
			$look_for = array(
				'sales_price' => array( 'SalesOrPurchase Price', 'SalesAndPurchase SalesPrice', 'SalesPrice' ),
				'sales_desc' => array( 'SalesOrPurchase Desc', 'SalesAndPurchase SalesDesc', 'SalesDesc' ),
				'purchase_cost' => array( 'SalesOrPurchase Price', 'SalesAndPurchase PurchaseCost', 'PurchaseCost' ),
				'purchase_desc' => array( 'SalesOrPurchase Desc', 'SalesAndPurchase PurchaseDesc', 'PurchaseDesc' ),
				'pref_vendor_id' => array( 'SalesAndPurchase PrefVendorRef ListID', 'PrefVendorRef ListID' ), 
				'pref_vendor_full_name' => array( 'SalesAndPurchase PrefVendorRef FullName', 'PrefVendorRef FullName' ),
				); 
			
			foreach ($look_for as $field => $look_here)
			{
				if (!empty($arr[$field]))
				{
					break;
				}
				
				foreach ($look_here as $look)
				{
					$arr[$field] = $Item->getChildDataAt($ret . ' ' . $look);
				}
			}
			
			QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'Importing ' . $type . ' Item ' . $arr['full_name'] . ': ' . print_r($arr, true));
			
			foreach ($arr as $key => $value)
			{
				$arr[$key] = mysql_real_escape_string($value);
			}
			
			//print_r(array_keys($arr));
			//trigger_error(print_r(array_keys($arr), true));
			
			// Store the items in MySQL
			mysql_query("
				REPLACE INTO
					items
				(
					" . implode(", ", array_keys($arr)) . "
				) VALUES (
					'" . implode("', '", array_values($arr)) . "'
				)") or die(trigger_error(mysql_error()));
		}
	}
	
	return true;
}

/**
 * Build a request to import invoices already in QuickBooks into our application
 */
function _quickbooks_purchaseorder_import_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	// Iterator support (break the result set into small chunks)
	$attr_iteratorID = '';
	$attr_iterator = ' iterator="Start" ';
	if (empty($extra['iteratorID']))
	{
		// This is the first request in a new batch
		$last = _quickbooks_get_last_run($user, $action);
		_quickbooks_set_last_run($user, $action);			// Update the last run time to NOW()
		
		// Set the current run to $last
		_quickbooks_set_current_run($user, $action, $last);
	}
	else
	{
		// This is a continuation of a batch
		$attr_iteratorID = ' iteratorID="' . $extra['iteratorID'] . '" ';
		$attr_iterator = ' iterator="Continue" ';
		
		$last = _quickbooks_get_current_run($user, $action);
	}
	
	// Build the request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="' . $version . '"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<PurchaseOrderQueryRq ' . $attr_iterator . ' ' . $attr_iteratorID . ' requestID="' . $requestID . '">
					<MaxReturned>' . QB_QUICKBOOKS_MAX_RETURNED . '</MaxReturned>
					<!--<ModifiedDateRangeFilter>
						<FromModifiedDate>' . $last . '</FromModifiedDate>
					</ModifiedDateRangeFilter>-->
					<IncludeLineItems>true</IncludeLineItems>
					<OwnerID>0</OwnerID>
				</PurchaseOrderQueryRq>	
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

/** 
 * Handle a response from QuickBooks 
 */
function _quickbooks_purchaseorder_import_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	if (!empty($idents['iteratorRemainingCount']))
	{
		// Queue up another request
		
		$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();
		$Queue->enqueue(QUICKBOOKS_IMPORT_PURCHASEORDER, null, QB_PRIORITY_PURCHASEORDER, array( 'iteratorID' => $idents['iteratorID'] ));
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
		$List = $Root->getChildAt('QBXML/QBXMLMsgsRs/PurchaseOrderQueryRs');
		
		foreach ($List->children() as $PurchaseOrder)
		{
			$arr = array(
				'TxnID' => $PurchaseOrder->getChildDataAt('PurchaseOrderRet TxnID'),
				'TimeCreated' => $PurchaseOrder->getChildDataAt('PurchaseOrderRet TimeCreated'),
				'TimeModified' => $PurchaseOrder->getChildDataAt('PurchaseOrderRet TimeModified'),
				'RefNumber' => $PurchaseOrder->getChildDataAt('PurchaseOrderRet RefNumber'),
				'Customer_ListID' => $PurchaseOrder->getChildDataAt('PurchaseOrderRet CustomerRef ListID'),
				'Customer_FullName' => $PurchaseOrder->getChildDataAt('PurchaseOrderRet CustomerRef FullName'),
				);
			
			QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'Importing purchase order #' . $arr['RefNumber'] . ': ' . print_r($arr, true));
			
			foreach ($arr as $key => $value)
			{
				$arr[$key] = mysql_real_escape_string($value);
			}
			
			// Process all child elements of the Purchase Order
			foreach ($PurchaseOrder->children() as $Child)
			{
				if ($Child->name() == 'PurchaseOrderLineRet')
				{
					// Loop through line items
					
					$PurchaseOrderLine = $Child;
					
					$lineitem = array( 
						'TxnID' => $arr['TxnID'], 
						'TxnLineID' => $PurchaseOrderLine->getChildDataAt('PurchaseOrderLineRet TxnLineID'), 
						'Item_ListID' => $PurchaseOrderLine->getChildDataAt('PurchaseOrderLineRet ItemRef ListID'), 
						'Item_FullName' => $PurchaseOrderLine->getChildDataAt('PurchaseOrderLineRet ItemRef FullName'), 
						'Descrip' => $PurchaseOrderLine->getChildDataAt('PurchaseOrderLineRet Desc'), 
						'Quantity' => $PurchaseOrderLine->getChildDataAt('PurchaseOrderLineRet Quantity'),
						'Rate' => $PurchaseOrderLine->getChildDataAt('PurchaseOrderLineRet Rate'), 
						);
					
					QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, ' - line item #' . $lineitem['TxnLineID'] . ': ' . print_r($lineitem, true));
				}
				else if ($Child->name() == 'DataExtRet')
				{
					// Loop through custom fields
					
					$DataExt = $Child;
					
					$dataext = array(
						'DataExtName' => $Child->getChildDataAt('DataExtRet DataExtName'), 
						'DataExtValue' => $Child->getChildDataAt('DataExtRet DataExtValue'), 
						);
					
					QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, ' - custom field "' . $dataext['DataExtName'] . '": ' . $dataext['DataExtValue']);
				}
			}
		}
	}
	
	return true;
}

function _quickbooks_vendor_import_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	// Iterator support (break the result set into small chunks)
	$attr_iteratorID = '';
	$attr_iterator = ' iterator="Start" ';
	if (empty($extra['iteratorID']))
	{
		// This is the first request in a new batch
		$last = _quickbooks_get_last_run($user, $action);
		_quickbooks_set_last_run($user, $action);			// Update the last run time to NOW()
		
		// Set the current run to $last
		_quickbooks_set_current_run($user, $action, $last);
	}
	else
	{
		// This is a continuation of a batch
		$attr_iteratorID = ' iteratorID="' . $extra['iteratorID'] . '" ';
		$attr_iterator = ' iterator="Continue" ';
		
		$last = _quickbooks_get_current_run($user, $action);
	}
	
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="' . $version . '"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<VendorQueryRq ' . $attr_iterator . ' ' . $attr_iteratorID . ' requestID="' . $requestID . '">
					<MaxReturned>' . QB_QUICKBOOKS_MAX_RETURNED . '</MaxReturned>
					<FromModifiedDate>' . $last . '</FromModifiedDate>
					<OwnerID>0</OwnerID>
				</VendorQueryRq>	
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

/** 
 * Handle a response from QuickBooks 
 */
function _quickbooks_vendor_import_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	

	if (!empty($idents['iteratorRemainingCount']))
	{
		// Queue up another request
		
		$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();
		$Queue->enqueue(QUICKBOOKS_IMPORT_VENDOR, null, QB_PRIORITY_VENDOR, array( 'iteratorID' => $idents['iteratorID'] ));
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
		$List = $Root->getChildAt('QBXML/QBXMLMsgsRs/VendorQueryRs');
		
               
              
		foreach ($List->children() as $Vendor)
		{
                    
			$arr = array(
				'id' => $Vendor->getChildDataAt('VendorRet ListID'),
				'name' => $Vendor->getChildDataAt('VendorRet Name'),
				'is_active' => $Vendor->getChildDataAt('VendorRet IsActive'),
				'company_name' => $Vendor->getChildDataAt('VendorRet CompanyName'),
				'salutation' => $Vendor->getChildDataAt('VendorRet Salutation'),
				'first_name' => $Vendor->getChildDataAt('VendorRet FirstName'),
				'middle_name' => $Vendor->getChildDataAt('VendorRet MiddleName'),
				'last_name' => $Vendor->getChildDataAt('VendorRet LastName'),
				'addr1' => $Vendor->getChildDataAt('VendorRet VendorAddress Addr1'),
				'addr2' => $Vendor->getChildDataAt('VendorRet VendorAddress Addr2'),
				'addr3' => $Vendor->getChildDataAt('VendorRet VendorAddress Addr3'),
				'city' => $Vendor->getChildDataAt('VendorRet VendorAddress City'),
				'state' => $Vendor->getChildDataAt('VendorRet VendorAddress State'),
				'zip' => $Vendor->getChildDataAt('VendorRet VendorAddress PostalCode'),
				'address_note' => $Vendor->getChildDataAt('VendorAddress Note'),
				'phone' => $Vendor->getChildDataAt('VendorRet Phone'),
				'alt_phone' => $Vendor->getChildDataAt('VendorRet AltPhone'),
				'fax' => $Vendor->getChildDataAt('VendorRet Fax'),
				'email' => $Vendor->getChildDataAt('VendorRet Email'),
				'contact' => $Vendor->getChildDataAt('VendorRet Contact'),
				'alt_contact' => $Vendor->getChildDataAt('VendorRet AltContact'),
				'notes' => $Vendor->getChildDataAt('VendorRet Notes'),
				'balance' => $Vendor->getChildDataAt('VendorRet Balance'),
				'created' => $Vendor->getChildDataAt('VendorRet TimeCreated'),
				'modified' => $Vendor->getChildDataAt('VendorRet TimeModified')
				);
			//file_put_contents('/customerdata.txt', print_r($arr, true), FILE_APPEND);
			QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'Importing vendor ' . $arr['name'] . ': ' . print_r($arr, true));
			
			foreach ($arr as $key => $value)
			{
				$arr[$key] = mysql_real_escape_string($value);
			}
			
			// Store the invoices in MySQL
			mysql_query("
				REPLACE INTO
					vendors
				(
					" . implode(", ", array_keys($arr)) . "
				) VALUES (
					'" . implode("', '", array_values($arr)) . "'
				)") or die(trigger_error(mysql_error()));
		}
	}
	
	return true;
}

/**
 * Build a request to import customers already in QuickBooks into our application
 */
function _quickbooks_employee_import_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	
		$last = _quickbooks_get_last_run($user, $action);
		_quickbooks_set_last_run($user, $action);			// Update the last run time to NOW()
		
		// Set the current run to $last
		_quickbooks_set_current_run($user, $action, $last);
	
	
	// Build the request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="' . $version . '"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<EmployeeQueryRq>
                                            <ActiveStatus>All</ActiveStatus>
                                            <FromModifiedDate>' . $last . '</FromModifiedDate>
                                            <OwnerID>0</OwnerID>
				</EmployeeQueryRq>	
			</QBXMLMsgsRq>
		</QBXML>';
		
        
	return $xml;
}

/** 
 * Handle a response from QuickBooks 
 */
function _quickbooks_employee_import_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
   
	if (!empty($idents['iteratorRemainingCount']))
	{
		// Queue up another request
		
		$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();
		$Queue->enqueue(QUICKBOOKS_IMPORT_EMPLOYEE, null, QB_PRIORITY_EMPLOYEE, array( 'iteratorID' => $idents['iteratorID'] ));
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
		$List = $Root->getChildAt('QBXML/QBXMLMsgsRs/EmployeeQueryRs');
		
                
		foreach ($List->children() as $Employee)
		{
                    
                    $arr = array(
				'id' => $Employee->getChildDataAt('EmployeeRet ListID'),
				'created' => $Employee->getChildDataAt('EmployeeRet TimeCreated'),
				'modified' => $Employee->getChildDataAt('EmployeeRet TimeModified'),
				'name' => $Employee->getChildDataAt('EmployeeRet Name'),
                            'is_active' => $Employee->getChildDataAt('EmployeeRet IsActive'),
				'full_name' => $Employee->getChildDataAt('EmployeeRet FullName'),
				'first_name' => $Employee->getChildDataAt('EmployeeRet FirstName'),
				'middle_name' => $Employee->getChildDataAt('EmployeeRet MiddleName'),
				'last_name' => $Employee->getChildDataAt('EmployeeRet LastName'),
				'salutation' => $Employee->getChildDataAt('EmployeeRet Salutation'),
				'addr1' => $Employee->getChildDataAt('EmployeeRet EmployeeAddress Addr1'),
				'addr2' => $Employee->getChildDataAt('EmployeeRet EmployeeAddress Addr2'),
				'city' => $Employee->getChildDataAt('EmployeeRet EmployeeAddress City'),
				'state' => $Employee->getChildDataAt('EmployeeRet EmployeeAddress State'),
				'zip' => $Employee->getChildDataAt('EmployeeRet EmployeeAddress PostalCode'),
                            'phone' => $Employee->getChildDataAt('EmployeeRet Phone'),
                            'mobile' => $Employee->getChildDataAt('EmployeeRet Mobile'),
                        'alt_phone' => $Employee->getChildDataAt('EmployeeRet AltPhone'),
                            'pager' => $Employee->getChildDataAt('EmployeeRet Pager'),
                            'fax' => $Employee->getChildDataAt('EmployeeRet Fax'),
                            'email' => $Employee->getChildDataAt('EmployeeRet Email'),
                            'pager_pin' => $Employee->getChildDataAt('EmployeeRet PagerPIN'),
                            'employee_type' => $Employee->getChildDataAt('EmployeeRet EmployeeType'),
                            'gender' => $Employee->getChildDataAt('EmployeeRet Gender'),
                            'hired_date' => $Employee->getChildDataAt('EmployeeRet HiredDate'),
                        'released_date' => $Employee->getChildDataAt('EmployeeRet ReleasedDate'),
                            'birth_date' => $Employee->getChildDataAt('EmployeeRet BirthDate'),
                            'pay_period' => $Employee->getChildDataAt('EmployeeRet EmployeePayrollInfo PayPeriod'),
                            'use_time_data' => $Employee->getChildDataAt('EmployeeRet EmployeePayrollInfo IsUsingTimeDataToCreatePaychecks'),
                            'notes' => $Employee->getChildDataAt('EmployeeRet Notes'),
                            'vendor_id' => null
                            );
                    
                
                       
			
                        $queryString = "
				SELECT `id` FROM 
					`vendors`
				WHERE
                                 `name` LIKE '%" . $arr['first_name'] . "%" . "" .
                                 $arr['last_name'] . "%' OR `email` = '" . $arr['email'] . "' LIMIT 1";
                        
                        // QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, "First query::" . ": " . $queryString);
                        
                        $result = mysql_query($queryString) or die(trigger_error(mysql_error()));
                        $res = mysql_fetch_row($result)[0];
                   //     QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, $arr['first_name'] . ': ' . ': ' . print_r($res, true));
                        
                        if(isset($res) && !empty($res))
                            $arr['vendor_id'] = $res;
                        else
                            $arr['vendor_id'] = "";
                        
                     //    QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'Importing employee ' . $arr['name'] . ': ' . print_r($arr, true));
                         
			foreach ($arr as $key => $value)
			{
				$arr[$key] = mysql_real_escape_string($value);
			}
			
                        $query = "
				INSERT INTO
					users
				(
					" . implode(", ", array_keys($arr)) . "
				) VALUES (
					'" . implode("', '", array_values($arr)) . "'
				) ON DUPLICATE KEY UPDATE ";
                                foreach($arr as $key => $value)
                        {
                            $query .= $key . "='" . $value . "', ";
                                }
                                $query = substr($query,0,-1);
                                $query .= ";"
                                
                                ;
                        
                   //     QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'MYSQL QUERY: ' . print_r($query, true));
                         
			// Store the invoices in MySQL
			mysql_query($query) or die(trigger_error(mysql_error()));
                    
                   
			
			
		}
	}
	
	return true;
}

/**
 * Build a request to import customers already in QuickBooks into our application
 */
function _quickbooks_class_import_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	
		$last = _quickbooks_get_last_run($user, $action);
		_quickbooks_set_last_run($user, $action);			// Update the last run time to NOW()
		
		// Set the current run to $last
		_quickbooks_set_current_run($user, $action, $last);
	
	
	// Build the request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="' . $version . '"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<ClassQueryRq>
                                            <ActiveStatus>All</ActiveStatus>
                                            <FromModifiedDate>' . $last . '</FromModifiedDate>
                                           
				</ClassQueryRq>	
			</QBXMLMsgsRq>
		</QBXML>';
		
        
	return $xml;
}

/** 
 * Handle a response from QuickBooks 
 */
function _quickbooks_class_import_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
   
	if (!empty($idents['iteratorRemainingCount']))
	{
		// Queue up another request
		
		$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();
		$Queue->enqueue(QUICKBOOKS_IMPORT_CLASS, null, QB_PRIORITY_CLASS, array( 'iteratorID' => $idents['iteratorID'] ));
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
		$List = $Root->getChildAt('QBXML/QBXMLMsgsRs/ClassQueryRs');
		
                
		foreach ($List->children() as $Employee)
		{
                    
                    $arr = array(
				'id' => $Employee->getChildDataAt('ClassRet ListID'),
				'created' => $Employee->getChildDataAt('ClassRet TimeCreated'),
				'modified' => $Employee->getChildDataAt('ClassRet TimeModified'),
				'name' => $Employee->getChildDataAt('ClassRet Name'),
                            'is_active' => $Employee->getChildDataAt('ClassRet IsActive'),
				'full_name' => $Employee->getChildDataAt('ClassRet FullName'),
				'parent_id' => $Employee->getChildDataAt('ClassRet ParentRef ListID'),
                            'sub_level' => $Employee->getChildDataAt('ClassRet Sublevel'),
                        
                            );
                    
                
                       
			
                       
			foreach ($arr as $key => $value)
			{
				$arr[$key] = mysql_real_escape_string($value);
			}
			
                        $query = "
				REPLACE INTO
					classes
				(
					" . implode(", ", array_keys($arr)) . "
				) VALUES (
					'" . implode("', '", array_values($arr)) . "'
				)";
                        
                   //     QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'MYSQL QUERY: ' . print_r($query, true));
                         
			// Store the invoices in MySQL
			mysql_query($query) or die(trigger_error(mysql_error()));
                    
                   
			
			
		}
	}
	
	return true;
}

/**
 * Build a request to import customers already in QuickBooks into our application
 */
function _quickbooks_payrollitem_import_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	
		$last = _quickbooks_get_last_run($user, $action);
		_quickbooks_set_last_run($user, $action);			// Update the last run time to NOW()
		
		// Set the current run to $last
		_quickbooks_set_current_run($user, $action, $last);
	
	
	// Build the request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="' . $version . '"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<PayrollItemWageQueryRq>
                                            <ActiveStatus>All</ActiveStatus>
                                            <FromModifiedDate>' . $last . '</FromModifiedDate>
                                           
				</PayrollItemWageQueryRq>	
			</QBXMLMsgsRq>
		</QBXML>';
		
        
	return $xml;
}

/** 
 * Handle a response from QuickBooks 
 */
function _quickbooks_payrollitem_import_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
   
	if (!empty($idents['iteratorRemainingCount']))
	{
		// Queue up another request
		
		$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();
		$Queue->enqueue(QUICKBOOKS_IMPORT_CLASS, null, QB_PRIORITY_CLASS, array( 'iteratorID' => $idents['iteratorID'] ));
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
		$List = $Root->getChildAt('QBXML/QBXMLMsgsRs/PayrollItemWageQueryRs');
		
                
		foreach ($List->children() as $Employee)
		{
                    
                    $arr = array(
				'id' => $Employee->getChildDataAt('PayrollItemWageRet ListID'),
				'created' => $Employee->getChildDataAt('PayrollItemWageRet TimeCreated'),
				'modified' => $Employee->getChildDataAt('PayrollItemWageRet TimeModified'),
				'name' => $Employee->getChildDataAt('PayrollItemWageRet Name'),
                            'is_active' => $Employee->getChildDataAt('PayrollItemWageRet IsActive'),
				'wage_type' => $Employee->getChildDataAt('PayrollItemWageRet WageType'),
				
                        
                            );
                    
                
                       
			
                       
			foreach ($arr as $key => $value)
			{
				$arr[$key] = mysql_real_escape_string($value);
			}
			
                        $query = "
				REPLACE INTO
					payroll_items
				(
					" . implode(", ", array_keys($arr)) . "
				) VALUES (
					'" . implode("', '", array_values($arr)) . "'
				)";
                        
                   //     QuickBooks_Utilities::log(QB_QUICKBOOKS_DSN, 'MYSQL QUERY: ' . print_r($query, true));
                         
			// Store the invoices in MySQL
			mysql_query($query) or die(trigger_error(mysql_error()));
                    
                   
			
			
		}
	}
	
	return true;
}
/**
 * Handle a 500 not found error from QuickBooks
 * 
 * Instead of returning empty result sets for queries that don't find any 
 * records, QuickBooks returns an error message. This handles those error 
 * messages, and acts on them by adding the missing item to QuickBooks. 
 */
function _quickbooks_error_e500_notfound($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg)
{
	$Queue = QuickBooks_WebConnector_Queue_Singleton::getInstance();
	
	if ($action == QUICKBOOKS_IMPORT_INVOICE)
	{
		return true;
	}
	else if ($action == QUICKBOOKS_IMPORT_CUSTOMER)
	{
		return true;
	}
        else if ($action == QUICKBOOKS_IMPORT_VENDOR)
	{
		return true;
	}
	else if ($action == QUICKBOOKS_IMPORT_SALESORDER)
	{
		return true;
	}
	else if ($action == QUICKBOOKS_IMPORT_ITEM)
	{
		return true;
	}
	else if ($action == QUICKBOOKS_IMPORT_PURCHASEORDER)
	{
		return true;
	}
        else if ($action == QUICKBOOKS_IMPORT_EMPLOYEE)
	{
		return true;
	}
        else if ($action == QUICKBOOKS_IMPORT_TIMETRACKING)
	{
		return true;
	}
        else if ($action == QUICKBOOKS_IMPORT_CLASS)
	{
		return true;
	}
        else if ($action == QUICKBOOKS_IMPORT_PAYROLLITEMWAGE)
	{
		return true;
	}
	else if ($action == QUICKBOOKS_IMPORT_BILL)
	{
		return true;
	}
        else if ($action == QUICKBOOKS_ADD_TIMETRACKING)
	{
		return true;
	}
        else if ($action == QUICKBOOKS_ADD_BILL)
	{
		return true;
	}
        
	return false;
}


/**
 * Catch any errors that occur
 * 
 * @param string $requestID			
 * @param string $action
 * @param mixed $ID
 * @param mixed $extra
 * @param string $err
 * @param string $xml
 * @param mixed $errnum
 * @param string $errmsg
 * @return void
 */
function _quickbooks_error_catchall($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg)
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
	
	//mail(QB_QUICKBOOKS_MAILTO, 
	//	'QuickBooks error occured!', 
	//	$message);
}
