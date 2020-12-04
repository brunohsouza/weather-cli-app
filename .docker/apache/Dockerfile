FROM httpd:2.4.33-alpine

# Copy apache vhost file to proxy php requests to php-fpm container
COPY weather-app.conf /usr/local/apache2/conf/weather-app.conf
RUN echo "Include /usr/local/apache2/conf/weather-app.conf" \
    >> /usr/local/apache2/conf/httpd.conf
RUN sed -i '/LoadModule rewrite_module/s/^#//g' /usr/local/apache2/conf/httpd.conf

