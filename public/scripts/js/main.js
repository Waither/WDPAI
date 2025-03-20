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