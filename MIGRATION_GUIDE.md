# 🚚 Quick Migration Guide

Panduan singkat untuk pindah server dari A ke B.

---

## 📝 Preparation Checklist

Di **Server Lama**:
- [ ] Aplikasi berjalan dengan baik
- [ ] Semua data ter-update
- [ ] Tidak ada pending tasks/uploads yang sedang berjalan

Di **Server Baru**:
- [ ] Server sudah setup (PHP, MySQL, Nginx, dll)
- [ ] Repository sudah di-clone
- [ ] Dependencies sudah di-install (`composer install`, `npm install`)
- [ ] `.env` sudah dikonfigurasi

---

## 🎯 Quick Steps (5 Langkah)

### 1️⃣ Backup di Server Lama

```bash
cd /var/www/gamipikasi
./backup.sh
```

Script ini akan membuat:
- ✅ Database backup (`.sql.gz`)
- ✅ Storage files backup (`.tar.gz`)
- ✅ `.env` backup
- ✅ Backup info file

**Output location**: `~/gdgoc_backups/`

### 2️⃣ Download Backup ke Komputer Lokal

```bash
# Dari komputer lokal
scp -r user@old-server:~/gdgoc_backups ./
```

Atau gunakan FileZilla/WinSCP untuk download.

### 3️⃣ Upload ke Server Baru

```bash
# Dari komputer lokal
cd gdgoc_backups
scp gdgoc_db_*.sql.gz user@new-server:/tmp/
scp gdgoc_storage_*.tar.gz user@new-server:/tmp/
```

### 4️⃣ Restore di Server Baru

SSH ke server baru:

```bash
cd /var/www/gamipikasi

# Buat migration script
nano migrate.sh
# (Copy script dari DEPLOYMENT.md)

chmod +x migrate.sh

# Run migration
./migrate.sh /tmp/gdgoc_db_*.sql.gz /tmp/gdgoc_storage_*.tar.gz
```

### 5️⃣ Verify & Start Services

```bash
# Test database
php artisan tinker
>>> \App\Models\User::count();
>>> exit

# Clear cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start queue worker
sudo systemctl start gdgoc-queue

# Test di browser
curl https://your-domain.com
```

---

## 📊 Verification Checklist

Setelah migration, test hal-hal berikut:

### Authentication
- [ ] Login dengan user existing
- [ ] Logout works
- [ ] Password reset (optional)

### Core Features
- [ ] Dashboard loads with correct data
- [ ] Task board shows existing tasks
- [ ] Leaderboard shows correct rankings
- [ ] Point history visible

### File Uploads
- [ ] Avatar images terlihat
- [ ] Task attachments bisa didownload
- [ ] Upload file baru works

### Email System
- [ ] Queue worker running (`systemctl status gdgoc-queue`)
- [ ] Test create task → email sent
- [ ] Check spam folder jika email tidak masuk

### Performance
- [ ] Page load cepat
- [ ] No PHP errors di log (`tail -f storage/logs/laravel.log`)
- [ ] No nginx errors (`sudo tail -f /var/log/nginx/gdgoc-error.log`)

---

## 🔄 Alternative: Direct Server-to-Server Transfer

Jika kedua server bisa communicate langsung:

```bash
# Di server BARU, pull langsung dari server lama

# Database
ssh user@old-server "mysqldump -u root -p gamifikasi_gdgoc | gzip" | gunzip | mysql -u gdgoc_user -p gamifikasi_gdgoc

# Storage files
rsync -avz -e ssh user@old-server:/var/www/gamipikasi/storage/app/public/ /var/www/gamipikasi/storage/app/public/
```

**Keuntungan**: Lebih cepat, tidak perlu download ke komputer lokal
**Kekurangan**: Harus ada akses SSH antar server

---

## ⏱️ Estimated Time

| Step | Time |
|------|------|
| Backup di server lama | 2-5 menit |
| Download backup | 5-15 menit (tergantung koneksi) |
| Upload ke server baru | 5-15 menit |
| Restore database | 1-3 menit |
| Restore storage | 1-2 menit |
| Verification | 5-10 menit |
| **Total** | **~20-50 menit** |

*Waktu tergantung ukuran database dan kecepatan internet*

---

## 🆘 Common Issues & Solutions

### Issue 1: Database Restore Gagal

**Error**: `Access denied for user`

**Solution**:
```bash
# Pastikan user sudah dibuat dan punya akses
mysql -u root -p
CREATE USER 'gdgoc_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON gamifikasi_gdgoc.* TO 'gdgoc_user'@'localhost';
FLUSH PRIVILEGES;
```

### Issue 2: Storage Files Tidak Muncul

**Error**: Images/files 404

**Solution**:
```bash
# Recreate storage symlink
cd /var/www/gamipikasi
php artisan storage:link

# Check permissions
sudo chown -R www-data:www-data storage/
sudo chmod -R 775 storage/
```

### Issue 3: Queue Worker Not Running

**Error**: Email tidak terkirim

**Solution**:
```bash
# Check status
sudo systemctl status gdgoc-queue

# Restart
sudo systemctl restart gdgoc-queue

# Check logs
sudo journalctl -u gdgoc-queue -f
```

### Issue 4: 502 Bad Gateway

**Error**: Nginx error

**Solution**:
```bash
# Check PHP-FPM
sudo systemctl status php8.2-fpm
sudo systemctl restart php8.2-fpm

# Check nginx
sudo nginx -t
sudo systemctl restart nginx
```

---

## 📞 Emergency Rollback

Jika ada masalah di server baru dan perlu rollback ke server lama:

1. **Stop traffic ke server baru** (ubah DNS/cloudflare)
2. **Arahkan kembali ke server lama**
3. **Investigate issue di server baru**
4. **Retry migration setelah issue fixed**

**PENTING**: Jangan hapus data di server lama sampai migration fully verified (minimal 1-2 hari running).

---

## ✅ Post-Migration Tasks

Setelah migration sukses dan verified:

### Immediate (Hari 1)
- [ ] Monitor error logs
- [ ] Test all critical features
- [ ] Inform users about new server (jika ada perubahan domain)

### Short-term (Minggu 1)
- [ ] Setup automated backups di server baru
- [ ] Configure monitoring (uptime, performance)
- [ ] Update DNS TTL kembali ke normal

### Long-term (Bulan 1)
- [ ] Decommission server lama (setelah yakin stable)
- [ ] Archive backup dari server lama
- [ ] Document any issues encountered

---

## 📚 Full Documentation

Untuk dokumentasi lengkap, lihat:
- **[DEPLOYMENT.md](DEPLOYMENT.md)** - Complete deployment guide
- **[README.md](README.md)** - Project overview & features

---

**Questions?** Contact: gdsc@unpas.ac.id

**Last Updated**: 2025-10-23
