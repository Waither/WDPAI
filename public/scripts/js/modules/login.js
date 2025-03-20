"use strict";

export async function initSite() {
    const form = document.querySelector("#loginForm form");
    form.addEventListener("submit", login);

    function login(e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch("/public/scripts/php/login.php", { method: "POST", body: formData })
        .then(response => response.json())
        .then(data => {
            data.success ? window.location.href = "/" : alert("Login failed: " + (data.message || "Unknown error"));
        })
    }
}