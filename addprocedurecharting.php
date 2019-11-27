<html>
<head>
	<title>Add Procedure Charting</title>
	<script>
	function goBack() {
		window.history.back()
	}
	function goBack2() {
		window.history.go(-2)
	}
	</script>
</head>
<body>
	<h1>Add Procedure Charting</h1>
	<?php
	$host="db.ist.utl.pt";
	$user="ist425355";  
	$password="emyg3992";
	$dsn = "mysql:host=$host;dbname=$user";
	$dbname = $user;  

	// Try to connect to the database
	try {
		$connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

	}
	catch(PDOException $exception) {
		echo("<p>Error: ");
		echo($exception->getMessage());
		echo("</p>");
		exit();
	}

	// Show the received client
	echo("<p><b>Client: </b>");
	// Gets the VAT of the selected client
	if(isset($_REQUEST['VAT_client'])) {
		$VAT_client = $_REQUEST['VAT_client'];
		echo("VAT: ");
		echo($VAT_client);
	}
	// Gets the VAT of the selected client
	if(isset($_REQUEST['Client_Name'])) {
		$Client_Name = $_REQUEST['Client_Name'];
		echo(" - Name: ");
		echo($Client_Name);
	}
	echo("</p>");

	// Show the received doctor
	echo("<p><b>Doctor: </b>");
	// Gets the VAT of the selected client
	if(isset($_REQUEST['VAT_doctor'])) {
		$VAT_doctor = $_REQUEST['VAT_doctor'];
		echo("VAT: ");
		echo($VAT_doctor);
	}
	echo("</p>");

	// Show the received doctor
	echo("<p><b>Procedure name: </b>");
	// Gets the VAT of the selected client
	if(isset($_REQUEST['name'])) {
		$name = $_REQUEST['name'];
		echo($name);
	}
	echo("</p>");

	
	
	?>
	  <form action='#' method='post'>
		<h3>Add measurements:</h3>

		<!-- Fazer uma pesquisa dos procedures existentes na consulta e apresentar um menu de opcao -->
	    
	    <input type="hidden" name="name" value="<?=$name?>">
	    <input type="hidden" name="VAT_doctor" value="<?=$VAT_doctor?>">
	    <input type="hidden" name="date_timestamp" value="<?=$date_timestamp?>">
		<?php
	    if (empty($_POST['dosage'])) {
	    	echo("<p><input type=\"submit\" name=\"submit\" value=\"Submit\"/></p>");
		}
		?>
	  </form>

	  <?php

if (isset($_POST['submit']))//to run PHP script on submit
{	
	$medication = $_POST['medication'];
	$arr = explode("_", $medication, 2);
	$name = $arr[0];
	$lab = $arr[1];
	$dosage = $_POST['dosage'];
	$description = $_POST['description'];
	$VAT_client = $_POST['VAT_client'];
	$VAT_doctor = $_POST['VAT_doctor'];
	$date_timestamp = $_POST['date_timestamp'];
	$id = $_POST['id'];
	echo("<h3>New Prescription Added</h3>");
	
	// Insert prescription
	$sql = "INSERT INTO prescription VALUES ('$name', '$lab', '$VAT_doctor', '$date_timestamp', '$id', '$dosage', '$description')";
	$nrows = $connection->exec($sql);
	if($nrows != 0) {
		echo("<p>Sucessfully added prescription</p>");
	}

	echo("<button onclick=\"goBack2()\">Go Back</button>");
	echo("<button><a href=\"../homepage.php\">Homepage</button>");
}

else {
	echo("<button onclick=\"goBack()\">Go Back</button>");
	echo("<button><a href=\"../homepage.php\">Homepage</button>");
}

	$connection = null;
?>
</body>
</html>