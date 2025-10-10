#
# -----------------------------------------------------------------------------
# "土豆食堂" (Lynx) 项目 - 公网环境 (Internet) Dockerfile
# -----------------------------------------------------------------------------
#
# 功能:
# 1. 基于 PHP 8.4-FPM Alpine 镜像。
# 2. 已更换为阿里云的 Alpine 软件源，加速依赖安装。
# 3. 安装 Laravel 所需的常用 PHP 扩展 (pdo_mysql, bcmath, gd, intl, zip)。
# 4. 安装 Composer 并将其用于依赖管理。
# 5. 优化生产环境，不安装 dev 依赖。
#
# 构建命令 (通过 docker-compose.internet.yml 自动执行):
# docker compose -f docker-compose.internet.yml up -d --build
#
# -----------------------------------------------------------------------------

# 使用官方的 PHP 8.4 FPM Alpine 镜像作为基础
FROM php:8.4-fpm-alpine

# 设置工作目录
WORKDIR /var/www/html

# =================================================================
# **优化: 更换为国内阿里云的软件源以加速安装**
# =================================================================
RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories

# 安装编译 PHP 扩展所需的系统依赖
# 使用 .build-deps 虚拟包可以在之后轻松地将它们全部删除
RUN apk add --no-cache \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    icu-dev \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    bcmath \
    gd \
    intl \
    zip \
    opcache \
    && apk del .build-deps

# 从官方 Composer 镜像中拷贝 Composer 可执行文件
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 拷贝 composer 文件并安装依赖
COPY composer.json composer.lock ./
# 生产环境优化：不安装开发依赖，并优化自动加载
# 注意：如果需要在容器内运行 seeder，请暂时移除 --no-dev 参数并重新构建
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --prefer-dist --optimize-autoloader

# 拷贝项目的其余代码
COPY . .

# 设置目录权限
# 注意：最终的权限修复推荐在容器启动后通过 docker exec 执行，以确保挂载卷的权限正确
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 暴露 9000 端口，供 Nginx (FastCGI) 连接
EXPOSE 9000