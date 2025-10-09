# **“土豆食堂”项目 Docker 化部署与运维手册**

本文档详细记录了如何将 “土豆食堂” (Lynx) 这个 Laravel 12 项目通过 Docker 进行容器化打包、部署、运行及日常维护。

个人项目，已开源：[https://github.com/NightingaleWK/lynx](https://github.com/NightingaleWK/lynx)

## **一、环境准备**

在开始之前，请确保您的部署目标机器（例如 Windows 11）已安装并运行 Docker Desktop。

## **二、项目改造（首次部署需执行）**

以下文件需要在您本地的项目代码中创建。

### **1. 目录结构**

为了清晰地区分生产环境与 Sail 开发环境的配置，我们将所有生产部署相关的文件都存放在一个新的 docker-prod 目录中。

在项目根目录下，创建如下的目录和文件结构：
```
your-laravel-project/
├── docker-prod/ # <-- 新建，存放所有生产环境配置
│ ├── app/
│ │ └── Dockerfile
│ └── nginx/
│ └── default.conf
├── docker/# <-- 这是 Sail 的开发环境目录 (保留不变)
├── docker-compose.prod.yml# <-- 新建 (注意文件名)
└── .dockerignore# <-- 新建
```
### **2. 创建核心配置文件**

请根据后续提供的文件内容，在您的项目中创建或替换以下文件。

* docker-compose.prod.yml (服务编排文件)
* docker-prod/app/Dockerfile (PHP 应用镜像构建文件)
* docker-prod/nginx/default.conf (Nginx 配置文件)
* .dockerignore (Docker 忽略文件)

### **3. 修改 Laravel 配置 (.env)**

1. 复制 .env.example 为 .env 文件。
2. **关键修改**：确保数据库和 Redis 的 HOST 指向 Docker Compose 中定义的服务名。
```env
APP_ENV=production
APP_DEBUG=false
APP_KEY= # 留空，后续会通过命令生成

DB_CONNECTION=mysql
DB_HOST=mysql # <-- 必须是 mysql
DB_PORT=3306
DB_DATABASE=lynx# 您的数据库名
DB_USERNAME=sail# 您的用户名
DB_PASSWORD=password# 您的密码

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis# <-- 必须是 redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### **4. 生成 SSL 证书 (局域网 HTTPS)**

1. 在项目根目录下创建目录 docker-prod/certs。
2. 打开终端 (如 Git Bash 或 WSL)，进入项目根目录，执行以下命令：
```sh
openssl req -x509 -nodes -days 3650 -newkey rsa:2048 -keyout docker-prod/ certs/lynx-key.pem -out docker-prod/certs/lynx.pem
```
3. **重要提示**：在执行过程中，当提示输入 Common Name 时，**请输入您部署机器的局域网 IP 地址**（例如 192.168.1.100）。
4. 执行后，请检查 docker-prod/certs 目录下是否已生成 lynx.pem 和 lynx-key.pem 两个文件。

## **三、部署与初始化流程**

### **步骤 1：构建并启动所有容器**

在项目根目录下，打开终端，执行以下命令。此命令会根据 Dockerfile 构建应用镜像，并以后台模式启动所有服务。
```sh
# --no-cache 确保使用最新的基础镜像，避免缓存问题
# --build 强制重新构建镜像 
docker-compose -f docker-compose.prod.yml up -d --build --no-cache
```
### **步骤 2：初始化 Laravel 应用**

容器首次启动后，数据库是空的，应用也没有配置密钥。我们需要进入 app 容器执行一系列初始化命令。

1. **生成应用密钥 (APP_KEY)**:
```sh
docker-compose -f docker-compose.prod.yml exec app php artisan key:generate
```

2. 运行数据库迁移:
 此命令会根据 database/migrations 目录下的文件，在数据库中创建所有需要的数据表（包括 cache 表等）。\
```sh
docker-compose -f docker-compose.prod.yml exec app php artisan migrate
```

3. 修正 storage 目录权限:
 这是解决 "Permission denied" 错误的关键步骤。此命令将容器内的 storage 和 bootstrap/cache 目录的所有权交给 php-fpm 的运行用户 www-data。
```SH
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data storage bootstrap/cache
```
4. （可选）填充测试数据:
 如果您需要在测试时填充数据，可以运行此命令。请注意，生产环境不应执行此操作。
```SH
# --force 参数可以跳过生产环境的确认提示
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --force
```
*注：若需填充数据，请确保 Dockerfile 中 composer install 命令**没有**使用 --no-dev 参数，以保证 Faker 等开发包被安装。*

### **步骤 3：访问您的应用**

完成以上步骤后，您的应用已经成功部署并运行。

* 打开浏览器，访问 https://<您的部署机器IP>。
* 浏览器会提示证书不安全，请选择“高级” -> “继续前往”。
* 您应该能看到 Laravel 的欢迎页面或您的应用首页。

## **四、日常运维命令**

### **启动/停止服务**

* **启动所有服务** (后台运行):
```sh
docker-compose -f docker-compose.prod.yml up -d
```

* **停止并移除所有容器**:
```sh
docker-compose -f docker-compose.prod.yml down
```

*此操作不会删除数据库和 Redis 的持久化数据。*

### **查看状态与日志**

* **查看所有容器的运行状态**:
```sh
docker-compose -f docker-compose.prod.yml ps
```

* **实时查看所有服务的日志**:
```sh
docker-compose -f docker-compose.prod.yml logs -f
```

* **只看特定服务的日志** (例如 app 或 queue-worker):
```sh
docker-compose -f docker-compose.prod.yml logs -f app
docker-compose -f docker-compose.prod.yml logs -f queue-worker
```

### **代码更新流程**

当您通过 git pull 更新了项目代码后：

1. **重新构建镜像并重启服务**:
```sh
docker-compose -f docker-compose.prod.yml up -d --build
```

2. **（如果需要）运行新的数据库迁移**:
```sh
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

3. **（推荐）清理并重建配置缓存**:
```sh
docker-compose -f docker-compose.prod.yml exec app php artisan optimize:clear
```

### **执行 Artisan 命令**

您可以在 app 容器内执行任何 Artisan 命令，语法如下：
```sh
docker-compose -f docker-compose.prod.yml exec app php artisan <您的命令>
```