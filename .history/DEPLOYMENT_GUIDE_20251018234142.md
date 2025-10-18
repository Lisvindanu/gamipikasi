# 🚀 Panduan Deploy Laravel ke VPS

## 📋 Persyaratan

### Server Requirements
- Ubuntu 20.04 LTS atau lebih baru
- Minimal 1GB RAM
- PHP 8.2 atau lebih baru
- MySQL 8.0 atau MariaDB 10.3+
- Composer
- Nginx atau Apache
- Git

---

## 🔧 1. Persiapan VPS

### Update sistem
```bash
sudo apt update && sudo apt upgrade -y
```

### Install PHP 8.2 dan extensions yang diperlukan
```bash
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common php8.2-mysql \
php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath \
php8.2-intl php8.2-redis php8.2-opcache
```

### Install MySQL
```bash
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

### Install Composer
```bash
cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
```

### Install Nginx
```bash
sudo apt install -y nginx
```

### Install Git
```bash
sudo apt install -y git
```

---

## 🗂️ 2. Setup Database

### Login ke MySQL
```bash
sudo mysql
```

### Buat database dan user
```sql
CREATE DATABASE gamifikasi_gdgoc;
CREATE USER 'gdgoc_user'@'localhost' IDENTIFIED BY 'password_yang_kuat_123';
GRANT ALL PRIVILEGES ON gamifikasi_gdgoc.* TO 'gdgoc_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 📦 3. Deploy Aplikasi

### Clone repository
```bash
cd /var/www
sudo git clone https://github.com/username/gamifikasi-gdgoc.git
cd gamifikasi-gdgoc
```

### Set permissions
```bash
sudo chown -R www-data:www-data /var/www/gamifikasi-gdgoc
sudo chmod -R 755 /var/www/gamifikasi-gdgoc
sudo chmod -R 775 /var/www/gamifikasi-gdgoc/storage
sudo chmod -R 775 /var/www/gamifikasi-gdgoc/bootstrap/cache
```

### Install dependencies
```bash
sudo -u www-data composer install --optimize-autoloader --no-dev
```

### Setup environment
```bash
sudo cp .env.example .env
sudo nano .env
```

Edit `.env` dengan konfigurasi production:
```env
APP_NAME="GDGoC Gamification"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://gdgoc.example.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gamifikasi_gdgoc
DB_USERNAME=gdgoc_user
DB_PASSWORD=password_yang_kuat_123

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Generate APP_KEY
```bash
sudo php artisan key:generate
```

### Run migrations
```bash
sudo php artisan migrate --force
```

### Seed database dengan real users
```bash
sudo php artisan db:seed --class=DepartmentSeeder --force
sudo php artisan db:seed --class=RealUserSeeder --force
sudo php artisan db:seed --class=BadgeSeeder --force
```

### Setup storage link
```bash
sudo php artisan storage:link
```

### Optimize aplikasi
```bash
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache
sudo php artisan optimize
```

---

## 🌐 4. Konfigurasi Nginx

### Buat config file
```bash
sudo nano /etc/nginx/sites-available/gamifikasi-gdgoc
```

Isi dengan:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name gdgoc.example.com www.gdgoc.example.com;
    root /var/www/gamifikasi-gdgoc/public;

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

    # Client body size (untuk upload file)
    client_max_body_size 100M;
}
```

### Enable site
```bash
sudo ln -s /etc/nginx/sites-available/gamifikasi-gdgoc /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## 🔒 5. SSL dengan Let's Encrypt (HTTPS)

### Install Certbot
```bash
sudo apt install -y certbot python3-certbot-nginx
```

### Generate SSL certificate
```bash
sudo certbot --nginx -d gdgoc.example.com -d www.gdgoc.example.com
```

Follow the prompts dan pilih redirect HTTP to HTTPS.

### Auto-renewal SSL
```bash
sudo systemctl status certbot.timer
```

Test renewal:
```bash
sudo certbot renew --dry-run
```

---

## 🔄 6. Update Aplikasi (Git Pull)

Buat script untuk update:
```bash
sudo nano /var/www/update.sh
```

Isi dengan:
```bash
#!/bin/bash

cd /var/www/gamifikasi-gdgoc

# Pull latest changes
git pull origin main

# Install/update dependencies
sudo -u www-data composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Clear and recache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Fix permissions
sudo chown -R www-data:www-data /var/www/gamifikasi-gdgoc
sudo chmod -R 755 /var/www/gamifikasi-gdgoc
sudo chmod -R 775 /var/www/gamifikasi-gdgoc/storage
sudo chmod -R 775 /var/www/gamifikasi-gdgoc/bootstrap/cache

echo "✅ Update completed!"
```

Make it executable:
```bash
sudo chmod +x /var/www/update.sh
```

Untuk update aplikasi:
```bash
sudo /var/www/update.sh
```

---

## 📊 7. Monitoring & Maintenance

### Check logs
```bash
# Laravel logs
tail -f /var/www/gamifikasi-gdgoc/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log

# PHP-FPM logs
tail -f /var/log/php8.2-fpm.log
```

### Check services
```bash
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status mysql
```

### Restart services
```bash
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo systemctl restart mysql
```

---

## 🔐 8. Security Best Practices

### Setup Firewall (UFW)
```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
sudo ufw status
```

### Disable directory listing di Nginx
Already configured in the nginx config above.

### Setup fail2ban (optional, untuk protect SSH)
```bash
sudo apt install -y fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

### Regular backups
```bash
# Backup database
mysqldump -u gdgoc_user -p gamifikasi_gdgoc > backup_$(date +%Y%m%d).sql

# Backup uploaded files
tar -czf storage_backup_$(date +%Y%m%d).tar.gz /var/www/gamifikasi-gdgoc/storage/app/public
```

---

## 🐛 Troubleshooting

### Permission issues
```bash
sudo chown -R www-data:www-data /var/www/gamifikasi-gdgoc
sudo chmod -R 755 /var/www/gamifikasi-gdgoc
sudo chmod -R 775 /var/www/gamifikasi-gdgoc/storage
sudo chmod -R 775 /var/www/gamifikasi-gdgoc/bootstrap/cache
```

### 500 Internal Server Error
```bash
# Check Laravel logs
tail -f /var/www/gamifikasi-gdgoc/storage/logs/laravel.log

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Re-cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database connection failed
- Check `.env` credentials
- Make sure MySQL is running: `sudo systemctl status mysql`
- Test connection: `mysql -u gdgoc_user -p gamifikasi_gdgoc`

### File upload issues
- Check `client_max_body_size` in nginx config
- Check `upload_max_filesize` and `post_max_size` in php.ini:
```bash
sudo nano /etc/php/8.2/fpm/php.ini
# Set:
upload_max_filesize = 100M
post_max_size = 100M

sudo systemctl restart php8.2-fpm
```

---

## 📝 Default Credentials

Setelah deployment, login dengan:

**Lead:**
- Email: `narapati.lead@gdgoc.id`
- Password: `gdgoc2024`

**Co-Lead:**
- Email: `nadziffa123@gmail.com`
- Password: `gdgoc2024`

**Secretary:**
- Email: `anisaseptiani475@gmail.com`
- Password: `gdgoc2024`

**Bendahara:**
- Email: `ptriaprili34@gmail.com`
- Password: `gdgoc2024`

**⚠️ PENTING: Ganti semua password setelah first login!**

---

## ✅ Checklist Deploy

- [ ] Update sistem dan install dependencies
- [ ] Setup database
- [ ] Clone repository
- [ ] Set permissions
- [ ] Install composer dependencies
- [ ] Setup `.env` file
- [ ] Generate APP_KEY
- [ ] Run migrations
- [ ] Seed database dengan RealUserSeeder
- [ ] Setup storage link
- [ ] Optimize aplikasi (cache config, routes, views)
- [ ] Konfigurasi Nginx
- [ ] Setup SSL dengan Let's Encrypt
- [ ] Setup firewall (UFW)
- [ ] Test aplikasi di browser
- [ ] Setup backup strategy
- [ ] Ganti default passwords

---

**Good luck dengan deployment! 🚀**
