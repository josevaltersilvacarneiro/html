/**
 * This script creates the project.
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
 */

USE database_html;

-- Table of users

CREATE TABLE IF NOT EXISTS `database_html`.`users` (
	user_id	 INT UNSIGNED	NOT NULL	AUTO_INCREMENT,

	fullname VARCHAR(80) 	CHECK ( fullname REGEXP '^.{3,} .*.{3,}$' ),	-- International Recommendation
	email	 VARCHAR(255) 	CHECK ( email REGEXP '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}$' )	UNIQUE,	-- RFC 5321
	hash	 VARCHAR(255) 	CHECK ( CHAR_LENGTH(hash) > 60 ),	-- PHP recomendation @see password_hash function in doc
	salt	 VARCHAR(64) 	CHECK ( CHAR_LENGTH(salt) > 7 ),

	active	 BOOLEAN	NOT NULL DEFAULT TRUE,

	CONSTRAINT pk_users
		PRIMARY KEY (user_id)
);

-- Table of requests

CREATE TABLE IF NOT EXISTS `database_html`.`requests` (
	request_id	BIGINT UNSIGNED	NOT NULL	AUTO_INCREMENT,	-- 10 * 18¹⁸ requests

	ip		VARCHAR(39) CHECK ( ip REGEXP '^(\\d{1,3}\\.){3}\\d{1,3}$|^([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}$' ),
	port		VARCHAR(5)  CHECK ( port REGEXP '^[0-9]{1,5}$' ),
	access_date	DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT pk_requests
		PRIMARY KEY (request_id)
);

-- Table of sessions

CREATE TABLE IF NOT EXISTS `database_html`.`sessions` (
	session_id	VARCHAR(64) CHECK ( session_id REGEXP '^[0-9a-fA-F]{64}' ),	-- SHA256
	sessionuser	INT UNSIGNED,			-- it's NULL when the USER isn't registered
	request		BIGINT UNSIGNED	NOT NULL,	-- last access

	CONSTRAINT pk_sessions
		PRIMARY KEY (session_id),
	CONSTRAINT fk1
		FOREIGN KEY (sessionuser)	REFERENCES `users`	(user_id),
	CONSTRAINT fk2
		FOREIGN KEY (request)		REFERENCES `requests`	(request_id)
);

