"use strict";

export async function initMap() {
    const { Map } = await google.maps.importLibrary("maps");

    let map = new Map(document.getElementById("map"), {
        zoom: 6,
        center: { lat: 52.2297, lng: 21.0122 },
        mapId: "DEMO_MAP_ID",
    });

    return map;
}

export async function addMarker(map, position, title) {
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

    const marker = new AdvancedMarkerElement({ map, position, title});
}
