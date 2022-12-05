import * as L from 'leaflet';

const marKerIcon = L.divIcon({
    html: `
<svg width="40pt" height="40pt" version="1.1" viewBox="0 0 752 752" xmlns="http://www.w3.org/2000/svg">
 <path d="m481.24 283.92c0-58.047-47.195-105.24-105.24-105.24s-105.24 47.195-105.24 105.24c0 53.523 40.289 97.348 92.086 103.93v185.48h26.309v-185.48c51.797-6.5781 92.086-50.398 92.086-103.93z"/>
</svg>
    `,
    className: 'map-marker',
    iconSize: [40, 40],
    iconAnchor: [20, 0],
    tooltipAnchor: [15, 20],
});

const coordinates = new Array();
const markers = new Array();

const streets = new L.TileLayer(
    'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }
);

const locations = $('.contains-map-data');

locations.each(function () {
    // Extract data-location infos
    const location = $(this).data('location');
    const priceInfo = $(this).data('price');
    const locationExists = coordinates.find(
        (c) => c[0] === location.latitude && c[1] === location.longitude
    );

    if (!locationExists) {
        const newCoordinates = [location.latitude, location.longitude];
        coordinates.push(newCoordinates);
        markers.push(
            new L.Marker(newCoordinates, { icon: marKerIcon }).bindTooltip(
                priceInfo.rentMin +
                    (priceInfo.rentMax ? ' -' + priceInfo.rentMax : '') +
                    '€',
                { permanent: true, className: 'map-tooltip' }
            )
        );
    }
});

const map = L.map('map');
map.addLayer(streets);
L.control.scale().addTo(map);

if (locations.length > 0) {
    const bounds = L.latLngBounds(coordinates);
    const housings = L.layerGroup(markers);

    map.addLayer(housings);
    map.fitBounds(bounds);

    $(window).on('resize', function () {
        map.fitBounds(bounds);
    });
} else {
    // Orsay university coordinates
    map.setView([48.69858, 2.18071], 13);
}
