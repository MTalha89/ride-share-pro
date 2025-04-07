// Initialize Google Maps (replace YOUR_API_KEY with your key)
function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 24.8607, lng: 67.0011 }, // Default: Karachi
        zoom: 12
    });
    // Add further map logic here if needed
}

// Real-time messaging simulation
$(document).ready(function() {
    $('.chat-box').scrollTop($('.chat-box')[0].scrollHeight);
});