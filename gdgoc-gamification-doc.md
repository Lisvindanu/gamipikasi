# 🌟 GDGoC Core Team Gamification System

## 📘 Overview

Sistem gamifikasi untuk menilai performa anggota Core Team GDGoC (Google Developer Groups on Campus) selama satu periode (1 tahun). Penilaian dilakukan oleh tim Human Resource (HR) secara berkala untuk menentukan reward dan evaluasi kinerja.

---

## 🏗️ Organizational Structure

| Level | Role | Description |
|-------|------|-------------|
| 👑 **Lead** | Main Leader | Oversees all activities and makes strategic decisions |
| 🤝 **Co-Lead** | Assistant Leader | Assists with coordination across departments |
| 🗂️ **Secretary** | Administrative | Records, archives documents, and supports internal communication |
| 🧩 **Head of Department** | Department Coordinator | Responsible for department activities and team management |
| 👥 **Member** | Team Member | Executes activities and contributes to programs |

---

## 🧮 Assessment Categories & Points

| Category | Description | Point Range |
|----------|-------------|-------------|
| **Commitment** | Consistency and attendance in activities | +1 to +10 |
| **Collaboration** | Ability to work effectively in teams | +1 to +10 |
| **Initiative** | Proactively provides ideas, solutions, and extra contributions | +1 to +15 |
| **Responsibility** | Completes tasks on time and meets expectations | +1 to +10 |
| **Violation** | Absence, inactivity, or rule violations | -1 to -10 |

> ⚠️ **No maximum point limit**  
> The more active and consistent, the higher the score earned.

---

## 🗓️ Assessment Period

- **Duration:** 1 year (1 period)
- **Evaluations conducted:**
  - Monthly → Review & input scores
  - End of period → Total point recap and reward distribution

---

## 🧠 System Workflow

1. HR inputs scores for each member via web portal at the end of each month
2. Lead and Co-Lead monitor real-time assessment dashboard
3. Members view their personal progress and point graphs
4. Rewards are given to members with the highest total points at the end of the period

---

## 🧱 Database Schema

### 📄 Table: `users`

| Column | Data Type | Description |
|--------|-----------|-------------|
| `id` | BIGINT | Primary Key |
| `name` | VARCHAR(100) | Full name of member |
| `email` | VARCHAR(100) | Official GDGoC email |
| `role` | ENUM('lead', 'co-lead', 'secretary', 'head', 'member') | Position |
| `department_id` | BIGINT | Foreign key to departments |
| `total_points` | INT | Current accumulated points |
| `created_at` | TIMESTAMP | Record creation date |
| `updated_at` | TIMESTAMP | Last update date |

**Indexes:**
- Primary: `id`
- Foreign: `department_id` → `departments.id`
- Index: `role`, `email` (unique)

---

### 🏢 Table: `departments`

| Column | Data Type | Description |
|--------|-----------|-------------|
| `id` | BIGINT | Primary Key |
| `name` | VARCHAR(100) | Department name |
| `description` | TEXT | Brief description |
| `head_id` | BIGINT | Foreign key to users (Head of Department) |
| `created_at` | TIMESTAMP | Record creation date |
| `updated_at` | TIMESTAMP | Last update date |

**Indexes:**
- Primary: `id`
- Foreign: `head_id` → `users.id`
- Unique: `name`

---

### 🏆 Table: `points`

| Column | Data Type | Description |
|--------|-----------|-------------|
| `id` | BIGINT | Primary Key |
| `user_id` | BIGINT | Foreign key to users |
| `category` | ENUM('commitment', 'collaboration', 'initiative', 'responsibility', 'violation') | Assessment type |
| `value` | INT | Point value (positive or negative) |
| `note` | TEXT | Additional notes |
| `given_by` | BIGINT | ID of HR/Lead who assigned the points |
| `created_at` | TIMESTAMP | Assessment date |

**Indexes:**
- Primary: `id`
- Foreign: `user_id` → `users.id`, `given_by` → `users.id`
- Index: `category`, `created_at`

---

### 💬 Table: `activities` *(optional)*

| Column | Data Type | Description |
|--------|-----------|-------------|
| `id` | BIGINT | Primary Key |
| `title` | VARCHAR(100) | Activity name |
| `description` | TEXT | Activity description |
| `date` | DATE | Execution date |
| `department_id` | BIGINT | Related department |
| `created_at` | TIMESTAMP | Record creation date |
| `updated_at` | TIMESTAMP | Last update date |

**Indexes:**
- Primary: `id`
- Foreign: `department_id` → `departments.id`
- Index: `date`

---

### 🎖️ Table: `badges` *(optional)*

| Column | Data Type | Description |
|--------|-----------|-------------|
| `id` | BIGINT | Primary Key |
| `name` | VARCHAR(100) | Badge name |
| `description` | TEXT | Badge criteria |
| `icon` | VARCHAR(255) | Badge icon URL |
| `created_at` | TIMESTAMP | Record creation date |

---

### 🏅 Table: `user_badges` *(optional)*

| Column | Data Type | Description |
|--------|-----------|-------------|
| `id` | BIGINT | Primary Key |
| `user_id` | BIGINT | Foreign key to users |
| `badge_id` | BIGINT | Foreign key to badges |
| `earned_at` | TIMESTAMP | Date badge was earned |

**Indexes:**
- Primary: `id`
- Foreign: `user_id` → `users.id`, `badge_id` → `badges.id`
- Unique: `user_id, badge_id`

---

## 🧭 UI Design (Wireframe)

### 🧍 HR Dashboard

**Features:**
- ✅ Input member scores
- 📊 Member list table per department
- 📝 Quick assessment form (category dropdown, value input, notes)
- 🔄 Assessment history
- 📈 Monthly statistics overview

**Layout:**
```
┌─────────────────────────────────────────┐
│ GDGoC HR Dashboard                      │
├─────────────────────────────────────────┤
│ [Filter by Department ▼] [Month ▼]     │
├─────────────────────────────────────────┤
│ Name          | Points | Last Updated   │
│ John Doe      | 85     | [Add Score +]  │
│ Jane Smith    | 92     | [Add Score +]  │
└─────────────────────────────────────────┘
```

---

### 👑 Lead / Co-Lead Dashboard

**Features:**
- 📈 Point graph per department
- 🧩 Top individual rankings
- 📅 Monthly & annual period recap
- 🏆 Badge distribution overview
- 📊 Comparative analytics

**Layout:**
```
┌─────────────────────────────────────────┐
│ GDGoC Leadership Dashboard              │
├─────────────────────────────────────────┤
│ [Total Members: 45] [Avg Points: 75]    │
├─────────────────────────────────────────┤
│ 📊 Department Performance               │
│ [Bar Chart: Points by Department]       │
├─────────────────────────────────────────┤
│ 🏆 Top 5 Members                        │
│ 1. John Doe - 120 pts                   │
│ 2. Jane Smith - 115 pts                 │
└─────────────────────────────────────────┘
```

---

### 👤 Member Dashboard

**Features:**
- ⭐ View personal total points
- 📅 Assessment history (commitment, collaboration, etc.)
- 📈 Personal progress graph
- 🏅 Earned badges
- 🎯 Next milestone tracker

**Layout:**
```
┌─────────────────────────────────────────┐
│ Welcome, John Doe                       │
├─────────────────────────────────────────┤
│ 🎯 Total Points: 85                     │
│ 📊 Rank: #12 of 45                      │
├─────────────────────────────────────────┤
│ 📈 Point Breakdown                      │
│ Commitment:      ████████░░ 25 pts      │
│ Collaboration:   ███████░░░ 22 pts      │
│ Initiative:      ██████████ 30 pts      │
│ Responsibility:  ██████░░░░ 18 pts      │
│ Violations:      ███░░░░░░░ -10 pts     │
├─────────────────────────────────────────┤
│ 🏅 Your Badges                          │
│ [Badge Icon] [Badge Icon] [Badge Icon]  │
└─────────────────────────────────────────┘
```

---

## 🎁 Reward System (Gamification)

### 🏆 End of Period Rewards

| Reward | Criteria |
|--------|----------|
| 🥇 **Top Member of the Year** | Highest total points during 1 period |
| 🏅 **Department Star** | Best member in each department |
| 💡 **Innovation Award** | Highest initiative score |
| 🤝 **Collaboration Champion** | Highest collaboration score |
| ⏱️ **Consistency Badge** | No violations throughout the period |
| 🚀 **Rising Star** | Most improved member (highest point growth) |

### 🎖️ Milestone Badges (Auto-awarded)

| Badge | Unlock Criteria |
|-------|-----------------|
| 🌱 **Newcomer** | Earned first 10 points |
| ⚡ **Active Contributor** | Reached 50 total points |
| 🌟 **Superstar** | Reached 100 total points |
| 👥 **Team Player** | Earned 30+ collaboration points |
| 💪 **Responsible** | Earned 25+ responsibility points |
| 🧠 **Innovator** | Earned 40+ initiative points |
| ✨ **Perfect Record** | Zero violations for 3 consecutive months |

---

## 🔁 HR Team Workflow

### Monthly Process

1. **Week 1:** Collect reports from each Head of Department
2. **Week 2:** Review member activities and performance
3. **Week 3:** Input assessments into the system via HR panel
4. **Week 4:** System auto-calculates total points
5. **End of Month:** Lead and Co-Lead review evaluation results

### System Features for HR

- 📋 Bulk assessment input
- 🔍 Quick member search and filter
- 📊 Assessment templates for common scenarios
- 📝 Notes and documentation for each assessment
- 🔔 Reminder notifications for pending evaluations

---

## 🔐 Access Rights & Permissions

| Role | Permissions |
|------|-------------|
| **Lead / Co-Lead** | • View all data<br>• Modify user roles<br>• Add/edit departments<br>• Export reports<br>• Manage badges |
| **HR** | • Add/edit assessments<br>• View all member data<br>• Generate reports<br>• Manage point history |
| **Head of Department** | • View department member data<br>• Submit feedback for their team<br>• View department analytics |
| **Secretary** | • View and recap administrative reports<br>• Archive documents<br>• Generate meeting minutes |
| **Member** | • View personal points and history<br>• View earned badges<br>• View leaderboard (anonymized option) |

---

## ⚙️ Technology Stack

### Backend
- **Framework:** Laravel 11
- **Database:** MySQL 8.0+
- **Authentication:** Laravel Breeze / Jetstream
- **API:** RESTful API for potential mobile app integration

### Frontend
- **Template Engine:** Blade
- **CSS Framework:** TailwindCSS 3.x
- **JavaScript:** Alpine.js (for interactivity)
- **Charts:** Chart.js / ApexCharts for data visualization

### Optional Enhancements
- **Notifications:** Laravel Notifications (Email, Database, Slack)
- **Queue System:** Redis for background jobs
- **File Storage:** Laravel Storage (local/S3)
- **Cache:** Redis for performance optimization

---

## 🚀 Development Phases

### Phase 1: MVP (Minimum Viable Product)
- ✅ User authentication and role management
- ✅ Basic CRUD for users, departments, and points
- ✅ Simple dashboard for each role
- ✅ Point calculation system

### Phase 2: Enhanced Features
- ✅ Advanced analytics and graphs
- ✅ Badge system implementation
- ✅ Notification system
- ✅ Export to PDF/Excel

### Phase 3: Advanced Features
- ✅ Multi-period support (archive previous years)
- ✅ Mobile responsive design
- ✅ Integration with Telegram/Discord
- ✅ Automated milestone badges
- ✅ Advanced filtering and search

---

## 📝 Business Rules

### Point Assignment Rules
1. Each assessment must include a category, value, and note
2. Points can be negative (violations) or positive (achievements)
3. All point changes are logged with timestamp and assessor ID
4. Point values must stay within defined ranges per category
5. Total points are automatically recalculated on each update

### Badge Award Rules
1. Badges are automatically awarded when criteria are met
2. Once earned, badges cannot be revoked
3. Badge criteria are evaluated on every point update
4. Members receive notifications when earning new badges

### Period Management Rules
1. One active period at a time
2. Periods last exactly 12 months
3. Points reset at the start of a new period
4. Previous period data is archived but viewable
5. Awards are finalized when a period closes

---

## 🔒 Security Considerations

- **Authentication:** Multi-factor authentication for admin roles
- **Authorization:** Role-based access control (RBAC)
- **Data Validation:** Server-side validation for all inputs
- **SQL Injection Prevention:** Laravel Eloquent ORM
- **XSS Protection:** Blade template escaping
- **CSRF Protection:** Laravel CSRF tokens
- **Audit Trail:** Log all point modifications with user ID and timestamp

---

## 📊 Reporting Features

### Available Reports

1. **Member Performance Report**
   - Individual point breakdown
   - Assessment history
   - Badge achievements
   - Period-over-period comparison

2. **Department Analytics**
   - Total points per department
   - Average points per member
   - Top performers
   - Activity trends

3. **Period Summary Report**
   - Total assessments conducted
   - Point distribution graph
   - Award recipients
   - Participation metrics

4. **HR Activity Report**
   - Number of assessments per HR staff
   - Assessment distribution by category
   - Monthly assessment trends

---

## 🎯 Success Metrics

### Key Performance Indicators (KPIs)

- **Engagement Rate:** % of members with >50 points
- **Assessment Frequency:** Average assessments per member per month
- **Violation Rate:** % of negative point assignments
- **Badge Achievement Rate:** Average badges earned per member
- **Department Balance:** Standard deviation of points across departments

---

## 🛠️ Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js & NPM

### Installation Steps
```bash
# Clone repository
git clone https://github.com/gdgoc/gamification-system.git

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Build assets
npm run build

# Start server
php artisan serve
```

---

## 🧪 Testing

- **Unit Tests:** For business logic and calculations
- **Feature Tests:** For API endpoints and workflows
- **Browser Tests:** For UI interactions (Laravel Dusk)

```bash
# Run tests
php artisan test

# Run with coverage
php artisan test --coverage
```

---

## 📚 API Documentation

### Authentication Endpoints
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `GET /api/user` - Get authenticated user

### Point Management
- `GET /api/points` - Get all points (filtered)
- `POST /api/points` - Create new point entry
- `PUT /api/points/{id}` - Update point entry
- `DELETE /api/points/{id}` - Delete point entry

### User Management
- `GET /api/users` - Get all users
- `GET /api/users/{id}` - Get specific user
- `PUT /api/users/{id}` - Update user
- `GET /api/users/{id}/points` - Get user's point history

### Reports
- `GET /api/reports/leaderboard` - Get current leaderboard
- `GET /api/reports/department/{id}` - Get department report
- `GET /api/reports/period` - Get period summary

---

## 🔄 Future Enhancements

### Potential Features
- 🌐 **Multi-language Support** (Bahasa Indonesia & English)
- 📱 **Mobile App** (React Native / Flutter)
- 🤖 **Chatbot Integration** for quick point queries
- 🎮 **Gamification Elements** (levels, streaks, challenges)
- 📧 **Email Notifications** for point updates
- 🔗 **Social Sharing** for badge achievements
- 📊 **Predictive Analytics** for performance forecasting
- 🎨 **Theme Customization** per department

---

## 🤝 Contributing

Contributions are welcome! Please follow these guidelines:
1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

---

## 📄 License

This project is proprietary software for GDGoC internal use.

---

## 📞 Support

For technical support or questions:
- **Email:** tech@gdgoc.id
- **Discord:** GDGoC Tech Support Channel
- **Documentation:** https://docs.gdgoc.id/gamification

---

_© 2025 GDGoC Gamification System — Designed for growth & collaboration_ 🚀