export async function initMap(position = undefined, zoom = 5) {
    const { Map } = await google.maps.importLibrary("maps");

    position = position ?? { lat: 52.210, lng: 20.982 };

    const map = new Map(document.getElementById("map"), {
        zoom: zoom,
        center: position,
        mapId: "DEMO_MAP_ID",
    });

    return map;
}

export async function addMarker(map, position, title) {
    const { AdvancedMarkerElement  } = await google.maps.importLibrary("marker");

    const marker = new AdvancedMarkerElement({ position, map, title });

    return marker;
}

export async function clearMarkers(markers) {
    markers.forEach(({ marker }) => {
        if (marker) {
            marker.setMap(null);
        }
    });
    return markers = [];
}