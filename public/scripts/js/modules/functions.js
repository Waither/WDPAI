function createBackdrop() {
    const backdrop = document.createElement("div");
    backdrop.classList.add("backdrop");
    document.body.appendChild(backdrop);

    return backdrop;
}

export function showModal(name = "modalForm") {
    const modal = document.querySelector(`#${name}`);
    const backdrop = createBackdrop();

    modal.style.display = "block";

    return { modal, backdrop };
}

export function hideModal(name = "modalForm") {
    const modal = document.querySelector(`#${name}`);
    const backdrop = document.querySelector(".backdrop");
    modal.remove();
    backdrop.remove();
}