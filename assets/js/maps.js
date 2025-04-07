function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 8,
        center: { lat: -34.397, lng: 150.644 },
    });
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);

    document.getElementById("pickup").addEventListener("change", updateRoute);
    document.getElementById("dropoff").addEventListener("change", updateRoute);

    function updateRoute() {
        const pickup = document.getElementById("pickup").value;
        const dropoff = document.getElementById("dropoff").value;
        if (pickup && dropoff) {
            directionsService.route({
                origin: pickup,
                destination: dropoff,
                travelMode: "DRIVING",
            }, (response, status) => {
                if (status === "OK") {
                    directionsRenderer.setDirections(response);
                    const distance = response.routes[0].legs[0].distance.value / 1000; // in km
                    document.getElementById("distance").value = distance;
                }
            });
        }
    }
}