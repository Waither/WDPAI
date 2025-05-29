function createBackdrop() {
    const backdrop = document.createElement("div");
    backdrop.classList.add("backdrop");
    document.body.appendChild(backdrop);
    backdrop.addEventListener("click", () => {
        console.log("backdrop clicked");
    });

    return backdrop;
}

export function showModal(name = "modalForm") {
    const modal = document.querySelector(`#${name}`);
    const backdrop = createBackdrop();

    modal.style.display = "block";
    modal.classList.remove("fade-out-up");
    modal.classList.add("show", "fade-in-down");

    return { modal, backdrop };
}

export function hideModal(name = "modalForm") {
    const modal = document.querySelector(`#${name}`);
    const backdrop = document.querySelector(".backdrop");

    modal.classList.remove("fade-in-down");
    modal.classList.add("fade-out-up");

    setTimeout(() => {
        backdrop.remove();
        modal.classList.remove("show");
        modal.style.display = "none";
    }, 500);
}

export function log() {
    console.log("This is a log message from functions.js");
}