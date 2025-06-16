# Filament Memory Bank

A modern Laravel + Filament admin panel for managing educational courses, credits, and specialties. Production-ready, Dockerized, and built with PostgreSQL, Redis, and strict code standards.

---

## 🚀 Quick Start

### Requirements
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/)
- [GNU Make](https://www.gnu.org/software/make/)
- (Optional) [git](https://git-scm.com/)

### 1. Clone the Repository
```bash
git clone <your-repo-url>
cd filament-memory-bank
```

### 2. Start the Project (All-in-One)
```bash
make install
```
This will:
- Build Docker containers
- Start all services (app, nginx, postgres, redis)
- Install Composer dependencies
- Run database migrations and seeders
- Install Filament admin panel

Access the app at: [http://localhost:8080](http://localhost:8080)

### 3. Stopping and Restarting
```bash
make down   # Stop all containers
make up     # Start containers again
```

### 4. Accessing the App Container Shell
```bash
make shell
```

---

## 📋 System Access
- **Admin Panel**: http://localhost:8080/admin
- **Login**: admin@example.com
- **Password**: password
- **Role**: super_admin (full access)

---

## 🏗️ Technology Stack
- **Framework**: Laravel 12 (strict types, PSR-12)
- **Admin Panel**: Filament 3.x (professional UI/UX)
- **Database**: PostgreSQL 15
- **Cache/Queue**: Redis
- **Containerization**: Docker (PHP 8.2+, Nginx, Supervisor)

---

## 🎨 Active Features
- **CourseResource**: Sectioned forms, advanced filtering, export (Excel/CSV)
- **CreditResource**: Date validation, status badges, requirements, export
- **SpecialtyResource**: Skills (TagsInput), experience, certification, export
- **Relationship Management**: Polymorphic (prerequisite, corequisite, recommended, awarded), pivot data
- **Authentication**: Laravel Sanctum, Filament Shield, 40+ permissions
- **Notifications**: Auto-generated, PostgreSQL JSON, interactive panel
- **Export System**: Native Filament export, background jobs, progress tracking
- **UI/UX**: Grouped navigation, dynamic badges, global search (Cmd+K/Ctrl+K), color scheme (Amber/Gray Slate)
- **Performance**: Eager loading, Redis caching, chunked exports
- **Security**: Role-based access, multi-layer validation, CSRF, user isolation

---

## 🛠️ Common Development Tasks
- **Run migrations:** `make migrate`
- **Seed the database:** `make seed`
- **Fresh migration + seed:** `make fresh`
- **Run tests:** `make test`
- **View logs:** `make logs`
- **Queue management:** `make queue-status`, `make queue-restart`, `make queue-monitor`
- **Filament admin user:** `make filament-user`

---

## 📂 Project Structure
- `app/` — Laravel application code
- `memory-bank/artifacts/` — Project status, cost, and implementation documentation
- `.docker/` — Docker configuration files
- `Makefile` — Project automation commands
- `tests/` — Feature and unit tests

---

## 📝 Code Standards
- **Strict Typing**: `declare(strict_types=1)` everywhere
- **PSR-12**: Coding standards enforced
- **PHPStan**: Array shapes and static analysis
- **Comprehensive documentation**

---

## ℹ️ Notes
- All API responses are in JSON format
- Default database: **PostgreSQL** (see `docker-compose.yml` for credentials)
- For more details, see the documentation in `memory-bank/artifacts/`.
