# 使用官方的 PHP 8.2-FPM Alpine 镜像作为基础
FROM php:8.2-fpm-alpine

# 设置工作目录
WORKDIR /var/www/html

# 安装 PHP 扩展 (根据你的项目需求增删)
# Laravel 常用扩展: pdo_mysql, redis, bcmath, gd, exif, intl
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo_mysql bcmath gd exif intl \
    && apk del .build-deps

# 安装 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 拷贝 composer 文件并安装依赖
# 这样可以利用 Docker 的缓存机制，只有当 composer 文件变化时才会重新安装依赖
COPY composer.json composer.lock ./
# 在生产环境中，我们通常不安装开发依赖 (--no-dev)，并且优化自动加载器 (-o)
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist --no-dev -o

# 拷贝项目代码
COPY . .

# 设置文件权限，确保 web 服务器和 php-fpm 进程有权写入日志和缓存
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 暴露 9000 端口给 Nginx 使用
EXPOSE 9000