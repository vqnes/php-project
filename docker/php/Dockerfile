FROM webdevops/php-apache-dev:7.4
COPY ./script  /tmp
RUN chmod +x /tmp/script
CMD /tmp/script
WORKDIR /var/www/html
