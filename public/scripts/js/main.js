"use strict"

import * as HOME from './modules/home.js';
import * as PLACE from './modules/places.js';
import * as MAP from './modules/map.js';
import * as LOGIN from './modules/login.js';
import * as REGISTER from './modules/register.js';
import * as FAVOURITE from './modules/favourite.js';
import * as ADMIN from './modules/admin.js';
import * as USER from './modules/user.js';
import * as MOD from './modules/moderator.js';


document.addEventListener("DOMContentLoaded", (e) => {
    const routes = [
        { path: "/places", module: PLACE },
        { path: "/map", module: MAP },
        { path: "/favourite", module: FAVOURITE },
        { path: "/login", module: LOGIN },
        { path: "/register", module: REGISTER },
        { path: "/admin", module: ADMIN },
        { path: "/user", module: USER },
        { path: "/moderator", module: MOD },
        { path: "/", module: HOME }
    ];

    const currentRoute = routes.find(route => window.location.href.includes(route.path));

    if (currentRoute) {
        currentRoute.module.initSite();
    }
    else {
        console.error("Unknown page");
    }
});

// Fade out effect for links
document.querySelectorAll('a').forEach(link => {
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