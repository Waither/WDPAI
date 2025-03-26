"use strict";

import * as ALERT from "./alert.js";
import * as functions from "./functions.js";
import * as API from "./api.js";

export async function initSite() {
    const container = document.querySelector(".container");

    window.addEventListener("resize", () => {
        setInitialHeight();
    });
    
    function setInitialHeight() {
        const parentElement = container.parentElement;
        const parentPaddingTop = parseFloat(window.getComputedStyle(parentElement).paddingTop);
        const parentPaddingBottom = parseFloat(window.getComputedStyle(parentElement).paddingBottom);
        const h1MarginBottom = parseFloat(window.getComputedStyle(parentElement.querySelector("h1")).marginBottom);
        const h1Height = parentElement.querySelector("h1").offsetHeight;
        container.style.height = (parentElement.offsetHeight - parentPaddingTop - parentPaddingBottom - h1MarginBottom - h1Height) + "px";

        const placesDiv = document.querySelector("#placesDiv");
        const div = placesDiv.parentElement.querySelector("div");
        document.querySelector(".scroll-box").style.height = (container.offsetHeight - div.offsetHeight) + "px";
    };
    
    setInitialHeight();

    document.querySelectorAll(".card").forEach((element) => {
        element.addEventListener("click", async (e) => {
            const placeID = element.getAttribute("data-id");
            
            functions.showModal();

            fetch(`/public/scripts/php/getPlace.php?placeID=${placeID}`, { method: "GET" })
                .then((response) => response.json())
                .then((data) => {
                    if (!data.success) {
                        ALERT.show("error", data.message);
                        return;
                    }
                    
                    const modal = document.querySelector("#modalForm");

                    const place = data.data;

                    modal.querySelector("#modalID").innerHTML = place.ID_place;
                    modal.querySelector("#modalName").innerHTML = place.name;
                    modal.querySelector("#modalCompany").innerHTML = place.company;
                    modal.querySelector("#modalAddress").innerHTML = place.address;
                    modal.querySelector("#modalRating").innerHTML = place.rating;

                    modal.querySelector("#modalImage").src = place.image;
                    modal.querySelector("#modalImage").alt = place.name;

                    console.log(place);


                    const placeTags = modal.querySelector("#modalTags");
                    placeTags.innerHTML = "";
                    place.placeTags.forEach((tag) => {
                        const tagElement = document.createElement("span");
                        tagElement.className = "badge bg-primary me-1";
                        tagElement.innerHTML = tag;
                        placeTags.appendChild(tagElement);
                    });

                    const placeTypes = modal.querySelector("#modalTypes");
                    placeTypes.innerHTML = "";
                    place.type.forEach((type) => {
                        const typeElement = document.createElement("span");
                        typeElement.className = "badge bg-secondary me-1";
                        typeElement.innerHTML = type;
                        placeTypes.appendChild(typeElement);
                    });

                    const position = { lat: parseFloat(place.latitude), lng: parseFloat(place.longitude) };
                    API.initMap(position, 10)
                        .then((map) => {
                            API.addMarker(map, position, place.name);
                        });
                })
                .catch((error) => {
                    ALERT.show("error", error.message);
                });
        });
    });

    document.querySelectorAll("[data-mdb-dismiss=modal").forEach((element) => {
        element.addEventListener("click", () => {
            functions.hideModal();
        });
    });
}