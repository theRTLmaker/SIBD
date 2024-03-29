<!DOCTYPE html>
<html>
<head>
	<title>View Consult</title>
	<script>
	function goBack() {
		window.history.back()
	}
	// Reload page when going back in the history
	window.addEventListener( "pageshow", function ( event ) {
	  var historyTraversal = event.persisted || 
	                         ( typeof window.performance != "undefined" && 
	                              window.performance.navigation.type === 2 );
	  if ( historyTraversal ) {
	    // Handle page restore.
	    window.location.reload();
	  }
	});
	</script>
</head>
<body>
	<h1>Consultation</h1>
	<?php
	$host="db.ist.utl.pt";
	$user="ist425355";	
	$password="emyg3992";
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
	if(isset($_REQUEST['VAT_client'])) {
		$VAT_client = $_REQUEST['VAT_client'];
		echo("VAT: ");
		echo($VAT_client);
	}
	// Gets the VAT of the selected client
	if(isset($_REQUEST['Client_Name'])) {
		$Client_Name = $_REQUEST['Client_Name'];
		echo(" Name: ");
		echo($Client_Name);
	}
	// Show the received client
	echo("<p><b>Doctor - </b>");
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

	$consult = 0;

	//this is in the past
	//search consultations
	$query = 'SELECT * FROM consultation NATURAL JOIN consultation_assistant WHERE VAT_doctor = :VAT_doctor and date_timestamp=:date_timestamp;';
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
		//No consult found

		$Edit = 0;
		echo("<p>No consultation found.</p>");
		echo("<p><a href=\"../appToConsult.php/?VAT_doctor=");
		echo($VAT_doctor);
		echo("&date_timestamp=");
		echo($date_timestamp);
		echo("&VAT_client=");
		echo($VAT_client);
		echo("&Client_Name=");
		echo($Client_Name);
		echo("&Edit=");
		echo($Edit);
		echo("\">");
		echo("Create consultation</a></p>");
	}
	else {
		$consult = 1;
		//display consultation info in table
		echo("<table border=\"1\">");
		echo("<tr><td>VAT_doctor</td><td>S</td><td>O</td><td>A</td><td>P</td><td>Nurse VAT</td></tr>");
		foreach ($result as $row) {
			echo("<tr><td>");
			echo($row['VAT_doctor']);
			echo("</td>");
			echo("<td>");
			echo($row['SOAP_S']);
			echo("</td>");
			echo("<td>");
			echo($row['SOAP_O']);
			echo("</td>");
			echo("<td>");
			echo($row['SOAP_A']);
			echo("</td>");
			echo("<td>");
			echo($row['SOAP_P']);
			echo("<td>");
			echo($row['VAT_nurse']);
			echo("</td></tr>");
		}
			
		echo("</table>");
		$Edit = 1;
		echo("<p><a href=\"../appToConsult.php/?VAT_doctor=");
		echo($VAT_doctor);
		echo("&date_timestamp=");
		echo($date_timestamp);
		echo("&VAT_client=");
		echo($VAT_client);
		echo("&Client_Name=");
		echo($Client_Name);
		echo("&Edit=");
		echo($Edit);
		echo("\">");
		echo("Edit consultation</a></p>");

		if($consult == 1) {

			echo("<h2>Diagnoses and Prescriptions</h2>");

			//check for diagnostics and prescriptions
			$query = 'SELECT cd.ID AS ID, name, lab, dosage, description from consultation_diagnostic cd LEFT OUTER JOIN prescription p ON cd.VAT_doctor = p.VAT_doctor AND cd.date_timestamp = p.date_timestamp AND cd.ID=p.ID WHERE cd.VAT_doctor=:VAT_doctor and cd.date_timestamp=:date_timestamp ORDER BY ID;';
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
				//No diagnosis found
				echo("<p>No diagnoses found - Add them by Editing the consultation.</p>");
				
			} else{
				//Display Diagnosis in table
				echo("<p>Diagnoses found.</p>");
				$prev_ID = -1;
				$first = 1;
				foreach ($result as $row) {
					if($prev_ID != $row['ID']) {
						if($first == 0) {
							echo("</table>");
						}
						$prev_ID = $row['ID'];

						echo("<h4>Diagnosis ID: ");
						echo($row['ID']);
						echo("   ");

						echo("<button><a href=\"../addprescription.php/?VAT_doctor=");
						echo($VAT_doctor);
						echo("&date_timestamp=");
						echo($date_timestamp);
						echo("&VAT_client=");
						echo($VAT_client);
						echo("&Client_Name=");
						echo($Client_Name);
						echo("&id=");
						echo($row['ID']);
						echo("\">");
						echo("Add Prescription</a></button>");
						echo("</h4>");
						echo("<p> </p>");
						if(!empty($row['name'])) {
							echo("<table border=\"1\">");
							echo("<tr><td>Medication Name</td><td>Medication Lab</td><td>Medication Dosage</td><td>Medication Desription</td></tr>");
						}
					}
					if(!empty($row['name'])) {
						echo("<tr><td>");
						echo($row['name']);
						echo("</td>");
						echo("<td>");
						echo($row['lab']);
						echo("</td>");
						echo("<td>");
						echo($row['dosage']);
						echo("</td>");
						echo("<td>");
						echo($row['description']);
						echo("</td></tr>");
					}

					$first = 0;
				}
				echo("</table>");
			}

			echo("<h2>Procedure in Consultation</h2>");

			//check for procedure in consultation
			$query = 'SELECT * FROM procedure_in_consultation WHERE VAT_doctor = :VAT_doctor AND date_timestamp = :date_timestamp';
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
				//No diagnosis found
				echo("<p>No procedures found.</p>");
			
				
			}
			else {
				//Display Diagnosis in table
				echo("<p>Procedures found.</p>");
				echo("<table border=\"1\">");
				echo("<tr><td>Procedure</td><td>Doctor VAT</td><td>Date</td><td>Description</td></tr>");
				foreach ($result as $row) {
					echo("<tr><td>");
					echo($row['name']);
					echo("</td>");
					echo("<td>");
					echo($row['VAT_doctor']);
					echo("</td>");
					echo("<td>");
					echo($row['date_timestamp']);
					echo("</td>");
					echo("<td>");
					echo($row['description']);
					echo("</td>");
					if($row['name'] == "Dental Charting") {
						echo("<td>");	
						echo("<a href=\"../add_procedure_charting.php/?VAT_doctor=");
						echo($VAT_doctor);
						echo("&date_timestamp=");
						echo($date_timestamp);
						echo("&VAT_client=");
						echo($VAT_client);
						echo("&Client_Name=");
						echo($Client_Name);
						echo("\">");
						echo("Add/View Procedure Charting</a>");
						echo("</td></tr>");
					}
				}
				echo("</table>");


			}
			// Add Procedure charting
			?>
			<form action='../addprocedure.php' method='post'>
			<input type="hidden" name="VAT_client" value="<?=$VAT_client?>">
			<input type="hidden" name="VAT_doctor" value="<?=$VAT_doctor?>">
			<input type="hidden" name="date_timestamp" value="<?=$date_timestamp?>">
			<input type="hidden" name="time" value="<?=$time?>">
			<p><input type="submit" name="submit" value="Add Procedure"/></p>
			</form>
			<?php
		}
		echo("<p> </p>");
	
	}

	$connection = null;
	?>

<button onclick="goBack()">Go Back</button>
<button><a href="../homepage.php">Homepage</button>
</body>
</html>
