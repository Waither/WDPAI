"use strict";

import * as ALERT from "./alert.js";

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
}