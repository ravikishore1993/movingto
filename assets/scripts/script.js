var placeSearch, autocomplete;
var foundLatitude, foundLongitude, foundCity;

var items = {
    'room' :'32' , 'house' :'32' , 'apartment' :'32', 'home' :'32' , 'flat' :'32', 'penthouse' :'32' , 'accomodation' : '32',
    'shop' : '215', 'office' :'215' , 'showroom' : '215', 'godown': '215', 'warehouse' :'215', 'commercialspace' : '215',
    'car' : '228', 'scooter' : '228', 'tempo' : '228', 'vehicle' : '228', 'bike' : '228',
    'villa' :'270' , 'bunglow' : '270', 'mansion' : '270' 
}

var houses = [
    'room' , 'house' , 'apartment' , 'home' , 'flat', 'penthouse', 'accomodation'
];

var villas = [
 'villa', 'bunglow', 'mansion'   
];

var suggest = {};

function initAutocomplete() {

  autocomplete = new google.maps.places.Autocomplete(
      (document.getElementById('location')),
      {types: ['(cities)']});  
}

function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      var circle = new google.maps.Circle({
        center: geolocation,
        radius: position.coords.accuracy
      });
      autocomplete.setBounds(circle.getBounds());
      google.maps.event.addListener(autocomplete, 'place_changed', function() {
        place = autocomplete.getPlace();
        foundLatitude = place.geometry.location.lat();
        foundLongitude = place.geometry.location.lng();

        });
    });
  }
}

function geoQuery (position) 
{
    foundLatitude = position.coords.latitude;
    foundLongitude = position.coords.longitude;
    
    $.ajax({
        type: 'GET',
        dataType: "json",
        url: "http://maps.googleapis.com/maps/api/geocode/json?latlng="+foundLatitude+","+foundLongitude+"&sensor=false",
        data: {},
        success: function(data) {
            $.each( data['results'],function(i, val) {
                $.each( val['address_components'],function(i, val) {
                    if (val['types'] == "locality,political") {
                        foundCity = val['long_name'];
                        return false;
                    }
                });
            });
            $('#location').val(foundCity);
        },
        error: function () { console.log('error'); } 
    }); 
}

function errorQuery (argument) {
    // body...
}

function submitHandler () {
    $('.optionals').hide();
        $('#ajax-loader').show();
    inputCategory = $('#category').val();
    inputCategory = inputCategory.replace(/\s+/g, '');
    inputCategory = inputCategory.toLowerCase();
    if(inputCategory in items)
    {
        categoryID = items[inputCategory];
        if(inputCategory in houses)
            suggest = villas;
        else if(inputCategory in villas)
            suggest = houses;
    }
    else
    {
        // Error message 
        $('#ajax-loader').hide();
        $('.optionals').show();
        $('#category').val('');
        
        return false;
    }
    var foundCity = $('#location').val();
    if(foundCity.indexOf(',') > -1)
    {
        foundCity = foundCity.substring(0,foundCity.indexOf(','));
        if(foundCity.indexOf(" ") > -1)
        {
            foundCity = foundCity.split(' ');
            foundCity = foundCity[1];
        }
    }
    $.ajax({
        method: "GET",
        contentType: "application/json",
        url: '/category/'+categoryID+'/'+foundCity+'/'+foundLatitude+'/'+foundLongitude,
        success: function(ResponseData) 
        {
            $('#ajax-loader').hide();
            ResponseData = JSON.parse(ResponseData);
            q = ResponseData;
            if( parseInt(ResponseData['count']) == 0)
            {
                $('#noresults').show();
                return false;
            }
            var start = {lat: foundLatitude, lng: foundLongitude};
            var end = {lat: parseFloat(ResponseData['data'][0]['lat']), lng: parseFloat(ResponseData['data'][0]['lon'])};
            map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: foundLatitude, lng: foundLongitude},
              zoom: 15,
              styles:  [{featureType: "all",stylers: [{ saturation: -100 }]}]
            });
            var directionsDisplay = new google.maps.DirectionsRenderer({
                map: map
            });
            var request = {
                destination: end,
                origin: start,
                travelMode: google.maps.TravelMode.DRIVING
              };
            var directionsService = new google.maps.DirectionsService();
              directionsService.route(request, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                  // Display the route on the map.
                  directionsDisplay.setDirections(response);
                }
              });
              $('#variablecategory').text($('#category').val());
              $('#sellerlink').attr('href',ResponseData['data'][0]['url']);
              $('#seller').show();

/*              var latlng = end;
              geocoder.geocode({'location': latlng}, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                  if (results[1]) {
                    map.setZoom(15);
                    var marker = new google.maps.Marker({
                      position: latlng,
                      map: map
                    });
                    infowindow.setContent(results[1].formatted_address);
                    infowindow.open(map, marker);
                  } else {
                    window.alert('No results found');
                  }
                } 
            });*/

        },
        error : function () {
            $('#ajax-loader').hide();
            $('noresults').show();
        }

    });

}

$(document).ready(function(argument) {
    $('#ajax-loader').hide();

    if ("geolocation" in navigator) 
    {
       navigator.geolocation.getCurrentPosition(geoQuery,errorQuery); 
    } 
    else 
    {

    }

    $('#submitbutton').click(submitHandler);

    $("#category").keyup(function (e) {
    if (e.keyCode == 13) {
        submitHandler();
    }
});


});