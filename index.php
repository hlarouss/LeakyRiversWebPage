<?php

//We need to pull in the Sag PHP library. SAG is an open API used to connect to the Cloudant database.
 //We only need to do this once!
 require('vendor/autoload.php');
//Get Connection Variables from VCAPS_SERVICES. We first need to pull in our Cloudant database
//connection variables from the VCAPS_SERVICES environment variable. This environment variable
//will be put in your project by Bluemix once you add the Cloudant database to your Bluemix
//application.
// vcap_services Extraction

//Debug: If you want to see all the variables returned you can use this line of code.
//var_dump($services_json);
// Extract the VCAP_SERVICES variables for Cloudant connection.
 $myUsername = "40d4c471-ca2f-466c-922e-547cf658edb1-bluemix";
 $myPassword = "21a0afd60cb6dffd08de3ebe2a6db2256c42d0896d08d84e71e3bb0729df4fb1";

 try {
  // Let's login to the database.
  $sag = new Sag($myUsername . ".cloudant.com");
  $sag->login($myUsername, $myPassword);
  $sag->setDatabase("yo");
    // if(!$sag->put("myId", '{"myKey":"Hello World from Cloudant!"}')->body->ok) {
    //   error_log('Unable to post a document to Cloudant.');
    // } else {
  	  // We are now going to read a document from our cloudant database. We are going
  	  // to retrieve the value associated with myKey from the body of the document.
    	  //The SAG PHP library takes care of all the gory details and only retrieves the value.
  	  $resp = $sag->get('_design/nameview/_view/nameview')->body->rows;
    //   }

}
catch(Exception $e) {
//We sent something to Sag that it didn't expect.
echo '<p>There Was an Error Getting Data from Cloudant!!!</p>';
echo $e->getMessage();
}

 ?>


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
				<h1 id = "message"> <?php echo "Leaky Rivers"; ?> </h1>

				<p class='description'> testing json decode: </p>

                <?php echo 'query result: ' . var_dump($resp); ?>

			</td>
		</tr>
	</table>
</body>
</html>
