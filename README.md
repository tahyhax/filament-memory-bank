# Filament Memory Bank

A modern Laravel + Filament admin panel for managing memory bank data, with Dockerized development, PostgreSQL, Redis, and robust project automation.

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

## 🛠️ Common Development Tasks

- **Run migrations:**
  ```bash
  make migrate
  ```
- **Seed the database:**
  ```bash
  make seed
  ```
- **Fresh migration + seed:**
  ```bash
  make fresh
  ```
- **Run tests:**
  ```bash
  make test
  ```
- **View logs:**
  ```bash
  make logs
  ```
- **Queue management:**
  ```bash
  make queue-status    # Check queue worker status
  make queue-restart   # Restart queue workers
  make queue-monitor   # Monitor queue jobs
  ```
- **Filament admin user:**
  ```bash
  make filament-user
  ```

---

## 📂 Project Structure
- `app/` — Laravel application code
- `memory-bank/artifacts/` — Project status, cost, and implementation documentation
- `.docker/` — Docker configuration files
- `Makefile` — Project automation commands
- `tests/` — Feature and unit tests

---

## 📝 License
This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).

---

### ℹ️ Notes
- Default database: **PostgreSQL** (see `docker-compose.yml` for credentials)
- Caching/queue: **Redis**
- Admin panel: **Filament** (Laravel package)
- All API responses are in JSON format
- Coding standards: PHP 8+, strict types, PSR-12, PHPStan array shapes

For more details, see the documentation in `memory-bank/artifacts/`.
