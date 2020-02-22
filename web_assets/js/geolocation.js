function initMap() {
    var uluru = {lat: 28.6102643, lng: 77.3574337};
    var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 12,
    center: uluru
    });

    var contentString = '<strong>Grand Slam Fitness</strong>';

    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });

    var marker = new google.maps.Marker({
    position: uluru,
    map: map,
    title: 'Grand Slam Fitness',
    icon: "./images/loc.png"
    });
    marker.addListener('click', function() {
        infowindow.open(map, marker);
    });
}