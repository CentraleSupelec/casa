import housingIcon from '/assets/map/house_icon.png';
import * as L from 'leaflet';

const coordinates = new Array();
const markers = new Array();

const streets = new L.TileLayer(
    'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }
);

const myIcon = L.icon({
    iconUrl: housingIcon,
    iconSize: [32, 32], // size of the icon
    iconAnchor: [16, 32], // point of the icon which will correspond to marker's location
    popupAnchor: [0, -34], // point from which the popup should open relative to the iconAnchor
});

const locations = $('.contains-map-data');

locations.each(function () {
    // Extract data-location infos
    const location = $(this).data('location');
    const locationExists = coordinates.find(
        (c) => c[0] === location.latitude && c[1] === location.longitude
    );

    if (!locationExists) {
        const newCoordinates = [location.latitude, location.longitude];
        coordinates.push(newCoordinates);
        markers.push(new L.Marker(newCoordinates, { icon: myIcon }));
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
} else {
    // Orsay university coordinates
    map.setView([48.69858, 2.18071], 13);
}
