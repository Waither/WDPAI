"use strict"

document.addEventListener("DOMContentLoaded", async (e) => {
    const modulePaths = ["places", "map", "favourite", "login", "register", "admin", "user", "moderator", "home"];
    const routes = modulePaths.map(path => ({
        path: path === "home" ? "/" : `/${path}`,
        module: () => import(`./modules/${path}.js`)
    }));

    const currentRoute = routes.find(route => window.location.href.includes(route.path));

    if (currentRoute) {
        try {
            const module = await currentRoute.module();
            module.initSite();
        }
        catch (error) {
            console.error("Failed to load module:", error);
        }
    }
    else {
        console.error("Unknown page");
    }
});

// Fade out effect for links
document.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const targetHref = link.href;

        const main = document.querySelector('#main');
        main.classList.remove('fade-in-default');
        main.classList.add('fade-out-default');

        const mainStyles = getComputedStyle(main);
        const animationDuration = parseFloat(mainStyles.animationDuration) * 1000;
        const fadeOutDuration = animationDuration - 100;

        if (targetHref.includes("/login") && !window.location.href.includes("/register")) {
            const nav = document.querySelector('nav');
            nav.classList.add('animation', 'animation-500ms', nav.id === 'menuBottom' ? 'fade-out-down' : 'fade-out-left');

            if (nav.id === 'menuBottom') {
                document.getElementById("mobileTop").classList.add('animation', 'animation-500ms', 'fade-out-up');
            }
        }

        setTimeout(() => {
            window.location.href = targetHref;
        }, fadeOutDuration);

        // Restore initial classes after navigation
        setTimeout(() => {
            main.classList.remove('fade-out-default');
            main.classList.add('fade-in-default');

            const nav = document.querySelector('nav');
            if (nav) {
                nav.classList.remove('fade-out-down', 'fade-out-left', 'animation', 'animation-500ms');
            }

            const mobileTop = document.getElementById("mobileTop");
            if (mobileTop) {
                mobileTop.classList.remove('fade-out-up', 'animation', 'animation-500ms');
            }
        }, fadeOutDuration + 100);
    });
});
