# Restaurant Reservation System

Simple Laravel-based table reservation system.

## Overview

- Users create and cancel their own reservations
- Capacity checks prevent overbooking
- Email notifications are sent on create/cancel

## Install

1. Install dependencies:
   ```bash
   composer install
   npm install
   ```
2. Copy and configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Run migrations:
   ```bash
   php artisan migrate
   ```
4. Build assets and start the app:
   ```bash
   npm run dev
   php artisan serve
   ```

## Server

### Requirements

- PHP 8.4+
- Composer
- Node.js 18+ (build only)
- Database (SQLite, MySQL, or Postgres)
- Queue driver (sync for simple setups, or database/redis for production)
- Mailer (SMTP or compatible provider)

### Deployment

1. Provision and configure the server:
   - Create the database and user
   - Set web root to `public`
   - Configure the mailer credentials
2. Pull the code and install dependencies:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   ```
3. Configure the environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
4. Migrate and seed (if needed):
   ```bash
   php artisan migrate --force
   ```
5. Run the queue worker:
   ```bash
   php artisan queue:work
   ```
