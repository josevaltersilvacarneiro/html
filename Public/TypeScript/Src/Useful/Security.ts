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
 * @module github.com/josevaltersilvacarneiro/html/Public/TypeScript/Src/Useful
 */

export { hash, createSalt };

/**
 * @function hash calculates the hash of a word using the SHA-256 algorithm
 * 
 * @param   {string} word - any word
 * @returns {Promise<string|false>} - 64 characters representing the SHA-256 hash
 * 
 * @author      José V S Carneiro <git@josevaltersilvacarneiro.net>
 * @version     0.2
 * @access      public
 * @see         {@link https://en.wikipedia.org/wiki/SHA-2} to understand the algorithm
 * @see         {@link https://developer.mozilla.org/en-US/docs/Web/API/SubtleCrypto/digest} to learn more
 * @copyright   Copyright (C) 2023, José V S Carneiro
 * @license     GPLv3
 */

async function hash(word: string): Promise<string> {

    const encoder       = new TextEncoder();
    const data          = encoder.encode(word);

    const hashBuffer    = await crypto.subtle.digest('SHA-256', data);

    const hashArray     = Array.from(new Uint8Array(hashBuffer));

    const hashHex       = hashArray.map(byte => byte.toString(16)
        .padStart(2, '0'))
        .join('');
    
    return hashHex;
}

/** @deprecated a future version will solve the lack of security */

function createSalt(): string {
    return Math.random().toString(36).substring(2, 36);
}