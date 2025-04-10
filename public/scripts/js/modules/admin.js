"use strict";

import * as ALERT from "./alert.js";
import * as functions from "./functions.js";

export async function initSite() {
    let ID_user = undefined;
    let ID_special = undefined;

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

            fetch(`/public/scripts/php/getUser.php?userID=${userID}`, { method: "GET" })
                .then((response) => response.json())
                .then((data) => {
                    if (!data.success) {
                        ALERT.show("error", data.message);
                        return;
                    }
                    
                    const user = data.data

                    ID_user = user.ID_user;
                    ID_special = user.ID_special;
                    
                    const modal = document.querySelector("#modalForm");
                    
                    modal.querySelector("#modalID").textContent = user.ID_user;
                    modal.querySelector("#modalName").textContent = user.name;
                    modal.querySelector("#modalEmail").textContent = user.email;
                    modal.querySelector("#modalCompany").textContent = user.company;

                    document.querySelectorAll(".rolesCheckbox").forEach((checkbox) => {
                        checkbox.checked = user.roles.includes(checkbox.getAttribute("data-name"));
                    });

                    modal.querySelector(".modal-footer").classList.toggle("d-none", user.ID_special === userIdSpecial);
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
    
            element.querySelectorAll("h2, p").forEach((child) => {
                if (child.innerHTML.toLowerCase().split(":")[1].includes(value)) {
                    matches = true;
                }
            });
    
            element.style.display = (matches || value === "") ? "" : "none";
        });
    });
    
    document.querySelectorAll(".rolesCheckbox").forEach((element) => {
        element.addEventListener("change", async () => {
            const selectedIDs = Array.from(document.querySelectorAll(".rolesCheckbox:checked")).map(checkbox => checkbox.getAttribute("data-id"));

            const selectedIDsLabels = Array.from(document.querySelectorAll(".rolesCheckbox:checked")).map(checkbox => checkbox.getAttribute("data-name"));

            if (!ID_user) {
                ALERT.show("error", "No user selected");
                return;
            }

            const formData = new FormData();
            formData.append(":ids", JSON.stringify(selectedIDs));
            formData.append(":user", ID_user);

            fetch("/public/scripts/php/updateRoles.php", { method: "POST", body: formData })
                .then((response) => response.json())
                .then((data) => {
                    if (!data.success) {
                        ALERT.show("error", data.message);
                        return;
                    }
                    
                    ALERT.show("success", data.message);

                    const rolesText = selectedIDsLabels.length > 0 ? selectedIDsLabels.join(", ") : "normal";
                    document.querySelector(`.card[data-id="${ID_special}"]`).querySelector(".roles").textContent = rolesText;

                    if (ID_special == document.querySelector("#adminID").value) {
                        location.reload();
                    }
                })
                .catch((error) => {
                    ALERT.show("error", error.message);
                });
        });
    });

    document.getElementById("delButton").addEventListener("click", async () => {
        if (!ID_user) {
            ALERT.show("error", "No user selected");
            return;
        }

        const formData = new FormData();
        formData.append(":user", ID_user);

        fetch("/public/scripts/php/deleteUser.php", { method: "POST", body: formData })
            .then((response) => response.json())
            .then((data) => {
                if (!data.success) {
                    ALERT.show("error", data.message);
                    return;
                }
                
                ALERT.show("success", data.message);

                document.querySelector(`.card[data-id="${ID_user}"]`).remove();
            })
            .catch((error) => {
                ALERT.show("error", error.message);
            });
    });
}