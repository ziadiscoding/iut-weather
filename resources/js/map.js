document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('weathermap').setView([0, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // OpenWeatherMap tile layer
    L.tileLayer('https://tile.openweathermap.org/map/temp_new/{z}/{x}/{y}.png?appid=60b8200316da929dc4ce2b0f0c62f7c2', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="https://openweathermap.org">OpenWeatherMap</a>',
        id: 'weathermap',
        tileSize: 512,
        zoomOffset: -1
    }).addTo(map);
});