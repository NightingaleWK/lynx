# 1. 基础镜像升级到 PHP 8.4
FROM php:8.4-fpm-alpine

# 设置工作目录
WORKDIR /var/www/html

# 2. 优化构建过程：合并依赖安装，并使用 virtual group 以便后续清理
# 新增了 pcntl 和 sockets 扩展所需的依赖
RUN apk add --no-cache \
    # 核心系统工具
    zip \
    unzip \
    # PHP 扩展依赖
    libzip-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    icu-dev \
    # 创建一个名为 .build-deps 的临时构建依赖组
    && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    zlib-dev

# 3. 借鉴 Sail，补全核心扩展
# 新增了 pcntl 和 sockets
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    bcmath \
    gd \
    exif \
    intl \
    zip \
    pcntl \
    sockets \
    && pecl install redis \
    && docker-php-ext-enable redis

# 4. 清理工作：删除临时的构建依赖，减小镜像体积
RUN apk del .build-deps

# 安装 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 拷贝 composer 文件并安装依赖
COPY composer.json composer.lock ./
# 生产环境优化：不安装开发依赖，并优化自动加载
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist --no-dev -o

# 拷贝项目代码
COPY . .

# 设置文件权限
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 暴露 9000 端口
EXPOSE 9000

