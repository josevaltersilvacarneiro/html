'use strict'

/**
 * @module Public This module is responsible for initializing
 * the frontend.
 *
 * Copyright (C) 2023, José V S Carneiro
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,    
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License 
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * 
 * @module github.com/josevaltersilvacarneiro/html/Public/TypeScript/Public
 */

import { Dispatch } from "../App/Dispatch.js";

/**
 * @function route identifies the accessed page and returns its name in lower case
 * 
 * @returns {string} name of the method that the dispatcher should call
 * 
 * @author      José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version     0.1
 * @see         {@link https://developer.mozilla.org/en-US/docs/Web/API/Location/pathname}
 * @see         {@link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/split}
 * @copyright   Copyright (C) 2023, José V S Carneiro
 * @license     GPLv3
 */

function route(): string
{
    const PATH: string = window.location.pathname;

    let services: Array<string> = PATH.split("/");
    let service: string|undefined = services[1];

    return typeof service === 'string' && service.length > 0 ?
        service.toLowerCase() : "home";
}

// when the page loads, initialize the dispatcher
window.onload = () => {

    const service = route();

    const dispatch: Dispatch = new Dispatch(service);
}
