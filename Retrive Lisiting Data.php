<?php
	error_reporting(1);	
	require_once('lib/phrets.php');
	/////////////////////////// LOGIN INFO /////////////////////////////
	$login = 'http://rets.torontomls.net:6103/rets-treb3pv/server/login';	// Will not work in GODaddy Server or some servers so be carefull before choosing Hosting Provider for RETS API.
	$un = 'username'; // Provide your RETS username
	$pw = 'password'; // Provide your RETS password
	////////////////////////////////////////////////////////////////////

	$rets = new PHRETS;
	$connect = $rets->Connect($login, $un, $pw);	
	if ($connect) {
		echo "Connection Successfull <br/>";		
		
		echo "Location : Markham <br/>";
		echo "Type:  ResidentialProperty/CondoProperty  <br/>"; // Give the property Type to pull data. ResidentialProperty/CondoProperty
		echo "Query:  Municipality_code:'09.03'<br/>";		// Sample City Markham, Canada Data pulled using Muncipality Code
		echo "Date : ".date('Y-m-d', strtotime('-7 days')); // Setting date to 1 week earlier to pull RETS property listings.
		
		$queryStringData = "Municipality_code:'09.03',(Timestamp_sql=".date('Y-m-d', strtotime('-7 days'))."+))";
		
		$search = $rets->SearchQuery('Property', "ResidentialProperty", $queryStringData, array('Limit' => 1000)); // Pulling RETS data from API with max count 1000 and last updated 1 week earlier
		
		/* If search returned results */
		echo "Total Records Found ".$rets->TotalRecordsFound(). " <br/>";;
		while ($data = $rets->FetchRow($search)) {		
			echo json_encode($data)."<br/>"; // Printing JSON data of each property. You can either save to DB here.
		}	
		$rets->FreeResult($search);
		$rets->Disconnect();
	} else {
		$error = $rets->Error();
		print_r($error);
	}

?>
