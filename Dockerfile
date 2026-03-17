# =============================================================================
# DEPLOY NOW WORKSHOP — Dockerfile
# =============================================================================
#
# This Dockerfile uses a MULTI-STAGE BUILD pattern:
#
#   Stage 1 (builder): Install all tools needed to compile/build the app.
#   Stage 2 (runtime): Copy only the final app — no build tools, no dev deps.
#
# Why? Smaller images are faster to deploy and more secure.
# The runtime image only contains what the app needs to RUN, nothing more.
#
# This same Dockerfile is used by:
#   - Render (production deployment)
#   - Docker Compose locally (compose.local.yml)
# That consistency is the point of containers.
# =============================================================================

# ---- Stage 1: Build ----
# Install PHP, Composer, and Laravel dependencies.
# This stage is only used during the build — it won't be in the final image.
FROM php:8.2-cli AS builder

ENV DEBIAN_FRONTEND=noninteractive

# Install system libraries needed to compile PHP extensions.
# mbstring is required by Laravel for string manipulation.
RUN apt-get update \
 && apt-get install -y --no-install-recommends unzip libonig-dev pkg-config \
 && docker-php-ext-configure mbstring \
 && docker-php-ext-install -j"$(nproc)" mbstring \
 && rm -rf /var/lib/apt/lists/*

# Copy Composer from the official Composer image (no need to install it manually).
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set the working directory inside the container.
WORKDIR /app

# Install Laravel via Composer (no dev dependencies to keep it lean).
# ARG lets you override the version at build time: --build-arg LARAVEL_VERSION="^10.0"
ARG LARAVEL_VERSION="^11.0"
RUN composer create-project --no-dev --prefer-dist laravel/laravel:"${LARAVEL_VERSION}" .

# Copy our app files on top of the fresh Laravel install.
# Files in this repo (like welcome.blade.php) will override the defaults.
COPY . .

# Run Laravel optimization commands for production.
# key:generate creates the APP_KEY used for encryption.
# The clear commands remove any cached config/routes/views so fresh ones are used.
ENV APP_ENV=production
RUN php artisan key:generate --ansi \
 && php artisan config:clear \
 && php artisan route:clear \
 && php artisan view:clear

# ---- Stage 2: Runtime ----
# Start from a clean base — only copy what we need to run the app.
# Build tools, Composer, and intermediate files are NOT copied here.
FROM php:8.2-cli

ENV DEBIAN_FRONTEND=noninteractive

# Install only the runtime libraries needed to serve the app.
# Same extensions as the build stage, but without dev headers.
RUN apt-get update \
 && apt-get install -y --no-install-recommends unzip libonig-dev pkg-config \
 && docker-php-ext-configure mbstring \
 && docker-php-ext-install -j"$(nproc)" mbstring \
 && apt-get purge -y --auto-remove libonig-dev pkg-config \
 && rm -rf /var/lib/apt/lists/*

# Set the working directory.
WORKDIR /app

# Copy the fully-built app from Stage 1.
# This is the key step — we get the compiled app without the build tools.
COPY --from=builder /app /app

# Health check: periodically ping the app to make sure it's still running.
# If this check fails 3 times in a row, the container is marked unhealthy.
HEALTHCHECK --interval=30s --timeout=3s \
  CMD php -r "try{ echo file_get_contents('http://127.0.0.1:8080')?0:1; }catch(Exception \$e){ exit(1);}"

# Tell Docker that this container listens on port 8080.
# This does NOT actually open the port — that's done by Docker Compose or Render.
EXPOSE 8080

# The command that runs when the container starts.
# PHP's built-in server is fine for a workshop demo.
# For real production, you'd use nginx + php-fpm instead.
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public", "public/index.php"]
