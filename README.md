# 📚 Bookstore

A Laravel 13 bookstore app built with the Livewire starter kit, running in Docker with Laravel Sail and MySQL.

## 🌠 Installation

### 1. Start your environment

1. Start **Docker Desktop** and wait until it shows *Engine running*.
2. Open the **Ubuntu** terminal (WSL 2).
3. Go to the folder where you keep your projects:

```bash
cd ~
```

### 2. Get the project

```bash
git clone https://github.com/DinNamprom/Database_BookEcomm.git Database_BookEcomm
cd Database_BookEcomm/bookstore
```

### 3. Install the PHP dependencies

```bash
docker run --rm \
-u "$(id -u):$(id -g)" \
-v "$(pwd):/var/www/html" -w /var/www/html \
laravelsail/php84-composer:latest \
composer install --ignore-platform-reqs
```

### 4. Create and configure `.env`

```bash
cp .env.example .env
```

Open `.env` and set these values:

```dotenv
APP_NAME=Bookstore
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 5. Set the timezone for the MySQL container

Open `compose.yaml`, find the `mysql:` service and add the two highlighted lines to its existing `environment:` block:

```yaml
mysql:
    image: 'mysql:8.4'
    ports:
        - '${FORWARD_DB_PORT:-3306}:3306'
    environment:
        TZ: 'Asia/Bangkok'                                   # added
        MYSQL_INIT_COMMAND: "SET GLOBAL time_zone = '+07:00';"   # added
        MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
        MYSQL_ROOT_HOST: '%'
        MYSQL_DATABASE: '${DB_DATABASE}'
        . . .
```

### 6. Start Sail

```bash
./vendor/bin/sail up -d
```

`sail up -d` starts the containers in the background (detached). The first run builds the image and takes a few minutes. Go to Docker Desktop and check that both containers (`laravel.test` and `mysql`) are running.

> If your terminal can't run `./vendor/bin/sail`, use `docker compose up -d` instead. Every `sail ...` command in this guide can be written as `docker compose exec laravel.test ...`.

### 7. Generate the app key

```bash
./vendor/bin/sail artisan key:generate
```

### 8. Install the frontend and build it

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

### 9. Run the database migrations

```bash
./vendor/bin/sail artisan migrate
```

Open the app at **http://localhost**.

## 🗄️ Connect DBeaver (optional)

Create a new **MySQL** connection with these settings:

| Field | Value |
|---|---|
| Host | `localhost` |
| Port | `3306` (or the value of `FORWARD_DB_PORT` in `.env`) |
| Database | `laravel` |
| Username | `sail` |
| Password | `password` |

Use `localhost` here, because DBeaver runs on Windows, outside Docker. If you see *Public Key Retrieval is not allowed*, open the connection's driver properties and set `allowPublicKeyRetrieval` to `true`.

## 🧰 Everyday commands

```bash
./vendor/bin/sail up -d                 # start the containers
./vendor/bin/sail down                  # stop the containers
./vendor/bin/sail artisan migrate       # run new migrations
./vendor/bin/sail artisan migrate:fresh # drop all tables and re-run every migration (deletes data)
./vendor/bin/sail artisan optimize:clear # clear cached config, routes and views
```

## 🛠️ Troubleshooting

| Problem | Fix |
|---|---|
| `failed to connect to the docker API` | Start Docker Desktop, and enable WSL Integration for your Ubuntu distro. |
| `No application encryption key has been specified` | Run `./vendor/bin/sail artisan key:generate`. |
| `Vite manifest not found` | Run `./vendor/bin/sail npm run build`. |
| `tempnam(): file created in the system's temporary directory` or other permission errors | Run `docker compose exec -u root laravel.test chmod -R 777 storage bootstrap/cache`, and set `WWWUSER` / `WWWGROUP` in `.env`. |
| `attempt to write a readonly database` (SQLite) | Run `docker compose exec -u root laravel.test chmod -R 777 database`, and set `WWWUSER` / `WWWGROUP` in `.env`. |
| Site not reachable at port 8000 | Sail serves the app on port **80**. Open http://localhost, or set `APP_PORT=8000` in `.env` and run `sail down` then `sail up -d`. |
| Changes in `.env` are ignored | Run `./vendor/bin/sail artisan config:clear`. |
