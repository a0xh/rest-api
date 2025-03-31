USER_ID ?= 1000
GROUP_ID ?= 1000

# Execute all commands sequentially
setup: build env-prepare composer-install key-generate migrate-seed

# Deploy the project
all: setup

# Start containers and build images
build:
	docker compose up --build -d --remove-orphans

# Start containers
.PHONY: start
start:
	docker compose up -d

# Restart containers
.PHONY: stop
stop:
	docker compose down --volumes --remove-orphans

.PHONY: restart
restart:
	make stop
	make start

# Copy .env.example to .env
env-prepare:
	cp .env.example .env

# Install Composer dependencies
composer-install:
	composer install

# Generate application key
key-generate:
	docker compose exec app php artisan key:generate

# Refresh and seed the database
migrate-seed:
	docker compose exec app php artisan migrate:fresh --seed
