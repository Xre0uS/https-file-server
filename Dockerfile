FROM ubuntu/apache2

# Install necessary packages
RUN apt update && \
    apt -y install php8.3 libapache2-mod-php && \
    a2enmod ssl && \
    a2enmod php8.3 && \
    a2ensite default-ssl.conf && \
    a2dissite 000-default.conf

# Set PHP upload limits
RUN echo "file_uploads = On" >> /etc/php/8.3/apache2/php.ini && \
    echo "upload_max_filesize = 500M" >> /etc/php/8.3/apache2/php.ini && \
    echo "post_max_size = 1G" >> /etc/php/8.3/apache2/php.ini

# Set working directory
RUN mkdir /server/
WORKDIR /server/

# Create SSL directories and copy certificates
RUN mkdir -p /etc/ssl/certs/
RUN mkdir -p /etc/ssl/private/
COPY apache-selfsigned.key /etc/ssl/private/apache-selfsigned.key
COPY apache-selfsigned.crt /etc/ssl/certs/apache-selfsigned.crt

# Set proper permissions for SSL files
RUN chmod 600 /etc/ssl/private/apache-selfsigned.key && \
    chmod 600 /etc/ssl/certs/apache-selfsigned.crt && \
    chown www-data:www-data /etc/ssl/private/apache-selfsigned.key /etc/ssl/certs/apache-selfsigned.crt

# Copy Apache configuration and web files
COPY default-ssl.conf /etc/apache2/sites-available/default-ssl.conf
COPY upload.php /var/www/html/upload.php
COPY list_files.php /var/www/html/list_files.php
COPY index.html /var/www/html/index.html

# Debug: Check if SSL files exist in the container
RUN ls -l /etc/ssl/certs/apache-selfsigned.crt && ls -l /etc/ssl/private/apache-selfsigned.key

# Set entrypoint
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

# Start Apache when the container runs
CMD ["apachectl", "-D", "FOREGROUND"]

