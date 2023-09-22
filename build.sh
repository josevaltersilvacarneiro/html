#!/bin/bash
#
# Copyright (C) 2023, José Carneiro
# 
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by  
# the Free Software Foundation, either version 3 of the License, or
# any later version.
# 
# This program is distributed in the hope that it will be useful,    
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>.

apt-get install -y git
`cd /var/www/html/Src/ && composer update --no-dev`
apt-get purge -y git

a2enmod rewrite

local REDIRECT="
<Directory /var/www/html/>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
"

echo "${REDIRECT}" >> /etc/apache2/sites-available/000-default.conf

service apache2 restart && rm build.sh

# docker exec html service apache2 restart
