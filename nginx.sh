#!/usr/bin/with-contenv sh
sed 's@API_PORT@'"$PORT"'@' /etc/nginx/companies.tpl.conf > /etc/nginx/sites-available/companies.conf
exec nginx;