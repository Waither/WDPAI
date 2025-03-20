"use strict";

import * as MAP from './api.js';

let map;
let markers = [];

export async function initSite() {
    map = await MAP.initMap();

    setTimeout(async () => {
        const formData = new FormData();
        formData.append(":position", JSON.stringify({ lat: 52.210, lng: 20.982 }));

        const response = await fetch("/public/scripts/php/getClosePins.php", { method: "POST", body: formData });
        const data = await response.json();

        const markerPromises = JSON.parse(data).map(async (element) => {
            const marker = await MAP.addMarker(map, { lat: parseFloat(element.latitude_place), lng: parseFloat(element.longitude_place) }, element.name);
            return { id: element.ID_place, marker };
        });

        markers = await Promise.all(markerPromises);

        setTimeout(async () => {
            markers = await MAP.clearMarkers(markers);
        }, 5000);
    }, 1000);
}

