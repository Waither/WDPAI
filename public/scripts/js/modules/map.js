"use strict";

import * as MAP from './api.js';

let map;

export async function initSite() {
    map = await MAP.initMap();

    setTimeout(() => {
        MAP.addMarker(map, { lat: 52.210, lng: 20.982 }, "Warsaw");
        MAP.addMarker(map, { lat: 51.107, lng: 17.038 }, "Wroclaw");
        MAP.addMarker(map, { lat: 50.061, lng: 19.937 }, "Krakow");

        const formData = new FormData();
        formData.append(":position", { lat: 52.210, lng: 20.982 });

        fetch("/public/scripts/php/getClosePins.php", { method: "POST", body: formData })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                // data.forEach(({ lat, lng, name }) => {
                //     MAP.addMarker(map, { lat, lng }, name);
                // });
            });
    }, 1000);

    fetch
}