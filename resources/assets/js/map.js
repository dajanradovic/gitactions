import { errorAlert } from './main';

const DEFAULT_LAT = 45.369583;
const DEFAULT_LNG = 16.034951;
const DEFAULT_ZOOM = 9;
const maps = {};

function init() {
	initMapLatLngChange();
	initMapRadiusChange();
	initMapCurrentLocationButton();
}

function initMaps(selector = '.map-area') {
	const mapContainers = document.querySelectorAll(selector);

	for (const mapContainer of mapContainers) {
		initMap(mapContainer);
	}
}

function initMapLatLngChange(selector = 'input[type=number][data-map-latlng-change]') {
	const inputs = document.querySelectorAll(selector);

	for (const input of inputs) {
		input.onchange = () => coordinateChange(input.dataset.mapLatlngChange);
	}
}

function initMapRadiusChange(selector = 'input[type=number][data-map-radius-change]') {
	const inputs = document.querySelectorAll(selector);

	for (const input of inputs) {
		input.onchange = () => addMapCircle(input.value, input.dataset.mapRadiusChange);
	}
}

function initMapCurrentLocationButton(selector = 'button[type=button][data-map-current-location-button]') {
	const buttons = document.querySelectorAll(selector);

	for (const button of buttons) {
		button.onclick = () => setPinToCurrentLocation(button.dataset.mapCurrentLocationButton);
	}
}

function initMap(mapContainer) {
	const baseName = mapContainer.dataset.basename;
	const inputLat = document.getElementById(baseName + '_lat').value;
	const inputLng = document.getElementById(baseName + '_lng').value;

	const position = {
		lat: inputLat === '' ? DEFAULT_LAT : parseFloat(inputLat),
		lng: inputLng === '' ? DEFAULT_LNG : parseFloat(inputLng)
	};

	maps[baseName] = new google.maps.Map(mapContainer, {
		center: position,
		zoom: DEFAULT_ZOOM
	});

	maps[baseName].marker = new google.maps.Marker({
		map: maps[baseName],
		position: inputLat === '' && inputLng === '' ? null : position,
		draggable: true
	});

	maps[baseName].circle = new google.maps.Circle({
		map: maps[baseName],
		strokeColor: '#FF0000',
		strokeOpacity: 0.8,
		strokeWeight: 1,
		fillColor: '#FF0000',
		fillOpacity: 0.2
	});

	maps[baseName].addListener('click', e => {
		pinOnMap(maps[baseName].marker, e.latLng.lat(), e.latLng.lng());
	});

	maps[baseName].marker.addListener('position_changed', () => {
		const latLng = maps[baseName].marker.getPosition();
		const map = maps[baseName].marker.getMap();

		if (map.circle) {
			map.circle.setCenter(latLng);
		}

		document.getElementById(baseName + '_lat').value = latLng.lat();
		document.getElementById(baseName + '_lng').value = latLng.lng();
	});
}

function pinOnMap(marker, latitude, longitude) {
	marker.setPosition(new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude)));
	marker.setAnimation(google.maps.Animation.DROP);
}

function coordinateChange(baseName) {
	const latitude = document.getElementById(baseName + '_lat').value;
	const longitude = document.getElementById(baseName + '_lng').value;

	return pinOnMap(maps[baseName].marker, latitude, longitude);
}

function addMapCircle(meters, baseName) {
	const circle = maps[baseName].circle;

	if (circle) {
		circle.setRadius(parseInt(meters, 10));
	}
}

function setPosition(baseName, latLng) {
	maps[baseName].setCenter(new google.maps.LatLng(latLng.latitude, latLng.longitude));

	document.getElementById(baseName + '_lat').value = latLng.latitude;
	document.getElementById(baseName + '_lng').value = latLng.longitude;

	return coordinateChange(baseName);
}

function setPinToCurrentLocation(baseName) {
	return navigator.geolocation.getCurrentPosition(
		position => setPosition(baseName, position.coords),
		error => errorAlert({ text: error.message }),
		{ enableHighAccuracy: true }
	);
}

export { init, initMaps };
