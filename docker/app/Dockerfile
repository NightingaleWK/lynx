# 使用官方的 PHP 8.2-FPM Alpine 镜像作为基础
FROM php:8.2-fpm-alpine

# 设置工作目录
WORKDIR /var/www/html

# 安装编译 PHP 扩展所需的系统依赖库
# zlib-dev 解决了 gd 扩展的问题
# libpng-dev, jpeg-dev, freetype-dev 是 gd 扩展常用的依赖
# icu-dev 是本次报错的核心，为 intl 扩展提供依赖
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    zlib-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    icu-dev

# 安装 PHP 扩展
# 1. 配置 gd 扩展以支持 freetype 和 jpeg
# 2. 安装所有需要的扩展
# 3. 安装 redis 扩展
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql bcmath gd exif intl \
    && pecl install redis \
    && docker-php-ext-enable redis

# 安装 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 拷贝 composer 文件并安装依赖
COPY composer.json composer.lock ./
# 在生产环境中，我们通常不安装开发依赖 (--no-dev)，并且优化自动加载器 (-o)
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist --no-dev -o

# 拷贝项目代码
COPY . .

# 设置文件权限，确保 web 服务器和 php-fpm 进程有权写入日志和缓存
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 暴露 9000 端口给 Nginx 使用
EXPOSE 9000

