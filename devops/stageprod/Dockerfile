FROM jincort/base-nginx-php7-fpm:1.0

MAINTAINER Alexander Sedelnikov <alexander.sedelnikov@ymail.com>

WORKDIR /var/www/companies

ADD . .
ADD devops/stageprod/etc /etc/
ADD devops/stageprod/local /usr/local/

RUN mkdir -p ./storage/framework/cache ./storage/framework/sessions ./storage/framework/views ./storage/logs ./storage/app/public && \
    composer install || true && \
    chown -R nginx:nginx /var/www/companies

USER root
EXPOSE 80

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
