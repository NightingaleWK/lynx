# **"土豆食堂"项目阿里云服务器生产环境部署手册（纯公网 IP 版）**

本文档将指导您如何将 "土豆食堂" (Lynx) Laravel 项目成功部署到阿里云 Ubuntu 24.04 服务器上，**仅使用公网 IP（无域名）**，并配置 HTTPS 访问。

**部署信息:**

* **云服务器:** 阿里云 Ubuntu Server 24.04  
* **公网 IP:** 123.57.128.170（请替换为您的实际 IP）  
* **访问地址:** https://123.57.128.170  
* **核心工具:** Docker Engine, Nginx Proxy Manager
* **SSL 方案:** 自签名证书 / 免费 IP 证书 / 快速添加域名

---

## **整体思路说明**

您的当前部署方案（本地 Laravel Sail 开发 → GitHub 上传 → 阿里云 Docker 容器化部署 → NPM 反向代理 + SSL）非常成熟，核心流程无需大改。即使没有域名，只有公网 IP（如 123.57.128.170），也可以实现类似部署，并为 IP 配置 HTTPS。

### **关键挑战**
**主流免费 SSL 证书服务（如 Let's Encrypt）仅支持域名验证，不支持纯 IP 地址**。直接为公网 IP 颁发证书需要额外的验证（如 IP 所有权证明），且浏览器访问 `https://IP` 时可能显示"证书不匹配"警告（因为证书 CN 字段通常是域名）。

### **推荐方案**
1. **保持容器化部署不变**：Docker Compose（docker-compose.internet.yml）、Nginx（default.conf）、PHP-FPM 等服务逻辑相同，只需调整配置中的域名引用。
2. **NPM 作为代理**：将 Proxy Host 配置为 IP 地址（而非域名），监听 80/443 端口，转发到后端 Nginx。
3. **SSL 证书处理**：绕过 Let's Encrypt，使用**自签名证书**（快速测试）或**免费/低成本 IP 专用证书**（如 ZeroSSL 或 JoySSL）。生产环境建议注册一个廉价域名（年费几元）来简化。
4. **访问方式**：用户通过 `https://123.57.128.170` 访问，强制 HTTPS 重定向。
5. **风险与优化**：浏览器警告不可避免（可忽略或用企业 CA），安全组放行 80/443。长期看，添加域名能提升兼容性和 SEO。

**整个调整只需 30-60 分钟。**

---

## **第一阶段：服务器准备**

### **步骤 1：初始化服务器系统**

1. 准备一台新的阿里云服务器，或对现有服务器重装系统，选择 **Ubuntu 24.04**。  
2. 配置服务器的 root 密码。  
3. 通过 SSH 客户端（如 Xshell）登录到您的服务器。  
4. 执行系统更新，确保所有基础软件都是最新版本： 
   ```sh
   sudo apt update  
   sudo apt upgrade -y
   ```

### **步骤 2：安装 Docker Engine (国内服务器优化)**

我们将使用阿里云的镜像源来加速 Docker Engine 的安装过程。

1. 添加阿里云镜像站的 Docker GPG 密钥
   ```sh 
   sudo apt-get install ca-certificates curl -y 
   
   sudo install -m 0755 -d /etc/apt/keyrings  

   sudo curl -fsSL https://mirrors.aliyun.com/docker-ce/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg  

   sudo chmod a+r /etc/apt/keyrings/docker.gpg
   ```

2. 添加阿里云的 Docker APT 软件源
   此命令会自动检测您的系统版本并创建正确的软件源配置文件。  
   ```sh
   echo   
     "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://mirrors.aliyun.com/docker-ce/linux/ubuntu   
     $(. /etc/os-release && echo "${UBUNTU_CODENAME:-$VERSION_CODENAME}") stable" |   
     sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
   ```
3. 更新软件源索引并安装 Docker
   ```sh
   sudo apt-get update  
   sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin -y

   # 此命令会一并安装 Docker 引擎、命令行工具、containerd 以及 Docker Compose 插件。
   ```  
4. 验证安装
   运行以下命令，如果能看到版本号，则说明 Docker 和 Docker Compose 都已成功安装。  
   ```sh
   docker --version  
   docker compose version
   ```

### **步骤 3：配置 Docker Hub 加速镜像**

为了加速后续拉取 Docker 镜像的速度，我们需要配置镜像加速器。

1. 创建或修改 Docker 的配置文件： 
   ```sh
   sudo mkdir -p /etc/docker  
   sudo vim /etc/docker/daemon.json
   ```

2. 将以下 JSON 内容复制并粘贴到文件中：
   ```json
   {  
     "registry-mirrors": [  
       "https://u8uqbcma.mirror.aliyuncs.com",  
       "https://docker.m.daocloud.io",  
       "https://hub-mirror.c.163.com",  
       "https://mirror.baidubce.com",  
       "https://ccr.ccs.tencentyun.com"  
     ]  
   }
   ```
   
3. 保存并退出 (按 ESC，输入 :wq，回车)。

4. 重启 Docker 服务以使配置生效：  
   ```sh
   sudo systemctl daemon-reload  
   sudo systemctl restart docker
   ```

### **步骤 4：配置安全组**

⚠️ **重要：** 确保阿里云服务器的安全组已放行以下端口的入站流量：
- **80 端口 (HTTP)**：用于 HTTP 访问和证书验证
- **443 端口 (HTTPS)**：用于 HTTPS 访问
- **81 端口 (NPM 管理)**：用于 Nginx Proxy Manager 管理后台（可选，配置完成后建议关闭）

---

## **第二阶段：部署 Nginx Proxy Manager (NPM)**

1. 在服务器上，为 NPM 创建一个专用的工作目录：  
   ```sh
   sudo mkdir -p /opt/npm/data  
   sudo mkdir /opt/npm/letsencrypt
   ```

2. 进入该目录并创建一个 docker-compose.yml 文件：
   ```sh
   cd /opt/npm  
   sudo vim docker-compose.yml
   ```

3. 将以下内容复制并粘贴到这个新文件中，然后保存退出：
   ```yaml
   services:
     app:
       image: 'jc21/nginx-proxy-manager:latest'
       restart: unless-stopped
       ports:
         # These ports are in format <host-port>:<container-port>
         - '80:80' # Public HTTP Port
         - '443:443' # Public HTTPS Port
         - '81:81' # Admin Web Port
       volumes:
         - ./data:/data
         - ./letsencrypt:/etc/letsencrypt
       networks:
         - npm_default

   networks:
     npm_default:
       name: npm_default
   ```  
4. 启动 Nginx Proxy Manager:  
   ```sh
   sudo docker compose up -d
   ```
5. **初始化 NPM**：  
   * 打开浏览器，访问 `http://您的公网IP:81`（例如：http://123.57.128.170:81）。  
   * 使用默认账户首次登录：
     - Email: `admin@example.com`
     - Password: `changeme`
   * **立即修改密码**，建议使用强密码。

---

## **第三阶段：在服务器上部署项目**

### **步骤 1：获取项目代码**

1. 在服务器上，选择一个位置存放您的项目代码，例如 /var/www：  
   ```sh
   sudo mkdir -p /var/www  
   cd /var/www
   ```

2. 从您的 Git 仓库克隆项目代码：  
   ```sh
   sudo git clone https://github.com/NightingaleWK/lynx.git
   cd lynx
   ```

### **步骤 2：创建并配置 .env 文件**

1. 复制示例文件：  
   ```sh
   sudo cp .env.example .env
   ```

2. 编辑 .env 文件： 
   ```sh
   sudo vim .env
   ```

3. **进行以下关键修改**（⚠️ 重点：APP_URL 使用 IP 地址）： 
   ```env
   APP_NAME=Lynx  
   APP_ENV=production  
   APP_DEBUG=false  
   # ⚠️ 使用您的公网 IP，而非域名
   APP_URL=https://123.57.128.170

   # 留空，后续命令生成  
   APP_KEY=

   DB_CONNECTION=mysql  
   # 必须是 docker-compose.internet.yml 中定义的服务名  
   DB_HOST=mysql  
   DB_PORT=3306  
   DB_DATABASE=lynx  
   DB_USERNAME=sail  
   # !!! 强烈建议修改为一个更复杂的随机密码 !!!  
   DB_PASSWORD=password

   REDIS_HOST=redis  
   REDIS_PASSWORD=null  
   REDIS_PORT=6379
   ```
4. 保存并退出。

### **步骤 3：调整 Nginx 配置（支持 IP 访问）**

编辑项目中的 Nginx 配置文件，使其支持 IP 访问：

```sh
sudo vim docker-internet/nginx/default.conf
```

找到 `server` 配置块，将 `server_name` 那一行修改为：

```nginx
server {
    # 添加 default_server，移除或注释 server_name
    listen 80 default_server;
    # server_name lynx.wkarrow.top;  # 注释掉原域名配置
    
    root /var/www/public;
    index index.php index.html;
    
    # ... 其他配置保持不变 ...
}
```

保存并退出。

### **步骤 4：构建并启动应用容器**

1. 在项目根目录 (/var/www/lynx) 下，使用 docker-compose.internet.yml 文件执行构建命令：  
   ```sh
   sudo docker compose -f docker-compose.internet.yml up -d --build
   ```
2. **执行 Laravel 生产环境初始化命令 (顺序很重要)**:  
   * **安装 Composer 后端依赖**:  
      ```sh
      sudo docker compose -f docker-compose.internet.yml exec app composer install --no-dev --optimize-autoloader
      ```
   * **生成 APP_KEY**:  
      ```sh
      sudo docker compose -f docker-compose.internet.yml exec app php artisan key:generate
      ```
   * **安装 NPM 前端依赖**:  
      ```sh
      sudo docker compose -f docker-compose.internet.yml exec app npm install
      ```
   * **构建前端生产资源**:  
      ```sh
      sudo docker compose -f docker-compose.internet.yml exec app npm run build
      ```
   * **检查并删除 hot 文件**:  
      ```sh
      sudo rm -f public/hot
      ```
   * **创建存储链接**:  
      ```sh
      sudo docker compose -f docker-compose.internet.yml exec app php artisan storage:link
      ```
   * **发布 Filament 核心资产**:  
      ```sh
      sudo docker compose -f docker-compose.internet.yml exec app php artisan filament:assets
      ```
   * **运行数据库迁移**:  
      ```sh
      sudo docker compose -f docker-compose.internet.yml exec app php artisan migrate --force
      ```
   * **优化生产环境缓存**:  
      ```sh
      sudo docker compose -f docker-compose.internet.yml exec app php artisan optimize
      ```
   * **修复目录权限 (最后一步)**:  
      ```sh
      sudo docker compose -f docker-compose.internet.yml exec app chown -R www-data:www-data storage bootstrap/cache
      ```

---

## **第四阶段：配置反向代理（HTTP 先验证）**

在配置 SSL 证书之前，我们先配置 HTTP 代理，确保基本功能正常。

1. 打开并登录 NPM 管理后台（http://您的公网IP:81，例如 http://123.57.128.170:81）。  
2. 导航到 **Hosts** → **Proxy Hosts**，然后点击 **Add Proxy Host** 按钮。  
3. **填写 Details 选项卡**:  
   * **Domain Names:** 留空（或填写您的公网 IP，如 123.57.128.170，NPM 会自动处理）
   * **Scheme:** `http`  
   * **Forward Hostname / IP:** `lynx-internet-nginx`（这是 docker-compose.internet.yml 中定义的 Nginx 容器名）
   * **Forward Port:** `80`  
   * **勾选 Block Common Exploits**  
4. **先不配置 SSL**，直接点击 **Save** 按钮保存。

5. **测试 HTTP 访问**：
   - 打开浏览器，访问 `http://您的公网IP`（例如 http://123.57.128.170）
   - 应该能看到您的应用首页（无 HTTPS）

---

## **第五阶段：为公网 IP 配置 SSL 证书**

⚠️ **重要说明**：由于 Let's Encrypt 不支持为纯 IP 地址颁发证书，我们需要采用替代方案。以下提供三种方案，请根据您的需求选择：

### **方案 A：自签名证书（推荐测试，5 分钟快速部署）**

这是最快速的方案，适合测试环境或内部使用。浏览器会显示警告，但加密连接有效。

#### **步骤 1：生成自签名证书**

在服务器上执行以下命令：

```sh
# 创建证书存储目录
sudo mkdir -p /opt/ssl
cd /opt/ssl

# 生成自签名证书（有效期 365 天）
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout server.key \
  -out server.crt \
  -subj "/C=CN/ST=Beijing/L=Beijing/O=Personal/OU=IT/CN=您的公网IP"

# 例如：-subj "/C=CN/ST=Beijing/L=Beijing/O=Personal/OU=IT/CN=123.57.128.170"
```

**说明**：
- `-days 365`：证书有效期 1 年
- `-subj` 中的 `CN=您的公网IP`：请替换为您的实际公网 IP（如 123.57.128.170）
- 生成后会得到两个文件：`server.key`（私钥）和 `server.crt`（证书）

#### **步骤 2：上传证书到 NPM**

1. 查看证书内容并复制：
   ```sh
   # 查看证书内容（复制输出）
   sudo cat /opt/ssl/server.crt
   
   # 查看私钥内容（复制输出）
   sudo cat /opt/ssl/server.key
   ```

2. 登录 NPM 管理后台（http://您的公网IP:81）

3. 导航到 **SSL Certificates** → 点击 **Add SSL Certificate** → 选择 **Custom**

4. 填写表单：
   - **Name:** 随便起个名字，如 `IP-Self-Signed`
   - **Certificate Key:** 粘贴 `server.crt` 的完整内容（包括 `-----BEGIN CERTIFICATE-----` 和 `-----END CERTIFICATE-----`）
   - **Private Key:** 粘贴 `server.key` 的完整内容（包括 `-----BEGIN PRIVATE KEY-----` 和 `-----END PRIVATE KEY-----`）
   - **Intermediate Certificate:** 留空

5. 点击 **Save** 保存

#### **步骤 3：应用证书到 Proxy Host**

1. 返回 **Hosts** → **Proxy Hosts**，找到刚才创建的代理规则，点击右侧的 **编辑** 按钮

2. 切换到 **SSL** 选项卡

3. 配置 SSL：
   - **SSL Certificate:** 从下拉列表中选择刚才上传的 `IP-Self-Signed` 证书
   - **勾选 Force SSL**（强制 HTTPS）
   - **勾选 HTTP/2 Support**（启用 HTTP/2）

4. 点击 **Save** 保存

#### **步骤 4：重启 NPM**

```sh
cd /opt/npm
sudo docker compose restart
```

#### **步骤 5：验证 HTTPS 访问**

打开浏览器，访问 `https://您的公网IP`（例如 https://123.57.128.170）：

- ⚠️ 浏览器会显示"您的连接不是私密连接"或"NET::ERR_CERT_AUTHORITY_INVALID"警告
- 点击"高级" → "继续前往 IP 地址（不安全）"
- 应该能看到您的应用，且地址栏显示 🔒 锁图标（尽管有警告）

**优点**：快速、免费、无需外部服务  
**缺点**：浏览器警告，不适合公开生产环境

---

### **方案 B：免费 IP 专用证书（推荐生产，ZeroSSL 示例）**

这是生产环境的推荐方案，证书由 CA 签发，但需要验证 IP 所有权。

#### **步骤 1：生成 CSR（证书签名请求）**

在服务器上执行：

```sh
sudo mkdir -p /opt/ssl
cd /opt/ssl

# 生成私钥和 CSR
sudo openssl req -new -newkey rsa:2048 -nodes \
  -keyout server.key \
  -out server.csr \
  -subj "/C=CN/ST=Beijing/L=Beijing/O=Personal/OU=IT/CN=您的公网IP"

# 例如：-subj "/C=CN/ST=Beijing/L=Beijing/O=Personal/OU=IT/CN=123.57.128.170"
```

#### **步骤 2：在 ZeroSSL 申请证书**

1. 访问 [https://zerossl.com](https://zerossl.com)，注册一个免费账号

2. 登录后，点击 **New Certificate**

3. 输入您的公网 IP（如 `123.57.128.170`），点击 **Next**

4. 选择 **90-Day Certificate**（免费），点击 **Next**

5. 选择 **Manual Verification** → **HTTP File Upload**

6. ZeroSSL 会提供一个验证文件（如 `.well-known/pki-validation/xxxxx.txt`）

#### **步骤 3：完成 IP 所有权验证**

ZeroSSL 会要求您在服务器上创建一个验证文件：

```sh
# 进入应用的 public 目录
cd /var/www/lynx/public

# 创建验证目录
sudo mkdir -p .well-known/pki-validation

# 下载或创建验证文件（ZeroSSL 会提供文件内容）
sudo vim .well-known/pki-validation/XXXXXXXXXXXXXXX.txt
# 将 ZeroSSL 提供的内容粘贴进去，保存退出

# 设置权限
sudo chown -R www-data:www-data .well-known
```

在浏览器中访问 `http://您的公网IP/.well-known/pki-validation/XXXXXXXXXXXXXXX.txt`，确保能看到文件内容，然后在 ZeroSSL 点击 **Verify**。

#### **步骤 4：下载证书**

验证通过后，ZeroSSL 会生成证书：

1. 下载证书文件（通常包含 `certificate.crt` 和 `ca_bundle.crt`）

2. 合并证书链：
   ```sh
   cat certificate.crt ca_bundle.crt > fullchain.crt
   ```

3. 将 `fullchain.crt` 和 `server.key` 上传到 NPM（步骤同方案 A）

#### **步骤 5：应用证书**

按照方案 A 的步骤 3-5 操作，将证书应用到 Proxy Host。

**优点**：CA 签发，浏览器无警告（部分情况下）  
**缺点**：需要验证 IP 所有权，证书 90 天到期需续期

---

### **方案 C：快速添加域名（最推荐生产，几元成本）**

这是**最简单且最可靠**的生产方案，注册一个廉价域名即可使用原 Let's Encrypt 流程。

#### **步骤 1：注册域名**

- **免费域名**：[Freenom.com](https://www.freenom.com)（提供 .tk/.ml/.ga 等免费域名）
- **廉价域名**：阿里云/腾讯云（.top/.xyz 等域名年费 5-10 元）

#### **步骤 2：添加 A 记录**

在域名注册商的 DNS 管理页面，添加 A 记录：

- **主机记录:** `lynx`（或 `@` 表示根域名）
- **记录类型:** `A`
- **记录值:** 您的公网 IP（如 `123.57.128.170`）
- **TTL:** 默认（如 600 秒）

#### **步骤 3：修改配置文件**

1. 修改 `.env` 文件：
   ```sh
   sudo vim /var/www/lynx/.env
   ```
   将 `APP_URL` 改为域名：
   ```env
   APP_URL=https://lynx.yourdomain.com
   ```

2. 修改 Nginx 配置：
   ```sh
   sudo vim /var/www/lynx/docker-internet/nginx/default.conf
   ```
   恢复 `server_name`：
   ```nginx
   server {
       listen 80;
       server_name lynx.yourdomain.com;  # 改为您的域名
       # ...
   }
   ```

3. 重启容器：
   ```sh
   cd /var/www/lynx
   sudo docker compose -f docker-compose.internet.yml restart
   sudo docker compose -f docker-compose.internet.yml exec app php artisan optimize
   ```

#### **步骤 4：在 NPM 使用 Let's Encrypt**

1. 编辑 NPM 的 Proxy Host：
   - **Domain Names:** `lynx.yourdomain.com`（改为您的域名）
   - **Forward Hostname / IP:** `lynx-internet-nginx`
   - **Forward Port:** `80`

2. 切换到 **SSL** 选项卡：
   - **SSL Certificate:** 选择 **Request a new SSL Certificate**
   - 勾选 **Force SSL**、**HTTP/2 Support**
   - **Email Address for Let's Encrypt:** 输入您的邮箱
   - 勾选 **I Agree to the Let's Encrypt Terms of Service**

3. 点击 **Save**，NPM 会自动申请并配置证书（几秒钟内完成）

**优点**：自动续期、浏览器完全信任、零警告、SEO 友好  
**缺点**：需要几元成本（但非常低）

---

## **第六阶段：访问与验证**

根据您选择的 SSL 方案：

### **方案 A 或 B（IP 访问）**
- 访问：`https://您的公网IP`（例如 https://123.57.128.170）
- 方案 A 会有浏览器警告（点击"高级"继续）
- 方案 B 可能无警告（取决于 CA 和浏览器）

### **方案 C（域名访问）**
- 访问：`https://您的域名`（例如 https://lynx.yourdomain.com）
- 完全无警告，显示绿色锁图标

---

## **日常运维**

所有运维命令都应使用 `-f docker-compose.internet.yml` 参数。

### **启动服务**
```sh
sudo docker compose -f docker-compose.internet.yml up -d
```

### **停止服务**
```sh
sudo docker compose -f docker-compose.internet.yml down
```

### **查看日志**
```sh
# 查看应用日志
sudo docker compose -f docker-compose.internet.yml logs app

# 查看 Nginx 日志
sudo docker compose -f docker-compose.internet.yml logs nginx

# 实时查看日志
sudo docker compose -f docker-compose.internet.yml logs -f
```

### **更新代码后重新部署**
```sh
cd /var/www/lynx  
sudo git pull  
sudo docker compose -f docker-compose.internet.yml up -d --build  
# 根据需要运行其他初始化命令
sudo docker compose -f docker-compose.internet.yml exec app composer install --no-dev --optimize-autoloader
sudo docker compose -f docker-compose.internet.yml exec app npm install
sudo docker compose -f docker-compose.internet.yml exec app npm run build
sudo docker compose -f docker-compose.internet.yml exec app php artisan migrate --force
sudo docker compose -f docker-compose.internet.yml exec app php artisan optimize:clear  
sudo docker compose -f docker-compose.internet.yml exec app php artisan optimize  
```

### **清除缓存**
```sh
sudo docker compose -f docker-compose.internet.yml exec app php artisan optimize:clear
sudo docker compose -f docker-compose.internet.yml exec app php artisan cache:clear
sudo docker compose -f docker-compose.internet.yml exec app php artisan config:clear
sudo docker compose -f docker-compose.internet.yml exec app php artisan route:clear
sudo docker compose -f docker-compose.internet.yml exec app php artisan view:clear
```

### **重新优化**
```sh
sudo docker compose -f docker-compose.internet.yml exec app php artisan optimize
sudo docker compose -f docker-compose.internet.yml exec app php artisan config:cache
sudo docker compose -f docker-compose.internet.yml exec app php artisan route:cache
sudo docker compose -f docker-compose.internet.yml exec app php artisan view:cache
```

---

## **重要注意事项**

### **关于浏览器警告**
- **自签名证书（方案 A）**：浏览器会显示"您的连接不是私密连接"警告，这是正常现象。原因是证书不是由受信任的 CA 签发的。
  - Chrome：显示"NET::ERR_CERT_AUTHORITY_INVALID"
  - Firefox：显示"警告：潜在的安全风险"
  - 解决方法：点击"高级" → "继续前往"（不安全）
  - 生产环境建议使用方案 B 或 C

### **关于 IP 证书的兼容性**
- 部分旧版浏览器可能不支持 IP 证书
- 移动端（iOS/Android）对 IP 证书更严格
- 企业防火墙可能拦截 IP HTTPS 访问
- **长期建议**：使用方案 C（域名），成本低且兼容性最好

### **关于证书续期**
- **自签名证书（方案 A）**：到期后需重新生成并上传（每 365 天）
- **ZeroSSL（方案 B）**：证书有效期 90 天，到期前需重新验证并下载
- **Let's Encrypt（方案 C）**：NPM 会自动续期，无需人工干预

### **关于安全性**
- 自签名证书提供的加密强度与 CA 签发的证书相同，只是浏览器不信任签发者
- 如果只是个人或小团队使用，方案 A 完全够用
- 如果需要对外公开访问，强烈建议使用方案 C

### **关于性能**
- IP 访问与域名访问在性能上无差异
- HTTPS 会有轻微性能开销（可忽略）
- HTTP/2 可以提升性能（已在 NPM 中启用）

---

## **故障排查**

### **无法访问应用**
1. 检查容器是否正常运行：
   ```sh
   sudo docker compose -f docker-compose.internet.yml ps
   ```
2. 检查安全组是否放行 80/443 端口
3. 检查 NPM 日志：
   ```sh
   cd /opt/npm
   sudo docker compose logs
   ```

### **HTTPS 无法访问**
1. 检查 NPM 的 SSL 配置是否正确
2. 确认证书已正确上传
3. 检查防火墙是否拦截 443 端口：
   ```sh
   sudo ufw status
   sudo ufw allow 443/tcp
   ```

### **页面样式错乱**
1. 检查 `.env` 中的 `APP_URL` 是否正确（应为 `https://IP` 或 `https://域名`）
2. 清除缓存并重新优化：
   ```sh
   sudo docker compose -f docker-compose.internet.yml exec app php artisan optimize:clear
   sudo docker compose -f docker-compose.internet.yml exec app php artisan optimize
   ```
3. 检查 Nginx 是否正确传递 `X-Forwarded-Proto` 头部

### **数据库连接失败**
1. 检查 `.env` 中的数据库配置
2. 确认 MySQL 容器正常运行：
   ```sh
   sudo docker compose -f docker-compose.internet.yml exec mysql mysql -u sail -p
   ```
3. 检查容器网络：
   ```sh
   sudo docker network inspect lynx-internet_default
   ```

---

## **总结**

**部署时长预估**：
- 方案 A（自签名）：30 分钟
- 方案 B（ZeroSSL）：60 分钟
- 方案 C（域名）：45 分钟（含域名注册时间）

**推荐方案**：
- **测试/内部使用**：方案 A（最快）
- **生产环境**：方案 C（最可靠，只需几元）
- **特殊需求**：方案 B（需要 IP 证书但想避免警告）

**核心优势**：
- ✅ 无需域名即可部署
- ✅ 完整 HTTPS 支持
- ✅ Docker 容器化，易维护
- ✅ NPM 统一管理，易扩展

**主要限制**：
- ⚠️ IP 证书可能有浏览器警告
- ⚠️ SEO 不友好（无域名）
- ⚠️ 移动端兼容性较差

如有问题，请检查日志或根据"故障排查"章节操作！
