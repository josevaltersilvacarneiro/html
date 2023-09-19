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
RUN bash -c "apt-get update && apt-get upgrade -y && apt-get install apache2 php8.2 php8.2-gd php8.2-intl php8.2-xsl php8.2-mbstring -y"
RUN bash -c "rm -rf /var/www/html/*"
COPY . .
EXPOSE 80 443
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
