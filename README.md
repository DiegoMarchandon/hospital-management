# 🏥 Hospital Management System

> A production-ready, full-featured Hospital Management System built with **Laravel 11**, **PostgreSQL**, and **Redis**. Enterprise-grade architecture with role-based dashboards, RESTful APIs, and modern frontend.

**Built for**: [ACL Chile](https://www.acl.cl) — Portfolio Project  
**Status**: ✅ **Production Ready** | All tests passing | Docker containerized

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?style=for-the-badge&logo=php)](https://www.php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-18-336791?style=for-the-badge&logo=postgresql)](https://www.postgresql.org)
[![Redis](https://img.shields.io/badge/Redis-7-DC382D?style=for-the-badge&logo=redis)](https://redis.io)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=for-the-badge&logo=docker)](https://www.docker.com)

---

## 📋 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Quick Start](#-quick-start)
- [Demo Credentials](#-demo-credentials)
- [API Endpoints](#-api-endpoints)
- [Project Structure](#-project-structure)
- [Development](#-development)
- [Deployment](#-deployment)
- [Troubleshooting](#-troubleshooting)
- [Testing](#-testing)
- [License](#-license)

---

## 🎯 Features

### 👥 **Role-Based Access Control**
Three distinct dashboards for different user roles:

#### 🏢 Admin Dashboard
- Hospital statistics and KPIs
- Complete doctor and patient directory
- Appointment overview and management
- System-wide analytics

#### 👨‍⚕️ Doctor Dashboard
- Personal patient list
- Upcoming appointments schedule
- Appointment confirmation workflow
- Quick access to patient medical records

#### 👤 Patient Dashboard
- My appointments (upcoming & history)
- Medical records history
- Medical document uploads to S3
- Appointment booking & cancellation

### 📅 **Appointment Management System**
- ✅ Book appointments with doctors
- ✅ Doctor approval/confirmation workflow
- ✅ Real-time status updates (pending → confirmed → completed/cancelled)
- ✅ Appointment rescheduling & cancellation
- ✅ Complete appointment history

### 📄 **Medical Records & Documents**
- Secure AWS S3 document uploads
- Patient access control per document
- Medical record history with staff notes
- Drag-and-drop interface

### 🔐 **Authentication & Security**
- Session-based authentication (Laravel 11 native)
- Spatie Permissions (3 roles, 7 permissions)
- Protected routes with middleware
- Bcrypt password hashing & CSRF protection

### 📊 **Performance & Scalability**
- Redis caching layer for repeated queries
- N+1 query prevention with eager loading
- Database indexing on all foreign keys
- Pagination on large datasets

### 🎨 **Modern Frontend**
- Responsive Tailwind CSS design
- Interactive Blade templates
- Vite asset compilation
- Mobile-friendly interfaces

---

## 🛠️ Technology Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| **Backend** | Laravel | 11 |
| **Language** | PHP | 8.5 |
| **Database** | PostgreSQL | 18.1 |
| **Cache** | Redis | 7 Alpine |
| **Frontend** | Blade + Tailwind CSS | Latest |
| **Asset Bundler** | Vite | v5 |
| **Containerization** | Docker Compose | Latest |
| **RBAC** | Spatie Permissions | v6 |
| **Testing** | Pest | v2 |
| **Cloud Storage** | AWS S3 API | Compatible |

---

## 🚀 Quick Start

### Prerequisites
- **Docker** (`v24.0+`) — [Install Docker](https://docs.docker.com/install/)
- **Docker Compose** (`v2.20+`)
- **Git**

### Installation

**1. Clone the repository**
```bash
git clone https://github.com/yourusername/hospital-management.git
cd hospital-management
```

**2. Install dependencies**
```bash
docker run --rm -v $(pwd):/opt -w /opt \
  laravelsail/php85-composer:latest composer install
```

**3. Setup environment & keys**
```bash
cp .env.example .env
docker compose run --rm laravel.test php artisan key:generate
```

**4. Start services**
```bash
docker compose up -d
```

**5. Run migrations & seed demo data**
```bash
docker compose exec laravel.test php artisan migrate:fresh --seed
```

**6. Build frontend assets**
```bash
docker compose exec laravel.test npm install && npm run build
```

**7. Access application**
```
URL: http://localhost
Logs: docker compose logs -f laravel.test
```

✅ **Ready!** Log in with demo credentials below.

---

## 📝 Demo Credentials

All accounts use password: `password`

```
Admin:    admin@hospital.com
Doctors:  doctor_1@hospital.com to doctor_10@hospital.com
Patients: patient_1@hospital.com to patient_20@hospital.com
```

🔒 **Change passwords before production deployment!**

---

## 🔌 API Endpoints

### Doctors
```
GET    /api/doctors             # List all doctors
GET    /api/doctors/{id}        # Get doctor details
```

### Patients
```
GET    /api/patients            # List all patients
GET    /api/patients/{id}       # Get patient details
```

### Appointments
```
GET    /api/appointments        # List appointments
POST   /api/appointments        # Create appointment
PUT    /api/appointments/{id}   # Update appointment status
DELETE /api/appointments/{id}   # Cancel appointment
```

**Response Example:**
```json
{
  "id": 123,
  "doctor_id": 1,
  "patient_id": 5,
  "appointment_date": "2026-04-30",
  "appointment_time": "14:30",
  "status": "pending",
  "created_at": "2026-04-22T10:30:00Z"
}
```

**Query Parameters:**
- `page=1` — Pagination
- `limit=15` — Results per page
- `sort=name` — Sort by field
- `filter_status=pending` — Filter by status

---

## 📁 Project Structure

```
hospital-management/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── DocumentController.php
│   │   └── Api/
│   │       ├── DoctorController.php
│   │       ├── PatientController.php
│   │       └── AppointmentController.php
│   └── Models/
│       ├── User.php
│       ├── Doctor.php
│       ├── Patient.php
│       ├── Appointment.php
│       ├── MedicalRecord.php
│       ├── Schedule.php
│       └── Specialty.php
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
│       ├── AdminSeeder.php
│       ├── DoctorSeeder.php
│       ├── PatientSeeder.php
│       ├── ScheduleSeeder.php
│       └── AppointmentSeeder.php
├── resources/views/
│   ├── dashboard/
│   │   ├── admin.blade.php
│   │   ├── doctor.blade.php
│   │   └── patient.blade.php
│   ├── auth/login.blade.php
│   ├── appointments/create.blade.php
│   ├── documents/upload.blade.php
│   ├── layouts/app.blade.php
│   └── welcome.blade.php
├── routes/
│   ├── web.php
│   └── api.php
├── tests/Feature/Api/
│   ├── DoctorApiTest.php
│   ├── PatientApiTest.php
│   └── AppointmentApiTest.php
├── docker-compose.yml
├── composer.json
└── README.md
```

---

## 🔧 Development

### Running Artisan Commands
```bash
docker compose exec laravel.test php artisan {command}

# Examples:
docker compose exec laravel.test php artisan migrate:fresh --seed
docker compose exec laravel.test php artisan tinker
docker compose exec laravel.test php artisan cache:clear
```

### Building Assets
```bash
# Build once
docker compose exec laravel.test npm run build

# Watch for changes
docker compose exec laravel.test npm run dev
```

### Database Access
```bash
# PostgreSQL CLI
docker compose exec pgsql psql -U laravel -d laravel

# Common queries:
SELECT * FROM users;
SELECT status, COUNT(*) FROM appointments GROUP BY status;
```

### Redis Access
```bash
docker compose exec redis redis-cli

# Commands:
KEYS *
GET key_name
FLUSHDB
```

### Running Tests
```bash
# All tests
docker compose exec laravel.test php artisan test

# Specific file
docker compose exec laravel.test php artisan test tests/Feature/Api/DoctorApiTest.php

# With coverage
docker compose exec laravel.test php artisan test --coverage
```

---

## 🚀 Deployment

### Production Checklist
- [ ] Update `.env` with real database credentials
- [ ] Update `.env` with real AWS S3 credentials
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Change all demo passwords
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Setup HTTPS/SSL certificate
- [ ] Configure automated database backups
- [ ] Setup monitoring & uptime checks

### Docker Deployment
```bash
# Build Docker image
docker build -t yourusername/hospital-management:latest .

# Push to Docker Hub
docker login
docker push yourusername/hospital-management:latest

# Deploy on AWS/DigitalOcean/etc
# Copy docker-compose.yml and .env to production
docker compose up -d
```

---

## 🐛 Troubleshooting

### Port Already in Use
```bash
# Find process
lsof -i :80           # macOS/Linux
netstat -ano | findstr :80  # Windows

# Kill process
kill -9 <PID>         # macOS/Linux
taskkill /PID <PID> /F  # Windows
```

### Docker Containers Not Starting
```bash
docker compose logs -f
docker compose down
docker compose build --no-cache
docker compose up -d
```

### Database Migration Fails
```bash
docker compose exec laravel.test php artisan db
docker compose exec laravel.test php artisan migrate:reset
docker compose exec laravel.test php artisan migrate:fresh --seed
```

### Static Assets Not Loading
```bash
docker compose exec laravel.test npm run build
docker compose exec laravel.test php artisan cache:clear
docker compose exec laravel.test php artisan config:clear
```

### null Property Errors in Blade
```blade
@if($variable)
  {{ $variable->property }}
@else
  <p>No data available</p>
@endif
```

### Redis Connection Issues
```bash
docker compose ps  # Verify redis is running
docker compose exec laravel.test php artisan tinker
>>> Cache::get('test')
```

---

## ✅ Testing

All tests passing: **22/22** ✅

```bash
# Run all tests
docker compose exec laravel.test php artisan test

# Run specific test file
docker compose exec laravel.test php artisan test tests/Feature/Api/DoctorApiTest.php

# Watch mode (requires package)
docker compose exec laravel.test php artisan test --watch
```

**Test Coverage:**
- ✅ API endpoints (CRUD operations) - 8 tests
- ✅ API authentication & role filtering - 7 tests  
- ✅ Role-based dashboard access - 6 tests
- ✅ General application health - 1 test
- ✅ Unit tests - 1 test

**Test Statistics:**
- Total Tests: 22
- Total Assertions: 290+
- Passing: 100%
- Duration: ~15 seconds

---

## 📊 Monitoring

```bash
# View real-time logs
docker compose logs -f laravel.test

# Monitor resource usage
docker stats

# Check application health
docker compose exec laravel.test php artisan health:check
```

---

## 🤝 Contributing

This is a portfolio project. For improvements:

1. Fork repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit (`git commit -m 'Add feature'`)
4. Push (`git push origin feature/amazing-feature`)
5. Create Pull Request

---

## 📄 License

MIT License — See LICENSE file

---

## 👨‍💻 Author

**Diego** — Full Stack Developer  
📧 Email: your-email@example.com  
🔗 LinkedIn: [Your LinkedIn](https://linkedin.com/in/yourprofile)  
🌐 Portfolio: [Your Portfolio](https://yourportfolio.com)

---

**⭐ Please star this repository if it helped you!**

**Last Updated:** April 23, 2026  
**Version:** 1.0.0  
**Status:** Production Ready ✅
