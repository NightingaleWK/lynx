#
# -----------------------------------------------------------------------------
# "土豆食堂" (Lynx) 项目 - 公网环境 (Internet) Dockerfile
# -----------------------------------------------------------------------------
#
# 功能:
# 1. 基于 PHP 8.4-FPM Alpine 镜像。
# 2. 已更换为阿里云的 Alpine 软件源，加速依赖安装。
# 3. 安装 Node.js 和 npm，用于前端资源构建。
# 4. 使用官方安装脚本安装最新版 Composer。
# 5. 安装 Laravel 所需的常用 PHP 扩展。
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

# 安装编译 PHP 扩展所需的系统依赖 和 前端构建所需的 Node.js/npm
# 使用 .build-deps 虚拟包可以在之后轻松地将它们全部删除
RUN apk add --no-cache \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    icu-dev \
    nodejs \
    npm \
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

# =================================================================
# **更新: 使用官方脚本安装最新版 Composer**
# 替换了原有的 COPY --from=composer:latest 方法，确保获取最新版本。
# =================================================================
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# 拷贝项目配置文件，为后续在容器外执行 composer install / npm install 做准备
COPY composer.json composer.lock package.json ./

# 拷贝项目的其余代码
COPY . .

# 设置目录权限
# 注意：最终的权限修复在容器启动后通过 docker exec 执行，以确保挂载卷的权限正确
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 暴露 9000 端口，供 Nginx (FastCGI) 连接
EXPOSE 9000