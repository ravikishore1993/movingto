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
    $('.optionals').hide(function() {
        $('#ajax-loader').show();
    });
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
        return false;
    }
    $.ajax({
        method: "GET",
        url: '/category/'+categoryID+'/'+foundCity+'/'+foundLatitude+'/'+foundLongitude,
        success: function(ResponseData) 
        {
            console.log(ResponseData);            
        },
        error : function () {
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

    $("#location, #category").keyup(function (e) {
    if (e.keyCode == 13) {
        submitHandler();
    }
});


});