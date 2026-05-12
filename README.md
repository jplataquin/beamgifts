# Beam Gifts - Nginx Installation Guide

This guide provides step-by-step instructions for deploying the Beam Gifts application on a Linux server running Nginx.

## 📋 Prerequisites

Ensure your server meets the following requirements:
- **OS:** Ubuntu 22.04 LTS or later (recommended)
- **PHP:** 8.2 or higher
- **Extensions:** `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `gd`, `hash`, `mbstring`, `openssl`, `pcre`, `pdo`, `session`, `tokenizer`, `xml`
- **Database:** MySQL 8.0+ or MariaDB 10.6+
- **Web Server:** Nginx
- **Tools:** Composer, Node.js (v18+), NPM, Git

## 🚀 Installation Steps

### 1. Clone the Repository
```bash
cd /var/www
git clone https://github.com/your-repo/beamgifts.git
cd beamgifts
```

### 2. Install Dependencies
**PHP Dependencies:**
```bash
composer install --optimize-autoloader --no-dev
```

**Frontend Assets:**
```bash
npm install
npm run build
```

### 3. Environment Configuration
Copy the example environment file and generate the application key:
```bash
cp .env.example .env
php artisan key:generate --ansi
```
Edit `.env` to configure your database, application URL, and HitPay credentials:
```bash
nano .env
```

### 4. Database Setup
Run the migrations and seeders:
```bash
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder --force
```

### 5. Directory Permissions
Laravel requires certain directories to be writable by the web server (usually `www-data`):
```bash
chown -R www-data:www-data /var/www/beamgifts
chmod -R 775 /var/www/beamgifts/storage
chmod -R 775 /var/www/beamgifts/bootstrap/cache
```

### 6. Storage Link
Create the symbolic link for file uploads:
```bash
php artisan storage:link
```

## 🌐 Nginx Configuration

Create a new Nginx configuration file:
```bash
sudo nano /etc/nginx/sites-available/beamgifts
```

Paste the following configuration (adjust `server_name` and PHP-FPM socket path as needed):

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com;
    root /var/www/beamgifts/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site and restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/beamgifts /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

## 🔒 SSL (Recommended)
Use Certbot to obtain a free Let's Encrypt SSL certificate:
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

## 🛠️ Post-Installation

### Optimization
For production environments, run:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Task Scheduling
Add the Laravel scheduler to your crontab:
```bash
* * * * * cd /var/www/beamgifts && php artisan schedule:run >> /dev/null 2>&1
```

---
*Created by Gemini CLI - May 12, 2026*
