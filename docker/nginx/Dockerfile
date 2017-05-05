FROM nginx:latest

MAINTAINER Vanderson Nunes <vann.nunes@gmail.com>

ADD conf/nginx.conf /etc/nginx/
ADD sites/* /etc/nginx/sites-available/
ADD sites/* /etc/nginx/sites-enabled/

RUN echo "upstream php-upstream { server game-gym-php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN usermod -u 1000 www-data

CMD ["nginx"]

EXPOSE 80 443
