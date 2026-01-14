# Restaurant Reservation System

Simple Laravel-based table reservation system.

## Installation and Running

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
4. Start the app:
   ```bash
   npm run dev
   php artisan serve
   ```
