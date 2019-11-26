<!DOCTYPE html>
<html>
<head>
	<title>Consults and Appointments</title>
</head>
<body>
	<h1>Consults and Appointments</h1>
	<?php
	$host="db.ist.utl.pt";
	$user="ist425362";	
	$password="txsq2828";
	$dbname = $user;	

	// Try to connect to the database
	try	{
		$connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

	}
	catch(PDOException $exception) {
		echo("<p>Error: ");
		echo($exception->getMessage());
		echo("</p>");
		exit();
	}

	// Show the received client
	echo("<p><b>Client - </b>");
	// Gets the VAT of the selected client
	if(isset($_REQUEST['VAT_doctor'])) {
		$VAT_doctor = $_REQUEST['VAT_doctor'];
		echo("VAT: ");
		echo($VAT_doctor);
	}
	// Gets the VAT of the selected client
	if(isset($_REQUEST['date_timestamp'])) {
		$date_timestamp = $_REQUEST['date_timestamp'];
		echo(" <b>date: </b>");
		echo($date_timestamp);
	}
	echo("</p>");

	date_default_timezone_set("Europe/London");
	$currentDate = date("Y-m-d H:i:s");
	echo("<p>The time is " . date("Y-m-d H:i:s") . "</p>");


	//search appointment info
	$query = 'SELECT * FROM appointment WHERE VAT_doctor = :VAT_doctor and date_timestamp=:date_timestamp;';
	$queryVariables = array();
	$queryVariables[':VAT_doctor'] = $VAT_doctor;
	$queryVariables[':date_timestamp'] = $date_timestamp;
	$sql = $connection->prepare($query);
	if(!$sql->execute($queryVariables)){
		$info = $connection->errorInfo();
		echo("<p>Error: {$info[2]}</p>");
		exit();
	}
	$result=$sql->fetchAll();

	if($result == 0) {
	    $info = $sql->errorInfo();
	    echo("<p>Error: {$info[2]}</p>");
	    exit();
	}

    if ($sql->rowCount() == 0) {
    	echo("<p>No appointment found.</p>");
	}

	//Display info in table
	echo("<table border=\"1\">");
	echo("<tr><td>Doctor VAT</td><td>Date/Time</td><td>Description</td><td>Client VAT</td></tr>");
	foreach ($result as $row) {
		echo("<tr><td>");
		echo($row['VAT_doctor']);
		echo("</td>");
		echo("<td>");
		echo($row['date_timestamp']);
		echo("</td>");
		echo("<td>");
		echo($row['description']);
		echo("</td>");
		echo("<td>");
		echo($row['VAT_client']);
		echo("</td></tr>");
	}
		
	echo("</table>");

			
		
		
	
	$connection = null;
	?>
