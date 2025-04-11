export function show(type, message) {
    if (document.querySelector(".alert")) {
        document.querySelector(".alert").remove();
    }
    
    const { alert, fadeOutDuration } = createAlert(type, message);

    setTimeout(() => {
        alert.classList.remove("fade-in-default");
        alert.classList.add("fade-out-default");
        setTimeout(() => {
            alert.remove();
        }, fadeOutDuration);
    }, 5000);
}

function createAlert(type, message) {
    const types = {
        warning: {
            color: "warning",
            icon: "exclamation-triangle"
        },
        error: {
            color: "danger",
            icon: "exclamation-circle"
        },
        success: {
            color: "success",
            icon: "check-circle"
        },
        info: {
            color: "info",
            icon: "info-circle"
        }
    }

    const alert = document.createElement("div");
    alert.classList.add("alert", "mb-0", "alert-dismissible", "alert-absolute", `alert-${types[type].color}`, `animation`, `fade-in-default`, `animation-800ms`);

    const icon = document.createElement("i");
    icon.classList.add("fas", `fa-${types[type].icon}`, "me-2");
    alert.appendChild(icon);

    const span = document.createElement("span");
    span.innerHTML = message;
    alert.appendChild(span);

    const button = document.createElement("button");
    button.type = "button";
    button.classList.add("btn-close", "ms-2");
    alert.appendChild(button);

    document.body.appendChild(alert);

    const alertStyles = getComputedStyle(alert);
    const animationDuration = parseFloat(alertStyles.animationDuration) * 1000;
    const fadeOutDuration = animationDuration - 100;

    alert.querySelector("button").addEventListener("click", (e) => {
        alert.classList.remove("fade-in-default");
        alert.classList.add("fade-out-default");
        setTimeout(() => {
            alert.remove();
        }, fadeOutDuration);
    });

    return { alert, fadeOutDuration };
}
