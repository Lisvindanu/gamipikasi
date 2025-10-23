# 🚀 GDGoC Gamification - Deployment Guide

Dokumentasi lengkap untuk deployment aplikasi GDGoC Gamification ke server baru.

---

## 📋 Table of Contents

1. [System Requirements](#system-requirements)
2. [Pre-Installation](#pre-installation)
3. [Installation Steps](#installation-steps)
4. [Environment Configuration](#environment-configuration)
5. [Database Setup](#database-setup)
6. [File Permissions](#file-permissions)
7. [Queue Worker Setup](#queue-worker-setup)
8. [Web Server Configuration](#web-server-configuration)
9. [SSL/HTTPS Setup](#sslhttps-setup)
10. [Post-Deployment](#post-deployment)
11. [Troubleshooting](#troubleshooting)

---

## 🖥️ System Requirements

### Minimum Requirements:
- **OS**: Ubuntu 20.04 LTS or higher / Debian 10+
- **PHP**: 8.2 or higher
- **MySQL**: 8.0 or higher / MariaDB 10.3+
- **Web Server**: Nginx or Apache
- **Composer**: Latest version
- **Node.js**: 18.x or higher
- **NPM**: 9.x or higher
- **Memory**: 2GB RAM minimum (4GB recommended)
- **Storage**: 10GB minimum

### Required PHP Extensions:
```bash
php8.2-cli
php8.2-fpm
php8.2-mysql
php8.2-mbstring
php8.2-xml
php8.2-curl
php8.2-zip
php8.2-gd
php8.2-bcmath
php8.2-intl
php8.2-soap
php8.2-redis (optional)
```

---

## 🔧 Pre-Installation

### 1. Update System
```bash
sudo apt update && sudo apt upgrade -y
```

### 2. Install PHP 8.2
```bash
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.2 php8.2-fpm php8.2-cli php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl php8.2-soap -y
```

### 3. Install MySQL
```bash
sudo apt install mysql-server -y
sudo mysql_secure_installation
```

### 4. Install Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

### 5. Install Node.js & NPM
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y
node --version
npm --version
```

### 6. Install Nginx (or Apache)
```bash
sudo apt install nginx -y
```

---

## 📦 Installation Steps

### 1. Clone Repository
```bash
cd /var/www
sudo git clone <repository-url> gamipikasi
cd gamipikasi
```

### 2. Set Ownership
```bash
sudo chown -R www-data:www-data /var/www/gamipikasi
sudo chmod -R 755 /var/www/gamipikasi
```

### 3. Install PHP Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### 4. Install Node Dependencies & Build Assets
```bash
npm install
npm run build
```

### 5. Copy Environment File
```bash
cp .env.example .env
```

---

## ⚙️ Environment Configuration

Edit `.env` file dengan konfigurasi server baru:

```env
APP_NAME="GDGoC Gamification"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gamifikasi_gdgoc
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Mail Configuration (Gmail SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=gdsc@unpas.ac.id
MAIL_PASSWORD="ozoz wcce dzwi bqnh"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="gdsc@unpas.ac.id"
MAIL_FROM_NAME="GDGoC Gamification"

# Queue Configuration
QUEUE_CONNECTION=database

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database

# File Storage
FILESYSTEM_DISK=local

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error
```

### Generate Application Key
```bash
php artisan key:generate
```

---

## 🗄️ Database Setup

### 1. Create Database
```bash
sudo mysql -u root -p
```

```sql
CREATE DATABASE gamifikasi_gdgoc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'gdgoc_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON gamifikasi_gdgoc.* TO 'gdgoc_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 2. Run Migrations
```bash
php artisan migrate --force
```

### 3. Seed Database
```bash
php artisan db:seed --force
```

### 4. Create Storage Symlink
```bash
php artisan storage:link
```

---

## 🔐 File Permissions

Set proper permissions for Laravel:

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/gamipikasi

# Set directory permissions
sudo find /var/www/gamipikasi -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/gamipikasi -type f -exec chmod 644 {} \;

# Storage & Cache writable
sudo chmod -R 775 /var/www/gamipikasi/storage
sudo chmod -R 775 /var/www/gamipikasi/bootstrap/cache

# Make sure www-data owns these directories
sudo chown -R www-data:www-data /var/www/gamipikasi/storage
sudo chown -R www-data:www-data /var/www/gamipikasi/bootstrap/cache
```

---

## 🔄 Queue Worker Setup

### Option 1: Systemd Service (Recommended)

Create service file:
```bash
sudo nano /etc/systemd/system/gdgoc-queue.service
```

Add this configuration:
```ini
[Unit]
Description=GDGoC Queue Worker
After=network.target mysql.service

[Service]
Type=simple
User=www-data
Group=www-data
Restart=always
RestartSec=5s
ExecStart=/usr/bin/php /var/www/gamipikasi/artisan queue:work --sleep=3 --tries=3 --timeout=60

[Install]
WantedBy=multi-user.target
```

Enable and start service:
```bash
sudo systemctl daemon-reload
sudo systemctl enable gdgoc-queue.service
sudo systemctl start gdgoc-queue.service
sudo systemctl status gdgoc-queue.service
```

### Option 2: Supervisor (Alternative)

Install supervisor:
```bash
sudo apt install supervisor -y
```

Create configuration:
```bash
sudo nano /etc/supervisor/conf.d/gdgoc-queue.conf
```

Add:
```ini
[program:gdgoc-queue]
process_name=%(program_name)s_%(process_num)02d
command=/usr/bin/php /var/www/gamipikasi/artisan queue:work --sleep=3 --tries=3 --timeout=60
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/gamipikasi/storage/logs/queue-worker.log
stopwaitsecs=3600
```

Start supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start gdgoc-queue:*
```

---

## 🌐 Web Server Configuration

### Nginx Configuration

Create site configuration:
```bash
sudo nano /etc/nginx/sites-available/gdgoc
```

Add this configuration:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com www.your-domain.com;

    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name your-domain.com www.your-domain.com;

    root /var/www/gamipikasi/public;
    index index.php index.html;

    # SSL Configuration (update paths after getting SSL)
    ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    # Cloudflare Real IP (if using Cloudflare)
    set_real_ip_from 103.21.244.0/22;
    set_real_ip_from 103.22.200.0/22;
    set_real_ip_from 103.31.4.0/22;
    set_real_ip_from 104.16.0.0/13;
    set_real_ip_from 104.24.0.0/14;
    set_real_ip_from 108.162.192.0/18;
    set_real_ip_from 131.0.72.0/22;
    set_real_ip_from 141.101.64.0/18;
    set_real_ip_from 162.158.0.0/15;
    set_real_ip_from 172.64.0.0/13;
    set_real_ip_from 173.245.48.0/20;
    set_real_ip_from 188.114.96.0/20;
    set_real_ip_from 190.93.240.0/20;
    set_real_ip_from 197.234.240.0/22;
    set_real_ip_from 198.41.128.0/17;
    set_real_ip_from 2400:cb00::/32;
    set_real_ip_from 2606:4700::/32;
    set_real_ip_from 2803:f800::/32;
    set_real_ip_from 2405:b500::/32;
    set_real_ip_from 2405:8100::/32;
    set_real_ip_from 2c0f:f248::/32;
    set_real_ip_from 2a06:98c0::/29;
    real_ip_header CF-Connecting-IP;

    # Increase upload size
    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Disable access to sensitive files
    location ~ /\. {
        deny all;
    }

    # Logs
    access_log /var/log/nginx/gdgoc-access.log;
    error_log /var/log/nginx/gdgoc-error.log;
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/gdgoc /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## 🔒 SSL/HTTPS Setup

### Install Certbot
```bash
sudo apt install certbot python3-certbot-nginx -y
```

### Get SSL Certificate
```bash
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

### Auto-renewal
Certbot automatically adds renewal to cron. Test renewal:
```bash
sudo certbot renew --dry-run
```

---

## 💾 Database Migration (Existing Data)

Jika Anda sudah memiliki database yang running di server lama dan ingin pindahkan ke server baru:

### Step 1: Backup Database dari Server Lama

**Option A: Using mysqldump (Recommended)**

SSH ke server lama, lalu jalankan:

```bash
# Backup database
mysqldump -u root -p gamifikasi_gdgoc > gdgoc_backup_$(date +%Y%m%d_%H%M%S).sql

# Compress backup untuk transfer lebih cepat
gzip gdgoc_backup_*.sql
```

File backup akan ada di: `gdgoc_backup_YYYYMMDD_HHMMSS.sql.gz`

**Option B: Using phpMyAdmin (Alternative)**

1. Login ke phpMyAdmin di server lama
2. Pilih database `gamifikasi_gdgoc`
3. Tab **Export**
4. Method: **Quick**
5. Format: **SQL**
6. Click **Go**
7. Download file `.sql`

**Option C: Direct MySQL Command (Fastest)**

Jika ada akses langsung dari komputer lokal:

```bash
# Dari komputer lokal, backup dari server lama
ssh user@old-server.com "mysqldump -u root -p gamifikasi_gdgoc | gzip" > gdgoc_backup.sql.gz
```

### Step 2: Backup Storage Files (Uploads, Avatars, Attachments)

Backup folder storage yang berisi file uploads:

```bash
# Dari server lama
cd /var/www/gamipikasi
tar -czf storage_backup_$(date +%Y%m%d).tar.gz storage/app/public storage/app/avatars storage/app/attachments

# Download ke komputer lokal menggunakan scp
# Dari komputer lokal:
scp user@old-server.com:/var/www/gamipikasi/storage_backup_*.tar.gz ./
```

### Step 3: Transfer ke Server Baru

**Option A: Using SCP (Secure Copy)**

Dari komputer lokal:

```bash
# Transfer database backup
scp gdgoc_backup_*.sql.gz user@new-server.com:/tmp/

# Transfer storage backup
scp storage_backup_*.tar.gz user@new-server.com:/tmp/
```

**Option B: Using rsync (Faster for large files)**

```bash
# Sync storage langsung antar server
rsync -avz -e ssh user@old-server:/var/www/gamipikasi/storage/app/public/ user@new-server:/var/www/gamipikasi/storage/app/public/
```

**Option C: Using FTP/SFTP Client**

Menggunakan FileZilla, WinSCP, atau Cyberduck:
1. Connect ke server lama
2. Download backup files
3. Connect ke server baru
4. Upload backup files ke `/tmp/`

### Step 4: Restore Database di Server Baru

SSH ke server baru:

```bash
# Pindah ke directory tmp
cd /tmp

# Decompress backup jika terkompress
gunzip gdgoc_backup_*.sql.gz

# Pastikan database sudah dibuat
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS gamifikasi_gdgoc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Restore database
mysql -u gdgoc_user -p gamifikasi_gdgoc < gdgoc_backup_*.sql

# Atau jika masih terkompress, bisa langsung:
gunzip < gdgoc_backup_*.sql.gz | mysql -u gdgoc_user -p gamifikasi_gdgoc
```

**Verify restore:**

```bash
mysql -u gdgoc_user -p gamifikasi_gdgoc -e "SELECT COUNT(*) as user_count FROM users;"
mysql -u gdgoc_user -p gamifikasi_gdgoc -e "SELECT COUNT(*) as task_count FROM tasks;"
mysql -u gdgoc_user -p gamifikasi_gdgoc -e "SELECT COUNT(*) as point_count FROM points;"
```

### Step 5: Restore Storage Files

```bash
# Extract storage backup
cd /var/www/gamipikasi
sudo tar -xzf /tmp/storage_backup_*.tar.gz

# Set proper permissions
sudo chown -R www-data:www-data storage/
sudo chmod -R 775 storage/

# Recreate storage symlink jika belum ada
php artisan storage:link
```

### Step 6: Update Application Configuration

Edit `.env` di server baru jika ada perubahan:

```bash
cd /var/www/gamipikasi
nano .env
```

Update jika perlu:
- `APP_URL` - domain baru
- `DB_*` credentials jika berbeda
- Email settings jika ada perubahan

Clear cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Step 7: Test Application

```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
>>> \App\Models\User::count();

# Test file storage
ls -la storage/app/public/
```

Akses aplikasi via browser dan test:
- ✅ Login dengan user existing
- ✅ View tasks
- ✅ Upload file
- ✅ View leaderboard
- ✅ Check avatar images

### Step 8: Cleanup

Setelah verify semua berjalan dengan baik:

```bash
# Hapus backup files dari /tmp
sudo rm /tmp/gdgoc_backup_*.sql
sudo rm /tmp/gdgoc_backup_*.sql.gz
sudo rm /tmp/storage_backup_*.tar.gz

# OPTIONAL: Keep backup di safe location
mkdir -p ~/backups
mv /tmp/gdgoc_backup_*.sql.gz ~/backups/
```

---

## 🔄 Automated Migration Script

Untuk mempermudah, saya buatkan script automation:

Create file `migrate.sh` di server baru:

```bash
#!/bin/bash

# GDGoC Database Migration Script
# Usage: ./migrate.sh backup_file.sql.gz storage_backup.tar.gz

echo "🚀 GDGoC Database Migration Tool"
echo "=================================="

# Check if backup files provided
if [ -z "$1" ] || [ -z "$2" ]; then
    echo "❌ Error: Please provide backup files"
    echo "Usage: ./migrate.sh database_backup.sql.gz storage_backup.tar.gz"
    exit 1
fi

DB_BACKUP=$1
STORAGE_BACKUP=$2
DB_NAME="gamifikasi_gdgoc"
DB_USER="gdgoc_user"

# Read database password
read -sp "Enter MySQL password for $DB_USER: " DB_PASS
echo

# Step 1: Restore Database
echo ""
echo "📦 Step 1: Restoring database..."
gunzip < $DB_BACKUP | mysql -u $DB_USER -p$DB_PASS $DB_NAME

if [ $? -eq 0 ]; then
    echo "✅ Database restored successfully"
else
    echo "❌ Database restore failed"
    exit 1
fi

# Step 2: Restore Storage
echo ""
echo "📦 Step 2: Restoring storage files..."
cd /var/www/gamipikasi
sudo tar -xzf $STORAGE_BACKUP

if [ $? -eq 0 ]; then
    echo "✅ Storage files restored successfully"
else
    echo "❌ Storage restore failed"
    exit 1
fi

# Step 3: Fix Permissions
echo ""
echo "🔐 Step 3: Setting permissions..."
sudo chown -R www-data:www-data storage/
sudo chmod -R 775 storage/

# Step 4: Clear Cache
echo ""
echo "🧹 Step 4: Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize

# Step 5: Recreate storage link
echo ""
echo "🔗 Step 5: Recreating storage symlink..."
php artisan storage:link

# Verification
echo ""
echo "✅ Migration completed!"
echo ""
echo "📊 Verification:"
mysql -u $DB_USER -p$DB_PASS $DB_NAME -e "SELECT COUNT(*) as users FROM users;"
mysql -u $DB_USER -p$DB_PASS $DB_NAME -e "SELECT COUNT(*) as tasks FROM tasks;"
mysql -u $DB_USER -p$DB_PASS $DB_NAME -e "SELECT COUNT(*) as points FROM points;"

echo ""
echo "🎯 Next steps:"
echo "1. Test application in browser"
echo "2. Check all features working"
echo "3. Start queue worker: sudo systemctl start gdgoc-queue"
echo "4. Clean up backup files from /tmp"
```

Make executable:

```bash
chmod +x migrate.sh
```

Run migration:

```bash
./migrate.sh /tmp/gdgoc_backup_20251023.sql.gz /tmp/storage_backup_20251023.tar.gz
```

---

## 🎯 Post-Deployment

### 1. Optimize Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 2. Set Cron for Scheduled Tasks
```bash
sudo crontab -e -u www-data
```

Add:
```cron
* * * * * cd /var/www/gamipikasi && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Setup Logrotate
```bash
sudo nano /etc/logrotate.d/laravel
```

Add:
```
/var/www/gamipikasi/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

### 4. Test Email
```bash
php artisan tinker
```

```php
Mail::raw('Test email from GDGoC', function($message) {
    $message->to('test@example.com')->subject('Test Email');
});
```

### 5. Check Queue Worker
```bash
sudo systemctl status gdgoc-queue.service
```

---

## 🔍 Troubleshooting

### Permission Issues
```bash
sudo chmod -R 775 /var/www/gamipikasi/storage
sudo chmod -R 775 /var/www/gamipikasi/bootstrap/cache
sudo chown -R www-data:www-data /var/www/gamipikasi
```

### Clear All Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Check Logs
```bash
# Laravel logs
tail -f /var/www/gamipikasi/storage/logs/laravel.log

# Nginx logs
sudo tail -f /var/log/nginx/gdgoc-error.log

# Queue worker logs
sudo journalctl -u gdgoc-queue.service -f
```

### Database Connection Issues
```bash
# Test connection
php artisan tinker
DB::connection()->getPdo();
```

### Queue Not Processing
```bash
# Restart queue worker
sudo systemctl restart gdgoc-queue.service

# Check queue status
php artisan queue:work --once

# Check failed jobs
php artisan queue:failed
```

### 502 Bad Gateway
```bash
# Check PHP-FPM status
sudo systemctl status php8.2-fpm

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm

# Check PHP-FPM logs
sudo tail -f /var/log/php8.2-fpm.log
```

---

## 📝 Important Notes

### Default Login Credentials

**Lead:**
- Email: `narapatikeysa00@gmail.com`
- Password: `gdgoc2024`

**Co-Lead:**
- Email: `nadziffa123@gmail.com`
- Password: `gdgoc2024`

**IMPORTANT:** Change these passwords immediately after first login!

### Email Configuration

The system uses Gmail SMTP with App Password:
- Email: `gdsc@unpas.ac.id`
- App Password: `ozoz wcce dzwi bqnh`
- Make sure to keep this secure and update if needed

### Backup Strategy

**Daily Backups:**
```bash
# Database backup
mysqldump -u gdgoc_user -p gamifikasi_gdgoc > backup_$(date +%Y%m%d).sql

# Files backup
tar -czf files_backup_$(date +%Y%m%d).tar.gz /var/www/gamipikasi/storage/app
```

**Setup automated backups:**
```bash
sudo crontab -e
```

Add:
```cron
# Database backup daily at 2 AM
0 2 * * * mysqldump -u gdgoc_user -p'your_password' gamifikasi_gdgoc | gzip > /backup/db_$(date +\%Y\%m\%d).sql.gz

# Keep only last 7 days
0 3 * * * find /backup -name "db_*.sql.gz" -mtime +7 -delete
```

---

## 🚀 Quick Deployment Checklist

- [ ] System requirements met
- [ ] PHP 8.2+ installed
- [ ] MySQL installed and configured
- [ ] Composer installed
- [ ] Node.js & NPM installed
- [ ] Repository cloned
- [ ] Dependencies installed (composer & npm)
- [ ] `.env` configured
- [ ] Database created and migrated
- [ ] Storage symlink created
- [ ] File permissions set correctly
- [ ] Queue worker running
- [ ] Nginx/Apache configured
- [ ] SSL certificate installed
- [ ] Cron jobs configured
- [ ] Laravel optimized
- [ ] Email tested
- [ ] Backup strategy implemented
- [ ] Default passwords changed

---

## 📞 Support

For issues or questions:
- Repository: [GitHub URL]
- Email: gdsc@unpas.ac.id
- Developer: Muhammad Sufi Nadziffa Ridwan

---

**Last Updated:** 2025-10-23
**Version:** 1.0.0
