"use strict";

export async function initSite() {
    const form = document.querySelector("#loginForm form");
    form.addEventListener("submit", login);

    function login(e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch("/public/scripts/php/register.php", { method: "POST", body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cookie.set("user", data.user);
                window.location.href = "/";
            }
            else {
                alert("Login failed");
            }
        })
    }
}