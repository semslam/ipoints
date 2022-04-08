FROM shimohira/php-apache-composer:7.2-apache
RUN echo "ServerName https://ipoints.ng/" >> /etc/apache2/apache2.conf
# RUN apt-get update && apt-get -y install curl cron supervisor
RUN apt-get update && docker-php-ext-install bcmath && apt-get install nano
RUN apt-get -y install curl gnupg
RUN curl -sL https://deb.nodesource.com/setup_11.x  | bash -
RUN apt-get -y install nodejs
RUN npm install pm2@latest -g
ARG environment=staging
ENV CI_ENV=$environment
ARG siteUrl=default
ENV SITE_URL=$siteUrl
ARG dbHost=localhost
ENV DB_HOST=$dbHost
ARG dbName=mydb
ENV DB_NAME=$dbName
ARG dbUser=default
ENV DB_USER=$dbUser
ARG dbPassword=default
ENV DB_PASSWORD=$dbPassword
RUN echo "export CI_ENV=${CI_ENV}" | tee /etc/environment
RUN echo "export SITE_URL=${SITE_URL}" >> /etc/environment
RUN echo "export DB_HOST=${DB_HOST}" >> /etc/environment
RUN echo "export DB_NAME=${DB_NAME}" >> /etc/environment
RUN echo "export DB_USER=${DB_USER}" >> /etc/environment
RUN echo "export DB_PASSWORD=${DB_PASSWORD}" >> /etc/environment
RUN echo ". /etc/environment" >> /etc/apache2/envars
RUN mkdir -p /home/ipoints/._ssl
COPY ./rds-combined-ca-bundle.pem /home/ipoints/._ssl
COPY composer.json /var/www/html
COPY composer.lock /var/www/html
# COPY ecosystem.config.js /var/www/html/
WORKDIR /var/www/html
RUN composer install --no-dev
COPY . /var/www/html
# RUN echo "file_uploads = On\n" \
#          "memory_limit = 500M\n" \
#          "upload_max_filesize = 500M\n" \
#          "post_max_size = 500M\n" \
#          "max_execution_time = 1200\n" \
#          > /usr/local/etc/php/conf.d/uploads.ini
RUN chmod -R 775 /var/www/html
RUN chown -R :www-data /var/www/html
# EXPOSE 5672 15672 5671
RUN /usr/local/bin/php /var/www/html/index.php cli migrate up y
EXPOSE 80
# CMD ["pm2-runtime","start","ecosystem.config.js"]