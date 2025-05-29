"use strict";

import * as ALERT from "./alert.js";
import * as functions from "./functions.js";

export async function initSite() {
    ALERT.show("info", "Welcome to the moderator page!");
    functions.log();
}
