"use strict";

import * as ALERT from "./alert.js";
import * as functions from "./functions.js";

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

        const usersDiv = document.querySelector("#usersDiv");
        const div = usersDiv.parentElement.querySelector("div");
        document.querySelector(".scroll-box").style.height = (container.offsetHeight - div.offsetHeight) + "px";
    };
    
    setInitialHeight();

    document.querySelectorAll(".card").forEach((element) => {
        element.addEventListener("click", async (e) => {
            const userID = element.getAttribute("data-id");
            
            functions.showModal();

            fetch(`/public/scripts/php/getPlace.php?userID=${userID}`, { method: "GET" })
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

    document.getElementById("seachBar").addEventListener("input", (e) => {
        const value = e.target.value.toLowerCase();
    
        document.querySelectorAll(".card").forEach((element) => {
            let matches = false;
    
            Array.from(element.attributes).forEach(attr => {
                if (attr.name.startsWith("data-")) {
                    const attrValue = attr.value.toLowerCase();
                    if (attrValue.includes(value)) {
                        matches = true;
                    }
                }
            });
    
            element.style.display = (matches || value === "") ? "" : "none";
        });
    });
    
}