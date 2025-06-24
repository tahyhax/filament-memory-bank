# Filament Memory Bank - Makefile
.PHONY: help install clean-all env-setup up down build fresh shell logs filament-shield npm-install npm-build npm-dev rebuild

# Default target
help:
	@echo "🚀 Filament Memory Bank - Available Commands:"
	@echo ""
	@echo "📦 Setup & Installation:"
	@echo "  make install     - Full project setup (env + build + up + deps + migrate + seed)"
	@echo "  make rebuild    - Complete rebuild (clean all + full install)"
	@echo "  make clean-all   - Complete cleanup (stop, remove project resources + clean cache)"
	@echo "  make env-setup   - Create .env file from .env.example"
	@echo "  make build       - Build Docker containers"
	@echo "  make up          - Start Docker containers"
	@echo "  make down        - Stop Docker containers"
	@echo ""
	@echo "🗄️ Database:"
	@echo "  make migrate     - Run database migrations"
	@echo "  make seed        - Run database seeders"
	@echo "  make fresh       - Fresh migration with seeding"
	@echo ""
	@echo "🎨 Frontend Assets:"
	@echo "  make npm-install - Install Node.js dependencies"
	@echo "  make npm-build   - Build production assets (Vite)"
	@echo "  make npm-dev     - Start Vite dev server"
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
	@echo "🔑 Laravel Keys:"
	@echo "  make key-generate - Generate APP_KEY (with container restart & cache clear)"
	@echo ""
	@echo "🎛️ Filament:"
	@echo "  make filament-shield - Install and setup Filament Shield (roles & permissions)"
	@echo "  make filament-super-admin - Create super admin user (interactive)"
	@echo "  make filament-user-interactive - Create admin user (interactive)"

# Installation & Setup - Complete project setup
install: build up composer-install npm-install migrate seed
	@echo ""
	@echo "🎉 Installation complete!"
	@echo ""
	@echo "📋 Next steps:"
	@echo "  1. Run 'make key-generate' to generate encryption key"
	@echo "  2. Run 'make npm-build' to build frontend assets"
	@echo "  3. Run 'make filament-shield' to setup roles & permissions"
	@echo "  4. Run 'make filament-super-admin' to create super admin user"
	@echo "  5. Access admin panel: http://localhost:8080/admin"
	@echo ""

# Complete cleanup - Stop containers, remove all Docker resources and clean caches
clean-all:
	@echo "🧹 Starting complete cleanup..."
	@echo "🛑 Stopping all containers..."
	-docker-compose down 2>/dev/null || true
	@echo "🗑️ Removing project containers, images, volumes and orphans..."
	-docker-compose down --rmi all --volumes --remove-orphans 2>/dev/null || true
	@echo "📁 Removing application cache folders..."
	-rm -rf vendor 2>/dev/null || true
	-rm -rf node_modules 2>/dev/null || true
	-rm -rf storage/logs/* 2>/dev/null || true
	-rm -rf bootstrap/cache/*.php 2>/dev/null || true
	@echo "🔧 Removing Composer lock and cache..."
	-rm -rf ~/.composer/cache 2>/dev/null || true
	@echo "🎨 Removing npm cache..."
	-rm -rf ~/.npm 2>/dev/null || true
	@echo ""
	@echo "✅ Complete cleanup finished!"
	@echo ""
	@echo "🚀 Ready for fresh start! Run 'make install' to reinstall everything."
	@echo ""

env-setup:
	@echo "⚙️ Setting up environment file..."
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		echo "✅ Created .env file from .env.example"; \
	else \
		echo "ℹ️ .env file already exists"; \
	fi

build:
	@echo "🔨 Building Docker containers..."
	docker-compose build

up:
	@echo "🚀 Starting Docker containers..."
	docker-compose up -d

down:
	@echo "🛑 Stopping Docker containers..."
	docker-compose down

# Composer & Laravel
composer-install:
	@echo "📦 Installing Composer dependencies..."
	docker-compose exec app composer install

# Node.js & Frontend
npm-install:
	@echo "📦 Installing Node.js dependencies..."
	docker-compose exec app npm install

npm-build:
	@echo "🎨 Building frontend assets..."
	docker-compose exec app npm run build

npm-dev:
	@echo "🔧 Running Vite dev server..."
	docker-compose exec app npm run dev

migrate:
	@echo "🗄️ Running migrations..."
	docker-compose exec app php artisan migrate

seed:
	@echo "🌱 Seeding database..."
	docker-compose exec app php artisan db:seed

fresh:
	@echo "🔄 Fresh migration with seeding..."
	docker-compose exec app php artisan migrate:fresh --seed

# Queue Management
queue-status:
	@echo "⚡ Checking queue worker status..."
	docker-compose exec app supervisorctl status

queue-restart:
	@echo "🔄 Restarting queue workers..."
	docker-compose exec app supervisorctl restart laravel-queue:*

queue-monitor:
	@echo "👀 Monitoring queue jobs..."
	docker-compose exec app php artisan queue:monitor

# Development
shell:
	@echo "🐚 Accessing app container..."
	docker-compose exec app bash

logs:
	@echo "📋 Viewing container logs..."
	docker-compose logs -f

test:
	@echo "🧪 Running tests..."
	docker-compose exec app php artisan test

# Filament
filament-shield:
	@echo "🛡️ Installing and setting up Filament Shield..."
	@echo "📦 Installing Filament Shield package..."
	docker-compose exec app composer require bezhansalleh/filament-shield
	@echo "🔧 Publishing Shield resources..."
	docker-compose exec app php artisan vendor:publish --tag=filament-shield-config
	@echo "🗄️ Running Shield migrations..."
	docker-compose exec app php artisan migrate
	@echo "👤 Installing Shield for admin panel..."
	docker-compose exec app php artisan shield:install admin
	@echo "👤 Generating permissions and roles..."
	docker-compose exec app php artisan shield:generate --all
	@echo "✅ Filament Shield setup complete!"

filament-super-admin:
	@echo "👤 Creating Filament admin user..."
	docker-compose exec app php artisan shield:super-admin --panel=admin

filament-user-interactive:
	@echo "👤 Creating Filament admin user (interactive)..."
	docker-compose exec -it app php artisan make:filament-user

# Key generation with full reload
key-generate:
	@echo "🔑 Generating application key..."
	docker-compose exec app php artisan key:generate
	@echo "🧹 Clearing Laravel caches..."
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan cache:clear
	@echo "🔄 Restarting app container..."
	docker-compose down && docker-compose up -d
	@echo "✅ Application key setup complete!"

# Complete rebuild - Clean everything and reinstall
rebuild: clean-all install
	@echo ""
	@echo "🎉 Project rebuild complete!"
	@echo ""
	@echo "📋 Next steps:"
	@echo "  1. Run 'make npm-build' to build frontend assets"
	@echo "  2. Run 'make filament-shield' to setup roles & permissions"
	@echo "  3. Run 'make filament-super-admin' to create super admin user"
	@echo "  4. Access admin panel: http://localhost:8080/admin"
	@echo ""
