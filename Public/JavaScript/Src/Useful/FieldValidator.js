'use strict'

/**
 * @module Useful This module is responsible for providing
 * useful functions that are commonly reused.
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
 * @module github.com/josevaltersilvacarneiro/html/Public/JavaScript/Src/Useful
 */

export { isEmailValid, isPasswordValid };

/**
 * @func        isEmailValid    returns true if the email passed as parameter is valid; otherwise returns false
 * 
 * @param       {string}        email   @example  <git@josevaltersilvacarneiro.net>
 * 
 * @returns     {boolean}
 *
 * @author      José V S Carneiro
 * @version     0.1
 * @access      private
 * @see         {@link https://www.w3schools.com/jsref/jsref_regexp_test.asp} to learn more
 * @copyright   Copyright (C) 2023, José V S Carneiro
 * @license     GPLv3
 */

function isEmailValid(email) {

    const regx = /^.{1,30}@([a-z]{1,30}\.)+(com|net)(\.br)?/;

    return regx.test(email);
}

/**
 * @func    isPasswordValid returns true if the password passed as parameter is safe; otherwise returns false
 * 
 * @param   {string}    password @example  "TheLoveisBeaut1ful"
 * 
 * @returns {boolean}
 *
 * @author      José V S Carneiro
 * @version     0.1
 * @access      private
 * @see         {@link https://www.w3schools.com/jsref/jsref_regexp_test.asp} to learn more
 * @copyright   Copyright (C) 2023, José V S Carneiro
 * @license     GPLv3
 */

function isPasswordValid(password) {
	return password.length > 8 && /[A-Z]/.test(password);
}