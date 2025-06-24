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

### 2. Setup Environment
```bash
make env-setup
```
This creates the `.env` file from `.env.example` with proper configuration.

### 3. Complete Project Installation
```bash
make install
```
This comprehensive command will:

- Build Docker containers
- Start all services (app, nginx, postgres, redis)
- Install Composer dependencies
- Install Node.js dependencies
- Run database migrations
- Seed the database with test data
- **Generate APP_KEY and restart containers**
- **Clear Laravel caches for immediate effect**

### 4. Build Frontend Assets
```bash
make npm-build
```
This builds the Vite assets required for the admin panel UI.

### 5. Setup Roles & Permissions (Filament Shield)
```bash
make filament-shield
```
This will:
- Install Filament Shield package
- Setup roles and permissions system
- Create super admin role
- Publish configuration files

### 6. Create Super Admin User
```bash
make filament-super-admin
```

### 7. Access the Application
Access the admin panel at: [http://localhost:8080/admin](http://localhost:8080/admin)

> **Installation Order**: Always run commands in this order:
> 1. `make env-setup` (setup environment file)
> 2. `make install` (complete project setup + automatic key generation)
> 3. `make npm-build` (build frontend assets)
> 4. `make filament-shield` (setup roles & permissions)
> 5. `make filament-super-admin` (create super admin user)

> **Note**: You can customize database credentials and other settings by editing the `.env` file after running `make env-setup` and before running `make install`. The key generation step is required for Laravel encryption services to work properly.

### Alternative: Manual Step-by-Step Installation
If you prefer to run commands individually (note: `make install` already includes key generation):
```bash
make env-setup         # Creates .env from .env.example
make build             # Build Docker containers
make up                # Start services
make composer-install # Install PHP dependencies
make npm-install       # Install Node.js dependencies
make migrate           # Run database migrations
make seed              # Seed database with test data
make key-generate      # Generate APP_KEY (restart container + clear cache)
```

### Container Management
```bash
make down      # Stop all containers
make up        # Start containers again
make clean-all # Complete cleanup (stop, remove project resources + clean cache)
make shell     # Access app container shell
```

> **Warning**: `make clean-all` removes **this project's** Docker containers, images, volumes, vendor/, node_modules/, and application caches. It only affects this specific project, not other Docker projects. After running it, you'll need to run `make install` to rebuild everything.

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

## 🛠️ Available Commands

### 📦 Setup & Installation
- **Environment setup:** `make env-setup` (creates .env from .env.example)
- **Complete installation:** `make install` (build + up + deps + migrate + seed + key generation)
- **Generate encryption key:** `make key-generate` (manual APP_KEY generation if needed)
- **Complete cleanup:** `make clean-all` (stop, remove project resources + clean cache)
- **Build containers:** `make build`
- **Start services:** `make up`
- **Stop services:** `make down`

### 🗄️ Database Operations
- **Run migrations:** `make migrate`
- **Seed the database:** `make seed`
- **Fresh migration + seed:** `make fresh`

### 🎨 Frontend Assets
- **Install dependencies:** `make npm-install` (Node.js packages)
- **Build production assets:** `make npm-build` (required for admin panel)
- **Development server:** `make npm-dev` (Vite hot reload)

### 🎛️ Filament Management
- **Setup roles & permissions:** `make filament-shield` (installs and configures Filament Shield)
- **Create super admin user:** `make filament-super-admin` (interactive)
- 
### ⚡ Queue Management
- **Check queue status:** `make queue-status`
- **Restart queue workers:** `make queue-restart`
- **Monitor queue jobs:** `make queue-monitor`

### 🔧 Development
- **Run tests:** `make test`
- **View logs:** `make logs`
- **Access shell:** `make shell`

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

## 🔧 Troubleshooting

### Common Issues

**"No application encryption key has been specified" Error:**
- Run `make key-generate` to generate and properly configure the APP_KEY
- This command automatically restarts containers and clears caches

**500 Error after installation:**
- Ensure you ran `make key-generate` after `make env-setup`
- Try running `make key-generate` again to reload configuration

**Redis connection issues:**
- Verify `.env` has `REDIS_HOST=redis` (not 127.0.0.1)
- Run `make key-generate` to fix and reload configuration

---

## ℹ️ Notes
- All API responses are in JSON format
- Default database: **PostgreSQL** (see `docker-compose.yml` for credentials)
- For more details, see the documentation in `memory-bank/artifacts/`.
