var markerVisitor = false;
var map;
var rayonProxi = 50;
var geolocOneTime = false;

var styles = [
	{
			"featureType": "water",
			"elementType": "geometry",
			"stylers": [
					{
							"color": "#a2daf2"
					}
			]
	},
	{
			"featureType": "landscape.man_made",
			"elementType": "geometry",
			"stylers": [
					{
							"color": "#f7f1df"
					}
			]
	},
	{
			"featureType": "landscape.natural",
			"elementType": "geometry",
			"stylers": [
					{
							"color": "#d0e3b4"
					}
			]
	},
	{
			"featureType": "landscape.natural.terrain",
			"elementType": "geometry",
			"stylers": [
					{
							"visibility": "off"
					}
			]
	},
	{
			"featureType": "poi.park",
			"elementType": "geometry",
			"stylers": [
					{
							"color": "#bde6ab"
					}
			]
	},
	/*{
			"featureType": "poi",
			"elementType": "labels",
			"stylers": [
					{
							"visibility": "off"
					}
			]
	},*/
	{
			"featureType": "poi.medical",
			"elementType": "geometry",
			"stylers": [
					{
							"color": "#fbd3da"
					}
			]
	},
	{
			"featureType": "poi.business",
			"stylers": [
					{
							"visibility": "off"
					}
			]
	},
	{
			"featureType": "road",
			"elementType": "geometry.stroke",
			"stylers": [
					{
							"visibility": "off"
					}
			]
	},
	/*{
			"featureType": "road",
			"elementType": "labels",
			"stylers": [
					{
							"visibility": "off"
					}
			]
	},*/
	{
			"featureType": "road.highway",
			"elementType": "geometry.fill",
			"stylers": [
					{
							"color": "#ffe15f"
					}
			]
	},
	{
			"featureType": "road.highway",
			"elementType": "geometry.stroke",
			"stylers": [
					{
							"color": "#efd151"
					}
			]
	},
	{
			"featureType": "road.arterial",
			"elementType": "geometry.fill",
			"stylers": [
					{
							"color": "#ffffff"
					}
			]
	},
	{
			"featureType": "road.local",
			"elementType": "geometry.fill",
			"stylers": [
					{
							"color": "black"
					}
			]
	},
	{
			"featureType": "transit.station.airport",
			"elementType": "geometry.fill",
			"stylers": [
					{
							"color": "#cfb2db"
					}
			]
	}
];

function initialize(){
	bounds = new google.maps.LatLngBounds();

	var mapOption = {mapTypeId: google.maps.MapTypeId.ROADMAP, disableDefaultUI : true , zoomControl : true};

	map = new google.maps.Map(document.getElementById('map_canvas'), mapOption);

  autocomplete = new google.maps.places.Autocomplete($("address"));

  google.maps.event.addListener(autocomplete, 'place_changed', autocompleteCallback);

	map.setOptions({styles: styles});

  infoWindow = new google.maps.InfoWindow();

	if(!geolocOneTime) {
		initGeoloc();
		initMapWithAllPoints();
		geolocOneTime = true;
	}
}

function getItineraire(lat, lng ){
    var destination = new google.maps.LatLng(lat, lng);
    if(markerVisitor){
    var origin = markerVisitor.getPosition();
    var request = {
        origin      : origin,
        destination : destination,
        travelMode  : google.maps.DirectionsTravelMode.DRIVING
    }

    var directionsService = new google.maps.DirectionsService();

    directionsService.route(request, function(response, status){
        if(status == google.maps.DirectionsStatus.OK){
            direction.setDirections(response);
        }
    });
    }
}

function getMyLatLng(){
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(function(position){
            var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        }, erreurPosition);
        return latlng;
    }
}

function loadScript() {
	  var script = document.createElement("script");
	  script.type = "text/javascript";
	  script.src = gmapUrl;
	  document.body.appendChild(script);
}

function initGeoloc(){
	if(apiSensor){
    if(navigator.geolocation) {
    	survId = navigator.geolocation.getCurrentPosition(maPosition,erreurPosition);
    }
	}
}

function maPosition(position) {

    latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

		var haveStores = initStores("", latlng);

		if(haveStores) {
			jQuery(".vide").html("");
		} else {
			initMapWithAllPoints();
			jQuery(".vide").html("<p>Actuellement aucun magasin n'est disponible dans cette zone.</p>");
		}
}

function erreurPosition(error) {
    var info = "Erreur lors de la géolocalisation : ";
    switch(error.code) {
        case error.TIMEOUT:
            info += "Timeout !";
            break;
        case error.PERMISSION_DENIED:
            info += "Vous n’avez pas donné la permission";
            break;
        case error.POSITION_UNAVAILABLE:
            info += "La position n’a pu être déterminée";
            break;
        case error.UNKNOWN_ERROR:
            info += "Erreur inconnue";
            break;
    }
}

function accentReplace( s ) {

	var rules = {
	'a': /[àáâãäå]+/g,
	'ae': /[æ]+/g,
	'c': /[ç]+/g,
	'e': /[èéêë]+/g,
	'i': /[ìíîï]+/g,
	'n': /[ñ]+/g,
	'o': /[òóôõö]+/g,
	'oe': /[œ]+/g,
	'u': /[ùúûü]+/g,
	'y': /[ýÿ]+/g,
	'-': /[\s\\]+/g };

	s = s.toLowerCase();
	for (var r in rules) s = s.replace(rules[r], r);
	return s;
}

function autocompleteCallback(){
	jQuery(".store-table").hide();

	var latLng =  new google.maps.LatLng(this.getPlace().geometry.location.lat(), this.getPlace().geometry.location.lng());

	var place = this.getPlace().name.replace("\'", "-");

	var haveStores = initStores(accentReplace(place), latLng);

	if(haveStores) {
		jQuery(".vide").html("");
	} else {
		initMapWithAllPoints();
		jQuery(".vide").html("<p>Actuellement aucun magasin n'est disponible dans cette zone.</p>");
	}
}

function initStores(place, coord){

	initialize();

	markers = new Array();

	haveMarker = false;
	nbMarker = 0;

	for(i=0; i< stores.items.length; i++) {

		var city = accentReplace(stores.items[i].city);

		city = city.replace("\'", "-");

		var latLng =  new google.maps.LatLng(stores.items[i].lat, stores.items[i].long);

		var distance = google.maps.geometry.spherical.computeDistanceBetween(latLng, coord) / 1000;

		if(city == place || distance < rayonProxi) {
			bounds.extend(latLng);

			jQuery("." + city.toUpperCase()).show();

			if(stores.items[i].marker){
				var imgMarker = new google.maps.MarkerImage(pathMarker+stores.items[i].marker);
			}else{
				if(defaultMarker){
					var imgMarker = new google.maps.MarkerImage(pathMarker+defaultMarker);
				}else{
					var imgMarker = '';
				}
			}

			markers[i] = new google.maps.Marker({position: latLng, icon: imgMarker,map: map, store: stores.items[i]});
			google.maps.event.addListener(markers[i], 'click', openWindowInfo);
			$('store'+stores.items[i].entity_id).observe('click', openWindowInfo.bind(markers[i]));

			haveMarker = true;
			nbMarker++;
		}
	}

	map.fitBounds(bounds);
  map.panToBounds(bounds);

	if(nbMarker == 1) {
		zoomChangeBoundsListener = google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
			if (this.getZoom()){
					this.setZoom(10);
			}
		});

		setTimeout(function(){google.maps.event.removeListener(zoomChangeBoundsListener)}, 2000);
	}

	return haveMarker;
}

function initMapWithAllPoints(){

	markers = new Array();

	for(i=0; i< stores.items.length; i++) {

		var latLng =  new google.maps.LatLng(stores.items[i].lat, stores.items[i].long);

		bounds.extend(latLng);

		if(stores.items[i].marker){
			var imgMarker = new google.maps.MarkerImage(pathMarker+stores.items[i].marker);
		}else{
			if(defaultMarker){
				var imgMarker = new google.maps.MarkerImage(pathMarker+defaultMarker);
			}else{
				var imgMarker = '';
			}
		}

		markers[i] = new google.maps.Marker({position: latLng, icon: imgMarker,map: map, store: stores.items[i]});
		google.maps.event.addListener(markers[i], 'click', openWindowInfo);
		$('store'+stores.items[i].entity_id).observe('click', openWindowInfo.bind(markers[i]));
	}

	map.fitBounds(bounds);
	map.panToBounds(bounds);
}

function openWindowInfo(){

    if(!this.store.image){
        this.store.image = defaultImage;
    }

	var content = 	'<div class="store-info"></div><div class="store-name-infoWindow"><h3>' + this.store.name + '</h3>'
     + this.store.address + '<br>'
     + this.store.zipcode+' '+ this.store.city +' <br>'+ this.store.country_id + '<br>';


    if(this.store.phone){
        content += 'Téléphone : '+ this.store.phone + '<br>'
    }

    if(this.store.fax){
        content += 'Fax : '+  this.store.fax + '<br>'
    }
    content += "</div>";
    if(this.store.description){
        content += '<div class="store-description">'+ this.store.description+'</div>';
    }

    if(markerVisitor && directionEnable){
        content += '<span onclick="getItineraire('+this.store.lat+','+ this.store.long+')" class="span-geoloc">'+estimateDirectionLabel+'</span></div></div>';
    }

    infoWindow.setContent(content);
    infoWindow.open(map,this);
}

function markerPosition(latlng){
    bounds.extend(latlng);
    if(markerVisitor){
        markerVisitor.setPosition(latlng);
    }else{
        markerVisitor = new google.maps.Marker({
            position: latlng,
            map: map,
            title:"Vous êtes ici"
        });
    }
    map.panTo(latlng);
    map.setZoom(20);
}
