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

FROM ubuntu:23.10
LABEL net.josevaltersilvacarneiro.author="José Carneiro <git@josevaltersilvacarneiro.net>"

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends apt-utils
RUN apt-get update && apt-get upgrade -y && apt-get install curl apache2 php8.2 php8.2_mysql php8.2-curl php8.2-gd php8.2-intl php8.2-xsl php8.2-mbstring -y
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf /var/www/html/*

COPY . .

EXPOSE 80 443
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
