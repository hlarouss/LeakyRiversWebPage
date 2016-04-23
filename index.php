<!DOCTYPE html>
<html>
<head>
	<title>PHP Starter Application</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="style.css" />
</head>
<body>
	<table>
		<tr>
			<td style='width: 30%;'><img class = 'newappIcon' src='images/newapp-icon.png'>
			</td>
			<td>
				<h1 id = "message"><?php echo "Bye world!"; ?>
</h1>
				<p class='description'></p> Thanks for creating a <span class="blue">PHP Starter Application</span>. Get started by reading our <a
				href="https://www.ng.bluemix.net/docs/#runtimes/php/index.html">documentation</a>
				or use the Start Coding guide under your app in your dashboard.

				<?php
					echo("my user " . $myUsername);
				?>
			</td>
		</tr>
	</table>
</body>
</html>

<?php

//We need to pull in the Sag PHP library. SAG is an open API used to connect to the Cloudant database.
 //We only need to do this once!
 require('vendor/autoload.php');
//Get Connection Variables from VCAPS_SERVICES. We first need to pull in our Cloudant database
//connection variables from the VCAPS_SERVICES environment variable. This environment variable
//will be put in your project by Bluemix once you add the Cloudant database to your Bluemix
//application.
// vcap_services Extraction
$services_json = json_decode(getenv('VCAP_SERVICES'),true);
$VcapSvs = $services_json["cloudantNoSQLDB"][0]["credentials"];
//Debug: If you want to see all the variables returned you can use this line of code.
//var_dump($services_json);
// Extract the VCAP_SERVICES variables for Cloudant connection.
 $myUsername = $VcapSvs["username"];
 $myPassword = $VcapSvs["password"];

 try {
  // Let's login to the database.
  $sag = new Sag($myUsername . ".cloudant.com");
  $sag->login($myUsername, $myPassword);



 ?>
