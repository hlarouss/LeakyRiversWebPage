<?php

//We need to pull in the Sag PHP library. SAG is an open API used to connect to the Cloudant database.
 //We only need to do this once!
 require('vendor/autoload.php');

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
     $sag->setDatabase("tweets");

     $resp = $sag->get('_design/views/_view/tweet-view')->body->rows;

     $sag->setDatabase("weather");
     $weather = $sag->get('_design/views/_view/weather-view')->body->rows;

    }
    catch(Exception $e) {
    //We sent something to Sag that it didn't expect.
    echo '<p>There Was an Error Getting Data from the database :/ </p>';
    echo $e->getMessage();
    }

 ?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Leaky Rivers</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
        <link rel="stylesheet" href="assets/css/ol.css" type="text/css">
        <script src="assets/js/ol.js"></script>
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	</head>
	<body onpageshow="getLocation();">
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<div class="inner">

							<!-- Logo -->
								<a href="index.php" class="logo">
									<span class="symbol"><img src="images/logo.png" alt="Leaky Rivers" /></span>
								</a>
                                <!-- <div class="field">
                                    <input type="text" name="location" id="location" placeholder="Location " />
                                </div> -->

							<!-- Nav -->
								<nav>
									<ul>
										<li><a href="#menu">Menu</a></li>
									</ul>
								</nav>

						</div>
					</header>

				<!-- Menu -->
					<nav id="menu">
						<h2>Menu</h2>
						<ul>
							<li><a href="index.php">Home</a></li>
							<li><a href="index.php">Change Location</a></li>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">
						<div class="inner">
							<header>
                                <!-- <div id="popup" class="popup"></div>
                                <div id="popup-content" class="popup-content"></div>
                                <div id="popup-closer" class="popup-closer"></div> -->
								<div id="map" class="map"></div>
                                <!-- <div id="info" class="info"></div> -->
							</header>

							<section class="tiles">

                                 <?php foreach($weather as &$value)
                                 echo '<article class="style1">
                                     <span class="image">
                                         <img src="images/pic01.jpg" alt="" />
                                     </span>
                                     <a href="#">
                                         <h2> ' . $value->value->phrase_12char . '</h2>
                                         <div class="content">
                                             <p>How is it looking?</p>
                                         </div>
                                     </a>
                                 </article>';

                                echo ' <article class="style2">
                                     <span class="image">
                                         <img src="images/pic01.jpg" alt="" />
                                     </span>
                                     <a href="#">
                                         <h2> ' . $value->value->sky_cover . ' </h2>
                                         <div class="content">
                                             <p>Sky cover</p>
                                         </div>
                                     </a>
                                 </article>';

                                 echo '<article class="style3">
                                     <span class="image">
                                         <img src="images/pic01.jpg" alt="" />
                                     </span>
                                     <a href="#">
                                         <h2> ' . $value->value->metric->temp . '&#x2103  </h2>
                                         <div class="content">
                                             <p>Temperature</p>
                                         </div>
                                     </a>
                                 </article>';
                                 ?>

							</section>
						</div>
					</div>

				<!-- Footer -->
					<footer id="footer">
						<div class="inner">
							<section>
								<h2>Get in touch</h2>
								<form method="post" action="messageUs.php">
									<div class="field half first">
										<input type="text" name="name" id="name" placeholder="Name" />
									</div>
									<div class="field half">
										<input type="email" name="email" id="email" placeholder="Email" />
									</div>
									<div class="field">
										<textarea name="message" id="message" placeholder="Message"></textarea>
									</div>
									<ul class="actions">
										<li><input type="submit" value="Send" class="special" /></li>
									</ul>
								</form>
							</section>
							<section>
								<h2>Follow</h2>
								<ul class="icons">
									<li><a href="http://twitter.com/LeakyRivers" class="icon style2 fa-twitter"><span class="label">Twitter</span></a></li>
									<li><a href="http://facebook.com/LeakyRivers" class="icon style2 fa-facebook"><span class="label">Facebook</span></a></li>
                                    <li><a href="https://github.com/LeakyRivers" class="icon style2 fa-github"><span class="label">GitHub</span></a></li>
								</ul>
							</section>
							<ul class="copyright">
								<li>Leaky Rivers 2016 - NASA SpaceAppChallenge.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
							</ul>
						</div>
					</footer>

			</div>

            <script>
    var projection = ol.proj.get('EPSG:3857');

    var raster = new ol.layer.Tile({
    source: new ol.source.BingMaps({
    imagerySet: 'Aerial',
    key: 'AqzR3QSX8denhtQSfY2k-RPalRm7QBcC9kolBk103fshoCpc6HIoIcdv3n9YcOt4'
    })
    });

    var vector = new ol.layer.Vector({
    source: new ol.source.Vector({
    url: 'data/kml/waterNL.kml',
    format: new ol.format.KML()
    })
    });

    var latlon;

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {

        }
    }

    function displayTweets() {
        var tweets = <?php echo json_encode($resp); ?>;
        var styles = [];
        var features = [];

        for (i = 0; i < tweets.length; i++) {

            if (!styles[i]) {

               styles[i] = new ol.style.Style({
                 image: new ol.style.Circle({
                   radius: 5,
                   stroke: new ol.style.Stroke({
                     color: '#000'
                   }),
                   fill: new ol.style.Fill({
                     color: '#3AF'
                   })
                 }),
                 text: new ol.style.Text({
                   text: tweets[i].value.text,
                   scale: 2,
                   fill: new ol.style.Fill({
                     color: "#FFF"
                   })
                 })
               });
             }

            var marker = new ol.Feature({
                content: tweets[i].value.text,
                mapid: i,
                geometry: new ol.geom.Point(
                  ol.proj.transform(tweets[i].value.coordinates,'EPSG:4326', 'EPSG:3857')
              )
              });

            marker.setStyle(styles[i]);
            features.push(marker);
        }

        var source = vector.getSource();
        source.addFeatures(features);

    }

    function showPosition(position) {
        latlon = [position.coords.longitude, position.coords.latitude];
        map.getView().setCenter(ol.proj.transform(latlon, 'EPSG:4326', 'EPSG:3857'));
       map.getView().setZoom(10);
       displayTweets();
    }

    // var container = document.getElementById('popup');
    // var content = document.getElementById('popup-content');
    // var closer = document.getElementById('popup-closer');
    //
    //
    // /**
    //  * Add a click handler to hide the popup.
    //  * @return {boolean} Don't follow the href.
    //  */
    // closer.onclick = function() {
    //   overlay.setPosition(undefined);
    //   closer.blur();
    //   return false;
    // };


    /**
     * Create an overlay to anchor the popup to the map.
     */
    // var overlay = new ol.Overlay(({
    //   element: container,
    //   autoPan: true,
    //   autoPanAnimation: {
    //     duration: 250
    //   }
    // }));

    var map = new ol.Map({
        layers: [raster, vector],
        target: document.getElementById('map'),
        view: new ol.View({
        center: [0, 0],
        //overlays: [overlay],
        //target: 'map',
        projection: projection,
        zoom: 10
        })
    });

    // map.on('singleclick', function(evt) {
    //     console.log(evt);
    //     //if(evt.text) {
    //       var coordinate = evt.coordinate;
    //       var text = evt.value.text;
    //       var user = evt.value.name;
    //
    //       content.innerHTML = '<p>' + name + ' said ' + text + '</p>';
    //       overlay.setPosition(coordinate);
    //     //}
    // });

</script>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>
