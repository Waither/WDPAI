"use strict";

import * as ALERT from "./alert.js";

export async function initSite() {
    const form = document.querySelector("#loginForm form");
    form.addEventListener("submit", login);

    function login(e) {
        e.preventDefault();

        let end = false;
        
        document.querySelectorAll("input").forEach((element) => {
            if (element.value === "") {
                ALERT.show("warning", "Please fill in all fields");
                end = true;
                return;
            }
        });

        if (end) return;

        const formData = new FormData(form);

        fetch("/public/scripts/php/register.php", { method: "POST", body: formData })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                ALERT.show("success", "Register successful! You will be redirected to the home page in <span id='alertTime'>5</span> seconds.");

                document.getElementById("main").classList.toggle("fade-out-default", "fade-in-default");
                setTimeout(() => {
                    document.getElementById("main").remove();
                }, 500);
                
                setTimeout(() => {
                    window.location.href = "/";
                }, 5000);

                const span = document.getElementById("alertTime");
                let seconds = 5;
                setInterval(() => {
                    seconds--;
                    span.textContent = seconds;
                }, 1000);
            }
            else {
                ALERT.show("error", "Register failed: " + (data.message || "Unknown error"));
            }
        })
        .catch((error) => {
            ALERT.show("error", "Register failed: " + error);
        });
    }
}