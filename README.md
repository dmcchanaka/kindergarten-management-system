# Kindergarten Management System

A Laravel + Vue 3 application containerized using Docker and Docker Compose with HMR (Hot Module Replacement) enabled for seamless local development.

---

## 🚀 Getting Started (Docker Setup)

Follow these steps to run the application locally using Docker:

### 1. Configure the Environment
Copy the example environment file and make sure the database and API settings are configured:
```bash
cp .env.example .env
```

Ensure the following variables are configured for Docker in your `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=kindergarten_db
DB_USERNAME=root
DB_PASSWORD=

APP_URL=http://localhost:8080
FRONT_URL=http://localhost:8080
SANCTUM_STATEFUL_DOMAINS=localhost:8080
SESSION_DOMAIN=localhost
VITE_APP_API_URL=http://localhost:8080/api/web/v1/
```

### 2. Start the Docker Containers
Build the images and run the services in the background:
```bash
docker compose up --build -d
```

This starts four containers:
- **`app`**: Runs PHP-FPM (Laravel backend) on port `9000`.
- **`web`**: Runs Nginx on port `8080` (HTTP entry point).
- **`db`**: Runs MySQL 8 database on port `3306`.
- **`vite`**: Runs Vite dev server on port `5173` (Vue frontend compiler with hot-reloading).

### 3. Run Migrations & Seeders
Once the containers are running and the database is initialized, run database migrations and seeders:
```bash
docker compose exec app php artisan migrate --seed
```

### 4. Re-link Storage (If needed)
To link your public files storage directory:
```bash
docker compose exec app php artisan storage:link --force
```

### 5. Access the Application
Open your browser and navigate to:
- **Application URL**: [http://localhost:8080](http://localhost:8080)
- **Login Route**: [http://localhost:8080/sign-in](http://localhost:8080/sign-in)

**Default Admin Credentials:**
- **Username:** `admin` (or `admin@gmail.com`)
- **Password:** `123`

---

## 🛠️ Daily Development Commands

### Clear Cache
```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
```

### Stopping Services
```bash
docker compose down
```

### Restarting & Rebuilding Services
```bash
docker compose down && docker compose up --build -d
```