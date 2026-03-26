# 🗓️ Schedora — Smart Appointment Management System

> Professional appointment scheduling platform for businesses & clients.

[![Laravel](https://img.shields.io/badge/Laravel-10.x-brightred)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1-blueviolet)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-brightgreen)](LICENSE)

---

## 📌 About The Project

Schedora is a complete full-stack appointment management system built with **Laravel 10** and **Tailwind CSS**. 

**Three user roles:**
- **Admin**: Full system management
- **Provider**: Schedule appointments, manage availability  
- **Client**: Book & pay for services, leave reviews

Production-ready with Stripe payments, role-based auth, email notifications, and dark theme UI.

---

## ✨ Features

### 👑 Admin Dashboard
- User, provider, service management
- Appointments & payments overview
- Review moderation (approve/feature)
- Search & pagination on all tables
- Role & permission control

### 🔵 Provider Panel
- Dashboard with upcoming appointments
- Accept/reject booking requests
- Set working hours & holidays
- Mark payments as completed
- View client feedback & ratings

### 🟢 Client Portal
- Browse providers & services
- Book appointments (date/time picker)
- Secure Stripe checkout
- Status timeline (Pending → Confirmed → Paid → Completed)
- Post-service reviews

### 🌐 Additional Features
- Multi-role authentication (Spatie Laravel Permission)
- Email notifications (4 types)
- Responsive dark theme (glassmorphism)
- Stripe payments + webhooks
- Search filters on admin panels
- Soft deletes & full CRUD

---

## 🛠 Tech Stack

| Category | Technology |
|----------|------------|
| **Framework** | Laravel 10.x |
| **Frontend** | Blade Templates + Tailwind CSS (CDN) + Alpine.js |
| **Database** | MySQL + Migrations + Seeders |
| **Auth** | Laravel Sanctum + Spatie Roles/Permissions |
| **Payments** | Stripe (Sessions + Payment Intents) |
| **Emails** | Laravel Mailables + SMTP |
| **Packages** | `spatie/laravel-permission`, `stripe/stripe-php`, `laravel/breeze` |

---

## 🚀 Quick Start

### Prerequisites
```
PHP 8.1+
Composer
MySQL 8.0+
XAMPP (recommended)
```

### 🚀 Production Installation (2 minutes - One Command!)

```bash
# 1. Fresh clone/setup
git clone <repo> schedora && cd schedora
composer install --no-dev --optimize-autoloader

# 2. ONE COMMAND PRODUCTION FIX
chmod +x fix-prod-ready.sh
./fix-prod-ready.sh

# 3. Configure .env (auto-generated production template)
cp .env.example .env
php artisan key:generate

# 4. Serve (production optimized)
php artisan serve --host=0.0.0.0

# ✅ Check: Open verification-report.html
```

**Traditional Setup (Legacy):**
```bash
php artisan migrate:fresh --seed
php artisan storage:link
php artisan optimize
```

**Login Credentials:**
```
Admin: admin@schedora.com / password
Provider: provider@schedora.com / password
Client: client@schedora.com / password
```


**URL:** `http://127.0.0.1:8000`

### Default Login Credentials
```
Admin: admin@schedora.com / password
Provider: provider@schedora.com / password  
Client: client@schedora.com / password
```

---

## 💳 Stripe Testing

**Test Cards:**
```
4242 4242 4242 4242     → Success (any future date/any CVC)
4000 0000 0000 0002     → Declined
4000 0025 0000 3155     → 3D Secure required
```

---

## 🔄 Appointment Workflow

```
1. Client → Books appointment [Pending]
2. Provider → Accepts/Rejects [Confirmed/Cancelled]
3. Client → Pays via Stripe [Paid]
4. Provider → Marks Complete [Completed]
5. Client → Writes Review [Reviewed]
```

**Status Flow:** `pending → confirmed → paid → completed`

---

## 📁 Database Schema

**Core Tables (19 migrations):**

```
users (SoftDeletes, Roles)
providers (user_id → User)
services (name, duration, price)
provider_service (pivot)
appointments (client_id, provider_id, service_id, status)
payments (appointment_id, stripe_session_id)
reviews (user_id, provider_id, rating 1-5, is_approved)
provider_working_hours
provider_holidays
permission tables (Spatie)
```

**Relationships:**
```
User → hasOne Provider
Provider → belongsToMany Service
Appointment → client, provider, service, payment, review
Review → user, provider, appointment
Payment → appointment, user
```

---

## 📧 Email Notifications

**4 Automated Emails:**
1. **NewAppointmentMail** → Provider (new booking)
2. **AppointmentConfirmedMail** → Client (accepted)
3. **AppointmentCancelledMail** → Client (rejected)
4. **AppointmentCompletedMail** → Client (ready for review)

Dark glassmorphism templates in `resources/views/emails/`

---

## 🎨 UI/UX

- **Dark premium theme** (Tailwind CDN + custom CSS)
- **Glassmorphism cards** with backdrop-blur
- **Blue-cyan gradient accents**
- **Mobile hamburger menu** (Alpine.js)
- **Status timelines & badges**
- **Custom 404/500/403 pages**
- **Admin search bars** (live filtering)

---

## 📊 Admin Dashboard Stats

- Total payments/revenue
- Appointment statuses
- Review statistics (platform/provider)
- Average ratings

---

## 👥 Roles & Permissions

**Spatie Laravel Permission powered:**

| Role | Permissions |
|------|-------------|
| **admin** | Full CRUD all entities |
| **provider** | Manage own appointments/schedule |
| **client** | Book/pay/review |

---

## 🛡️ Security

- Role middleware (`role:admin|provider`)
- Stripe secure payments
- CSRF protection
- Rate limiting on auth
- Soft deletes
- Email verification

---

## 📱 Responsive Design

✅ **Mobile-first** Tailwind
✅ **Hamburger menu** for mobile
✅ **Touch-friendly** buttons
✅ **Table scrolling** on small screens

---

## 🔍 Search & Filters

**Live search on all admin tables** (users/providers/services/payments/reviews)
**Reviews filter** (type/rating/status)

---

## 📈 Contribution

1. Fork the project
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit (`git commit -m 'Add some AmazingFeature'`)
4. Push (`git push origin feature/AmazingFeature`)
5. Open PR

---

## 👩‍💻 Developer

**Maryam Ghanem**  
[GitHub](https://github.com/Maryamghanem64) | maryam.ghanem213@gmail.com

---

## 📄 License

MIT License - see `LICENSE` file.

---

**Built with ❤️ using Laravel 10 & Tailwind CSS** 🚀

⭐ **Star this repo if you found it useful!**

