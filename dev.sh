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

cat requirements.txt | xargs sudo apt install -y
sudo curl -sS https://getcomposer.org/installer -o composer-setup.php && sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
cd Src/ && composer update
cp example.env .env
[ -f composer-setup.php ] && rm composer-setup.php
