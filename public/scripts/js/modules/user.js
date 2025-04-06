"use strict";

import * as ALERT from "./alert.js";

export async function initSite() {
    document.getElementById("logout").addEventListener("click", async (e) => {
        fetch("/public/scripts/php/logout.php", { method: "GET" })
            .then(() => {
                ALERT.show("success", "Logout successful");

                document.getElementById("logout").disabled = true;
                document.getElementById("main").classList.remove("fade-in-default");
                document.getElementById("main").classList.add("fade-out-default");

                setTimeout(() => {
                    window.location.href = "/";
                }, 1500);
            })
    });

    const container = document.querySelector(".scroll-box");

    window.addEventListener("resize", () => {
        setInitialHeight();
    });
    
    function setInitialHeight() {
        const parentElement = container.parentElement;
        console.log(parentElement.offsetHeight);
        document.querySelector(".scroll-box").style.height = parentElement.offsetHeight + "px";
    };
    
    setInitialHeight();
}