'use strict'

/**
 * @module HTTP This module is responsible for providing
 * asynchronous functions that communicate with the API.
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
 * @module github.com/josevaltersilvacarneiro/html/Public/JavaScript/Src/HTTP
 */

export { get, post, redirect };

/**
 * @func    request     makes a request asyncchronously to the url passed as argument
 * 
 * @param   {string}    method  [ GET, POST, PUT, DELETE [
 * @param   {string}    url     a valid url that identifies the API
 * @param   {object}    params  key-value pair @default {{}}
 * 
 * @returns {Promise}   a json object if was succesful; otherwise, false
 *
 * @author      José V S Carneiro
 * @version     0.1
 * @access      private
 * @see         {@link https://www.w3schools.com/js/js_ajax_http.asp} to learn more
 * @copyright   Copyright (C) 2023, José V S Carneiro
 * @license     GPLv3
 */

function request(method, url, params = {}) {

    return new Promise((resolve, reject) => {
        let ajax = new XMLHttpRequest();

        ajax.open(method.toUpperCase(), url);

        ajax.onerror = event => {
            reject(false);
        };

        ajax.onload = event => {
            let response = {};

            try {
                response = JSON.parse(ajax.responseText);
            } catch (e) {
                reject(false);
            }

            resolve(response);
        };

        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send(params);
    });
}

function get(url) {
    return request('GET', url);
}

function post(url, params) {
    return request('POST', url, params)
}

/**
 * @func    redirect    redirects the user by sending parameters using the http post method
 * 
 * @param   {string}    url     a valid url that targets the service
 * @param   {Map}       params  key-value pair which matches name and value in the input field
 * 
 * @returns {void}
 *
 * @author      José V S Carneiro
 * @version     0.1
 * @access      public
 * @copyright   Copyright (C) 2023, José V S Carneiro
 * @license     GPLv3
 */

function redirect(url, params = new Map()) {
    
    const form = document.createElement('form');

    form.method = 'POST';
    form.action = url;

    for (const [key, value] of params) {

        const input = document.createElement('input');

        input.type  = hidden;
        input.name  = key;
        input.value = value;

        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}