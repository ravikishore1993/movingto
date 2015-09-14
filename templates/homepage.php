<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<title>HSIRAXAY</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>
	<div id="input-terms" class="top">
		HECK SHOULD I RENT A 
		<div>
			<span class="optionals">
				<span>
					<input type="text" id="category" placeholder="house">
				</span>
				<span class="randomtext"> 
					AT 
				</span>
				<span>
					<input type="text" id="location" onfocus="geolocate()" placeholder="">
				</span> ?
			</span>	
		</div>
	</div>
	<span class="optionals">
	<div id="buttonsdiv">
		<input type="submit" id="submitbutton" value="FIND">
	</div>
	</span>
	<div id="ajax-loader">
		<img src="assets/icons/ajax-loader.gif">
	</div>
	<div id="mapbox">
		<div id="seller">
			<span id="variablecategory"></span> from  <a target="blank" href="" id="sellerlink">this dealer on Quikr ?</a>
		</div>
		<div id="map">
			
		</div>
		<div id="alternatives">
			<div id="wronglocation" class="alternativelinks">
				<a id="wronglocationlink" class="alternativehrefs">This is not my location !!</a>
			</div>
			<div id="anotherdealer" class="alternativelinks">
				<a id="anotherdealerlink" class="alternativehrefs">Show another dealer ?</a>
			</div>
<!-- 			<div class="alternativelinks" id="reccomendations">
				
			</div> -->
		</div>
	</div>
	<div id="noresults">
		SORRY :( No Results. Try other <a href="/">location</a>
	</div>
	<div id="toomanyresults">
		Bro ! you better go to <a href="http://quikr.com">Quikr</a>
	</div>
</body>
	<script type="text/javascript" src="assets/scripts/script.js"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBaj9gTHLIh6s4NCE4JBzP_s8MV0MiCsnk&signed_in=true&libraries=places&callback=initAutocomplete"></script>
</html>
