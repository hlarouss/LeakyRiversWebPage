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
echo '<p>There Was an Error Getting Data from Cloudant!!!</p>';
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
        <link rel="stylesheet" href="http://openlayers.org/en/v3.15.1/css/ol.css" type="text/css">
        <script src="http://openlayers.org/en/v3.15.1/build/ol.js"></script>
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
								<a href="index.html" class="logo">
									<span><img src="images/logo.png" alt="" /></span>
                                    <!--<span class="title">Leaky Rivers</span>-->
								</a>

							<!-- Nav -->
								<!-- <nav>
									<ul>
										<li><a href="#menu">Menu</a></li>
									</ul>
								</nav> -->

						</div>
					</header>

				<!-- Menu -->
					<!-- <nav id="menu">
						<h2>Menu</h2>
						<ul>
							<li><a href="index.html">Home</a></li>
							<li><a href="generic.html">Ipsum veroeros</a></li>
							<li><a href="generic.html">Tempus etiam</a></li>
							<li><a href="generic.html">Consequat dolor</a></li>
							<li><a href="elements.html">Elements</a></li>
						</ul>
					</nav> -->

				<!-- Main -->
					<div id="main">
						<div class="inner">
							<header>
                                <div id="popup" class="popup"></div>
                                <div id="popup-content" class="popup-content"></div>
                                <div id="popup-closer" class="popup-closer"></div>
								<div id="map" class="map"></div>
                                <div id="info" class="info"></div>
							</header>

							<section class="tiles">

                                 <?php foreach($weather as &$value)
                                 echo '<article class="style1">
                                     <span class="image">
                                         <img src="images/pic01.jpg" alt="" />
                                     </span>
                                     <a href="generic.html">
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
                                     <a href="generic.html">
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
                                     <a href="generic.html">
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
								<!-- <form method="post" action="#">
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
								</form> -->
							</section>
							<section>
								<h2>Follow</h2>
								<ul class="icons">
									<li><a href="http://twitter.com/leakyrivers" class="icon style2 fa-twitter"><span class="label">Twitter</span></a></li>
									<!-- <li><a href="#" class="icon style2 fa-facebook"><span class="label">Facebook</span></a></li>
									<li><a href="#" class="icon style2 fa-instagram"><span class="label">Instagram</span></a></li>
									<li><a href="#" class="icon style2 fa-dribbble"><span class="label">Dribbble</span></a></li>
									<li><a href="#" class="icon style2 fa-github"><span class="label">GitHub</span></a></li>
									<li><a href="#" class="icon style2 fa-500px"><span class="label">500px</span></a></li>
									<li><a href="#" class="icon style2 fa-phone"><span class="label">Phone</span></a></li>
									<li><a href="#" class="icon style2 fa-envelope-o"><span class="label">Email</span></a></li> -->
								</ul>
							</section>
							<ul class="copyright">
								<li>&copy; Leaky Rivers. All rights reserved</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
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
    url: 'data/kml/water.kml',
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

    var tweets;
    var features = [];
    var styles = [];

    function displayTweets() {
        tweets = <?php echo json_encode($resp); ?>;

        for (i = 0; i < tweets.length; i++) {

            if (!styles[i]) {
   // In your case you will want to use  image : new ol.style.Icon(({
   // but this is the example that I have on hand..
               styles[i] = new ol.style.Style({
                 image: new ol.style.Circle({
                   radius: 5,
                //    stroke: new ol.style.Stroke({
                //      color: '#000'
                //    }),
                   fill: new ol.style.Fill({
                     color: '#3AF' // attribute colour
                   })
                 }),
                 text: new ol.style.Text({
                   text: tweets[i].value.text, // attribute code
                   fill: new ol.style.Fill({
                     color: "#FFF" // black text // TODO: Unless circle is dark, then white..
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
            console.log(tweets[i].value.text);
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

    var displayFeatureInfo = function(pixel) {
    var features = [];
    map.forEachFeatureAtPixel(pixel, function(feature) {
    features.push(feature);
    });
    if (features.length > 0) {
    var info = [];
    var i, ii;
    for (i = 0, ii = features.length; i < ii; ++i) {
    info.push(features[i].get('name'));
    }
    document.getElementById('info').innerHTML = info.join(', ') || '(unknown)';
    map.getTarget().style.cursor = 'pointer';
    } else {
    document.getElementById('info').innerHTML = '&nbsp;';
    map.getTarget().style.cursor = '';
    }
    };

    map.on('pointermove', function(evt) {
    if (evt.dragging) {
    return;
    }
    var pixel = map.getEventPixel(evt.originalEvent);
    displayFeatureInfo(pixel);
    });

    map.on('click', function(evt) {
    displayFeatureInfo(evt.pixel);
    });
</script>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>
