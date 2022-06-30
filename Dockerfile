FROM registry.gitlab.com/gsdiamondscore/backend:base

ARG RUN_COMPOSER=false
ARG RUN_PROD_COMPOSER=false

ADD auth.json /root/.composer/auth.json
ADD .ci_conf/www.conf /usr/local/etc/php-fpm.d/www.conf


COPY . /var/www/html/

RUN  if [ "$RUN_COMPOSER" == "true" ]; then cd /var/www/html/ && composer install || true; fi
RUN  if [ "$RUN_PROD_COMPOSER" == "true" ]; then cd /var/www/html/ && composer install --no-dev || true; fi

RUN php /var/www/html/artisan storage:link

RUN  chown -R nginx:nginx /var/www/html/* 

