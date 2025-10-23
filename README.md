# 🎯 GDGoC Gamification System

> Sistem gamifikasi untuk Google Developer Groups on Campus - Universitas Pasundan

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📖 About

GDGoC Gamification adalah sistem manajemen poin dan lencana untuk meningkatkan engagement anggota Google Developer Groups on Campus. Sistem ini memungkinkan:

- ✅ Manajemen tugas dengan sistem reward point
- 🏆 Leaderboard kompetitif
- 🎖️ Sistem lencana (badges) otomatis
- 📧 Email notification untuk task assignment
- 📊 Dashboard analytics untuk tracking performa
- 👥 Role-based access (Lead, Co-Lead, Head, Member)
- 📱 Responsive design untuk mobile & desktop

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js 18+
- NPM

### Installation

1. **Clone repository**
```bash
git clone <repository-url>
cd gamipikasi
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database** (edit `.env`)
```env
DB_DATABASE=gamifikasi_gdgoc
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. **Run migrations & seeders**
```bash
php artisan migrate --seed
```

6. **Build assets**
```bash
npm run build
```

7. **Start development server**
```bash
php artisan serve
```

8. **Start queue worker** (in another terminal)
```bash
php artisan queue:work
```

Visit: `http://localhost:8000`

---

## 📁 Project Structure

```
gamipikasi/
├── app/
│   ├── Http/Controllers/     # Controllers
│   ├── Models/                # Eloquent models
│   ├── Services/              # Business logic
│   └── Mail/                  # Email templates
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── resources/
│   ├── views/                 # Blade templates
│   └── css/                   # Stylesheets
├── routes/
│   └── web.php                # Web routes
└── public/                    # Public assets
```

---

## 👥 User Roles

### 1. **Lead / Co-Lead**
- Full access ke semua fitur
- Buat dan assign task ke Heads
- Manage points dan badges
- View all analytics
- Manage users

### 2. **Head of Department**
- Dashboard departemen
- Buat task untuk members di departemennya
- Monitor team performance
- Complete task dari Lead

### 3. **Member**
- View dan complete tasks
- Earn points dan badges
- View leaderboard
- Upload evidence

### 4. **Secretary / Bendahara**
- Similar dengan Member
- Additional access sesuai kebutuhan

---

## 🎮 Features

### Task Management
- **Trello-style board** dengan drag & drop
- **Priority levels**: Low, Medium, High
- **Status tracking**: Pending, In Progress, Completed
- **Point rewards**: 0-50 points per task
- **Deadline reminders**
- **File attachments**
- **Comments & collaboration**

### Point System
- **5 Categories**:
  - 🤝 Commitment
  - 👥 Collaboration
  - 💡 Initiative
  - ✅ Responsibility
  - ⚠️ Violation (negative points)

- **Point Range**: -20 to +50 per assessment
- **Automatic calculation**
- **Point history tracking**

### Badge System
- **Auto-award** based on achievements
- **8 Badge tiers**:
  - 🌟 Newcomer (0 points)
  - ⭐ Rising Star (50 points)
  - 🔥 Contributor (150 points)
  - 💎 Active Member (300 points)
  - 🏅 Top Contributor (500 points)
  - 👑 Champion (750 points)
  - 🚀 Legend (1000 points)
  - 🌟 Master (1500 points)

### Leaderboard
- **Real-time ranking**
- **Department comparison**
- **Filter by department**
- **Public facing page**

### Notifications
- **In-app notifications**
- **Email notifications** untuk task assignment
- **Deadline reminders**

---

## 🔧 Configuration

### Email Setup (Gmail SMTP)

Edit `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="GDGoC Gamification"
```

**Note:** Gunakan App Password untuk Gmail, bukan password biasa.

### Queue Configuration

Untuk production, gunakan systemd service atau supervisor untuk queue worker. Lihat [DEPLOYMENT.md](DEPLOYMENT.md) untuk details.

---

## 📊 Database Schema

### Main Tables
- `users` - User accounts & roles
- `departments` - Organization departments
- `tasks` - Task management
- `points` - Point history
- `badges` - Badge definitions
- `user_badges` - Earned badges
- `notifications` - User notifications
- `activity_logs` - Activity tracking

---

## 🎨 Tech Stack

### Backend
- **Framework**: Laravel 11.x
- **Database**: MySQL 8.0
- **Queue**: Database driver
- **Cache**: Database/Redis

### Frontend
- **Template Engine**: Blade
- **CSS Framework**: Custom CSS (Google Material Design inspired)
- **Icons**: Lucide Icons
- **JavaScript**: Vanilla JS + Alpine.js (minimal)

### Email
- **SMTP**: Gmail
- **Queue**: Async email sending

---

## 🛠️ Development

### Run tests
```bash
php artisan test
```

### Code style
```bash
composer format
```

### Clear cache
```bash
php artisan optimize:clear
```

### Database refresh
```bash
php artisan migrate:fresh --seed
```

---

## 🚢 Deployment

Untuk deployment ke production server, ikuti panduan lengkap di [DEPLOYMENT.md](DEPLOYMENT.md).

### Quick Deploy Checklist
- [ ] Server requirements met
- [ ] Dependencies installed
- [ ] Environment configured
- [ ] Database migrated
- [ ] Queue worker running
- [ ] SSL configured
- [ ] Backups scheduled

---

## 🐛 Troubleshooting

### Common Issues

**Queue not processing emails?**
```bash
# Make sure queue worker is running
php artisan queue:work

# Or restart the service
sudo systemctl restart gdgoc-queue.service
```

**Permission denied errors?**
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

**Database connection failed?**
```bash
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
```

More troubleshooting: [DEPLOYMENT.md#troubleshooting](DEPLOYMENT.md#troubleshooting)

---

## 📝 Default Credentials

**After seeding, use these credentials:**

**Lead:**
- Email: `narapatikeysa00@gmail.com`
- Password: `gdgoc2024`

**Co-Lead:**
- Email: `nadziffa123@gmail.com`
- Password: `gdgoc2024`

⚠️ **IMPORTANT:** Change passwords immediately in production!

---

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📄 License

This project is licensed under the MIT License.

---

## 👨‍💻 Team

**Developer:**
- Muhammad Sufi Nadziffa Ridwan (Co-Lead)

**Organization:**
- Google Developer Groups on Campus
- Universitas Pasundan

---

## 📞 Support

- **Email**: gdsc@unpas.ac.id
- **Website**: [gdgoc.vinmedia.my.id](https://gdgoc.vinmedia.my.id)

---

## 🎯 Roadmap

- [x] Task management system
- [x] Point & badge system
- [x] Email notifications
- [x] Leaderboard
- [ ] Mobile app (React Native)
- [ ] Real-time notifications (Pusher)
- [ ] Advanced analytics
- [ ] Export reports (PDF/Excel)
- [ ] API documentation (Swagger)
- [ ] Automated testing coverage

---

**Made with ❤️ by GDGoC Universitas Pasundan**

*Last updated: 2025-10-23*
