<?php
	date_default_timezone_set("America/Detroit");
	$day = date('z'); //+1 for current day#
	$day.="_";
	$prevday = date('z')-1;
	$prevday.="_";
	$addr = "http://oas.gsfc.nasa.gov/Products/";
	$loc="000E060N"; //tile/region according to http://oas.gsfc.nasa.gov/floodmap/
	$loc2="070E020N";
	$product="/MFW_2016";
	$preproc="_3D3OT_V.kmz"; //preprocessing specs
	$fname = "{$addr}{$loc}{$product}{$day}{$loc}{$preproc}"; //get data for NL
	$fname2 = "{$addr}{$loc2}{$product}{$prevday}{$loc2}{$preproc}"; //get data for INDIA
	//fname2 = "http://oas.gsfc.nasa.gov/Products/070E020N/MFW_2016";
	//http://oas.gsfc.nasa.gov/Products/070E020N/MFW_2016114_070E020N_3D3OT_V.kmz
	
	//NL
	$data = file_get_contents($fname); // url of the KMZ file
	file_put_contents("/tmp/kmz_temp",$data);
	ob_start();
	passthru('unzip -p /tmp/kmz_temp');
	$xml_data = ob_get_clean();
	header("Content-type: text/xml");
	file_put_contents("waterNL.kml", $xml_data);
	
	//INDIA - sorry for code replication - quick and dirty
	$data = file_get_contents($fname2); // url of the KMZ file
	file_put_contents("/tmp/kmz_temp",$data);
	ob_start();
	passthru('unzip -p /tmp/kmz_temp');
	$xml_data = ob_get_clean();
	header("Content-type: text/xml");
	file_put_contents("waterINDIA.kml", $xml_data);	
?>