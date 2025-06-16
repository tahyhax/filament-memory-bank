# Filament Memory Bank - Makefile
.PHONY: help install up down build fresh shell logs

# Default target
help:
	@echo "🚀 Filament Memory Bank - Available Commands:"
	@echo ""
	@echo "📦 Setup & Installation:"
	@echo "  make install     - Full project setup (build + up + migrate + seed)"
	@echo "  make build       - Build Docker containers"
	@echo "  make up          - Start Docker containers"
	@echo "  make down        - Stop Docker containers"
	@echo ""
	@echo "🗄️ Database:"
	@echo "  make migrate     - Run database migrations"
	@echo "  make seed        - Run database seeders"
	@echo "  make fresh       - Fresh migration with seeding"
	@echo ""
	@echo "⚡ Queue Management:"
	@echo "  make queue-status    - Check queue worker status"
	@echo "  make queue-restart   - Restart queue workers"
	@echo "  make queue-monitor   - Monitor queue jobs"
	@echo ""
	@echo "🔧 Development:"
	@echo "  make shell       - Access app container shell"
	@echo "  make logs        - View container logs"
	@echo "  make test        - Run tests"
	@echo ""
	@echo "🎛️ Filament:"
	@echo "  make filament-user   - Create Filament admin user"
	@echo "  make filament-install - Install Filament panel"

# Installation & Setup
install: build up composer-install migrate seed filament-install
	@echo "🎉 Installation complete! Access: http://localhost:8080"

build:
	@echo "🔨 Building Docker containers..."
	docker-compose -f .docker/docker-compose.yml build

up:
	@echo "🚀 Starting Docker containers..."
	docker-compose -f .docker/docker-compose.yml up -d

down:
	@echo "🛑 Stopping Docker containers..."
	docker-compose -f .docker/docker-compose.yml down

# Composer & Laravel
composer-install:
	@echo "📦 Installing Composer dependencies..."
	docker-compose -f .docker/docker-compose.yml exec app composer install

migrate:
	@echo "🗄️ Running migrations..."
	docker-compose -f .docker/docker-compose.yml exec app php artisan migrate

seed:
	@echo "🌱 Seeding database..."
	docker-compose -f .docker/docker-compose.yml exec app php artisan db:seed

fresh:
	@echo "🔄 Fresh migration with seeding..."
	docker-compose -f .docker/docker-compose.yml exec app php artisan migrate:fresh --seed

# Queue Management
queue-status:
	@echo "⚡ Checking queue worker status..."
	docker-compose -f .docker/docker-compose.yml exec app supervisorctl status

queue-restart:
	@echo "🔄 Restarting queue workers..."
	docker-compose -f .docker/docker-compose.yml exec app supervisorctl restart laravel-queue:*

queue-monitor:
	@echo "👀 Monitoring queue jobs..."
	docker-compose -f .docker/docker-compose.yml exec app php artisan queue:monitor

# Development
shell:
	@echo "🐚 Accessing app container..."
	docker-compose -f .docker/docker-compose.yml exec app bash

logs:
	@echo "📋 Viewing container logs..."
	docker-compose -f .docker/docker-compose.yml logs -f

test:
	@echo "🧪 Running tests..."
	docker-compose -f .docker/docker-compose.yml exec app php artisan test

# Filament
filament-install:
	@echo "🎛️ Installing Filament panel..."
	docker-compose -f .docker/docker-compose.yml exec app php artisan filament:install --panels

filament-user:
	@echo "👤 Creating Filament admin user..."
	docker-compose -f .docker/docker-compose.yml exec app php artisan make:filament-user

# Key generation
key-generate:
	@echo "🔑 Generating application key..."
	docker-compose -f .docker/docker-compose.yml exec app php artisan key:generate 