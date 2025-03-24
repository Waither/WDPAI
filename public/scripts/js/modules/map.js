"use strict";

import * as MAP from './api.js';
import * as ALERT from './alert.js';

let map;
let markers = [];

export async function initSite() {
    map = await MAP.initMap();

    setTimeout(async () => {
        const formData = new FormData();
        const position = JSON.stringify({ lat: 52.210, lng: 20.982 });

        fetch(`/public/scripts/php/getClosePins.php?position=${position}`, { method: "GET" })
            .then((response) => response.json())
            .then((data) => {
                if (!data.success) {
                    ALERT.show("error", data.message);
                    return;
                }

                const markerPromises = data.data.map(async (element) => {
                    const marker = await MAP.addMarker(map, { lat: parseFloat(element.location.lat), lng: parseFloat(element.location.lng) }, element.name);
                    return { id: element.ID_place, marker };
                });

                markers = Promise.all(markerPromises);
            });
    }, 1000);
}

