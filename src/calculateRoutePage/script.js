let map;
let directionsService;
let directionsRenderer;
let originLatLng = null;
let destinationLatLng = null;

const getUserLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            let userLat = position.coords.latitude;
            let userLng = position.coords.longitude;
            let userLocation = new google.maps.LatLng(userLat, userLng);

            map.setCenter(userLocation);

            let userMarker = new google.maps.Marker({
                position: userLocation,
                map: map,
                title: 'Sua Localização'
            });

            document.getElementById('origin').textContent = 'Origem: Sua Localização';
            originLatLng = userLocation;

            if (destinationLatLng) {
                calculateRoute();
            }
        }, function(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("Permissão para geolocalização negada pelo usuário.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Informações de localização indisponíveis.");
                    break;
                case error.TIMEOUT:
                    alert("Tempo limite para obter a localização esgotado.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("Erro desconhecido ao obter a localização.");
                    break;
            }
        });
    } else {
        alert("Seu navegador não suporta geolocalização.");
    }
}

const calculateRoute = () => {
    if (originLatLng && destinationLatLng) {
        let request = {
            origin: originLatLng,
            destination: destinationLatLng,
            travelMode: 'DRIVING'
        };

        directionsService.route(request, function(result, status) {
            if (status == 'OK') {
                let route = result.routes[0];
                let distance = route.legs[0].distance.text;
                let duration = route.legs[0].duration.text;

                document.getElementById('distance').textContent = 'Distância por estrada: ' + distance;
                document.getElementById('duration').textContent = 'Tempo estimado: ' + duration;

                directionsRenderer.setDirections(result);
            } else {
                alert('Não foi possível calcular a rota: ' + status);
            }
        });
    }
}

function initMap() {
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();

    let coordenadas = { lat: -5.302041925508938, lng: -38.93314844402641 };

    map = new google.maps.Map(document.getElementById('map'), {
        center: coordenadas,
        zoom: 15, 
        mapTypeId: 'roadmap' 
    });

    directionsRenderer.setMap(map);

    map.addListener('click', function(event) {
        if (!originLatLng) {
            originLatLng = event.latLng;
            document.getElementById('origin').textContent = 'Origem: ' + event.latLng.toString();
        } else if (!destinationLatLng) {
            destinationLatLng = event.latLng;
            document.getElementById('destination').textContent = 'Destino: ' + event.latLng.toString();
            calculateRoute();
        }
    });
}
