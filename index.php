<?php

//We need to pull in the Sag PHP library. SAG is an open API used to connect to the Cloudant database.
 //We only need to do this once!
 require('vendor/autoload.php');

//don't try this at home - but it works for now
 $myUsername = "18e196e9-549a-4963-8281-fd6d4dff4381-bluemix";
 $myPassword = "a33e392c6c7ad92ce784c2e37b3fa15912c6ee34fdcb3dde5296ef293fcc8cab";

 try {
  // Let's login to the database.
  $sag = new Sag($myUsername . ".cloudant.com");
  $sag->login($myUsername, $myPassword);
  $sag->setDatabase("tweets");

  $resp = $sag->get('_design/views/_view/tweet-view')->body->rows;

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
									<span class="symbol"><img src="images/logo.svg" alt="" /></span>
                                    <span class="title">Leaky Rivers</span>
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
								<div id="map" class="map"></div>
                                <div id="info" class="info"></div>
							</header>

							<section class="tiles">

                                <?php foreach($resp as &$value)
                                    echo '<article class="style1">
                                        <span class="image">
                                            <img src="images/pic01.jpg" alt="" />
                                        </span>
                                        <a href="generic.html">
                                            <h2>' . $value->value->text . '</h2>
                                            <div class="content">
                                                <p>' . $value->value->name . '</p>
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
									<li><a href="#" class="icon style2 fa-twitter"><span class="label">Twitter</span></a></li>
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

              marker.on('click', function (e) {
      var $pop = $("#popup").dialog({
          minWidth: 100,
          minHeight: 100,
          position: {
              'of':  e.browserEvent.getBrowserEvent()
          }
      });

      $pop.html('<h3>clicked on <strong>'+ e.content +'</strong></h3>');

      map.addOverlay({
          element: $pop
      });
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
    }

    var map = new ol.Map({
    layers: [raster, vector],
    target: document.getElementById('map'),
    view: new ol.View({
    center: [0, 0],
    projection: projection,
    zoom: 10
    })
    });

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
