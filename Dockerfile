FROM php:8.2-fpm

# Install Nginx and PHP extensions.
RUN apt-get update && apt-get install -y \
    libpq-dev \
    nginx \
    && docker-php-ext-install pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set the environment variable for the application directory.
ENV APP_DIR /server/http

# Copy application code to container.
COPY . ${APP_DIR}

# Copy Nginx configuration file from the project into the container.
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy the custom entrypoint script that will start both services.
COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Set working directory.
WORKDIR ${APP_DIR}

# Expose port 80 which Nginx will use.
EXPOSE 80

# Start the entrypoint script.
ENTRYPOINT ["/entrypoint.sh"]
