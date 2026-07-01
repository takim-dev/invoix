# InvoiceApp

A professional invoice management application built with CodeIgniter 4. Create, send, and track invoices across multiple currencies, manage multiple companies, build a reusable item catalog, and export polished PDFs — all in one clean dashboard.

## Features

- **Multi-currency** — USD, IDR, MYR, CNY, INR, EUR, SAR, VND. Item prices can be defined per currency and flow through to invoice totals.
- **Multiple companies** — Run several businesses under one account. Each gets its own short code embedded in invoice numbers.
- **Item catalog** — Reusable items with categories and per-currency pricing. Pick from catalog when building invoices.
- **One-click PDF** — Polished PDF export with company branding via dompdf.
- **Print view** — Clean HTML print layout with royal navy theme.
- **Excel export** — Download invoice lists as Excel-compatible files.
- **Server-side DataTables** — Fast, searchable, paginated lists for invoices, items, and users.
- **Company logo upload** — PNG/JPG/WEBP with auto-downscaling (max 2MB, 1000px longest edge).
- **Internationalization** — 9 languages: English, Bahasa Indonesia, Bahasa Malaysia, Chinese, Vietnamese, Arabic, Spanish, French, Hindi.
- **Email verification** — Token-based verification with configurable method (none / email / admin approval).
- **Admin panel** — User management, app settings, invoice config, auth settings, email/SMTP config, page content.
- **Per-user limits** — Configurable max companies and max invoices per user.
- **Security** — CSRF protection, per-user data ownership, admin approval flow, bcrypt password hashing.

## System Requirements

- PHP 8.2 or higher
- MySQL 5.7+ / MariaDB 10.3+
- Composer 2.x
- PHP Extensions:
  - `intl` — internationalization
  - `mbstring` — multibyte strings
  - `json` — enabled by default
  - `mysqlnd` — MySQL native driver
  - `gd` — image processing (logo upload)
  - `libcurl` — HTTP requests (optional)

## Quick Start (Local Development)

### 1. Clone & Install Dependencies

```bash
git clone <repo-url> invoice-app
cd invoice-app
composer install
```

### 2. Configure Environment

```bash
cp env .env
```

Edit `.env` and set:

```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
database.default.hostname = localhost
database.default.database = invoice_app
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
encryption.key = <generate-a-random-32-char-string>
```

### 3. Create the Database Schema

The app requires 8 core tables. Run this SQL on your database before running migrations:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    code VARCHAR(20) NULL UNIQUE,
    language VARCHAR(10) DEFAULT 'en',
    role ENUM('admin','user') DEFAULT 'user',
    status ENUM('active','pending','blocked') DEFAULT 'pending',
    max_companies INT DEFAULT 5,
    max_invoices INT DEFAULT 100,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    code VARCHAR(20) NOT NULL,
    address TEXT NULL,
    phone VARCHAR(30) NULL,
    email VARCHAR(100) NULL,
    tax_number VARCHAR(50) NULL,
    logo VARCHAR(255) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company_id INT NOT NULL,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    client_name VARCHAR(150) NOT NULL,
    client_email VARCHAR(100) NULL,
    client_address TEXT NULL,
    invoice_date DATE NOT NULL,
    due_date DATE NULL,
    notes TEXT NULL,
    status ENUM('draft','sent','paid','unpaid','cancelled') DEFAULT 'draft',
    currency VARCHAR(10) DEFAULT 'USD',
    subtotal DECIMAL(15,2) DEFAULT 0,
    tax_rate DECIMAL(5,2) DEFAULT 0,
    tax_amount DECIMAL(15,2) DEFAULT 0,
    total DECIMAL(15,2) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

CREATE TABLE invoice_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    item_id INT NULL,
    description VARCHAR(255) NOT NULL,
    quantity DECIMAL(10,2) DEFAULT 1,
    unit_price DECIMAL(15,2) DEFAULT 0,
    total DECIMAL(15,2) DEFAULT 0,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE
);

CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    unit_price DECIMAL(15,2) DEFAULT 0,
    currency VARCHAR(10) DEFAULT 'USD',
    unit VARCHAR(50) NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE item_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE app_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE email_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    verified_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 4. Run Migrations

```bash
php spark migrate
```

This applies 5 additive migrations:
- `add_email_verification_and_config` — email verification system
- `add_currency_to_items` — currency column on items
- `add_user_company_codes` — user and company short codes
- `make_company_code_globally_unique` — prevents invoice number prefix collisions
- `add_user_language` — per-user language preference

### 5. Create Admin User

There is no built-in admin seeder. Hash a password locally then insert the admin:

```php
// Run this in a throwaway PHP file or php -a interactive shell:
echo password_hash('your-password', PASSWORD_DEFAULT);
```

```sql
INSERT INTO users (name, email, password, role, status, max_companies, max_invoices, created_at, updated_at)
VALUES ('Admin', 'admin@example.com', '<bcrypt-hash-from-above>', 'admin', 'active', 999, 9999, NOW(), NOW());
```

### 6. Start the Dev Server

```bash
php spark serve --port 8080
```

Visit `http://localhost:8080` and log in with the admin credentials.

## Default Admin Credentials

No defaults exist — you set them in step 5 above. The admin email/password is whatever you choose when inserting the user row with `role = 'admin'`.

> **Note:** The app protects against removing the last admin. You cannot delete or deactivate the sole remaining active admin account.

## Production Deployment

1. **Upload** all files to your web server.
2. **Run** `composer install --no-dev --optimize-autoloader`.
3. **Copy** `env` to `.env` and configure:
   ```ini
   CI_ENVIRONMENT = production
   app.baseURL = 'https://your-domain.com/'
   database.default.hostname = <production-host>
   database.default.database = <production-db>
   database.default.username = <production-user>
   database.default.password = <production-password>
   encryption.key = <strong-random-string>
   ```
4. **Create database tables** using the schema above, then run `php spark migrate`.
5. **Create admin user** as described in step 5 above.
6. **Set document root** to the `public/` directory.
7. **Ensure writable permissions** on `writable/` and its subdirectories.
8. **Configure SMTP** via the admin panel (Settings → Email Config) for email verification to work.

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | CodeIgniter 4.7+ |
| PHP | 8.2+ |
| Database | MySQL / MariaDB (MySQLi driver) |
| PDF | dompdf 3.1 |
| Frontend | Bootstrap 5.3, Bootstrap Icons, DataTables |
| I18n | CI4 Language files (9 locales) |
| Auth | Session-based with CSRF protection |

## Project Structure

```
invoice-app/
├── app/
│   ├── Config/          # Routes, Filters, Validation, Security, App
│   ├── Controllers/     # 13 controllers (Auth, Admin, Invoice, etc.)
│   ├── Database/
│   │   ├── Migrations/  # 5 additive migrations
│   │   └── Seeds/       # (empty — no built-in seeders)
│   ├── Filters/         # AuthFilter, LocaleFilter
│   ├── Helpers/         # currency_helper, format_helper
│   ├── Language/        # 9 locale directories × 7 files each
│   ├── Models/          # 8 models (User, Company, Invoice, etc.)
│   └── Views/           # 40+ views (layouts, invoices, items, companies, etc.)
├── public/
│   └── uploads/logos/   # Company logo uploads
├── writable/            # Logs, cache, session files
├── .env                 # Environment configuration
├── composer.json        # PHP 8.2, CI4 4.7, dompdf 3.1
└── spark                # CI4 CLI tool
```

## Key Routes

| Method | Path | Action |
|--------|------|--------|
| GET | `/` | Landing page |
| GET | `/login` | Login form |
| POST | `/login` | Process login |
| GET | `/register` | Registration form |
| POST | `/register` | Process registration |
| GET | `/verify-email/{token}` | Email verification |
| GET | `/language/{locale}` | Switch language |
| GET/POST | `/dashboard` | User dashboard |
| GET/POST | `/invoices` | Invoice list (DataTables) |
| GET/POST | `/invoices/create` | Create invoice |
| GET | `/invoices/{id}` | View invoice |
| GET/POST | `/invoices/{id}/edit` | Edit invoice |
| POST | `/invoices/{id}/delete` | Delete invoice |
| GET | `/invoices/{id}/print` | Print view |
| GET | `/invoices/{id}/pdf` | PDF download |
| GET | `/invoices/export` | Excel export |
| POST | `/invoices/{id}/status` | Change status |
| GET/POST | `/items` | Item list (DataTables) |
| GET/POST | `/items/create` | Create item |
| GET/POST | `/items/{id}/edit` | Edit item |
| POST | `/items/{id}/delete` | Delete item |
| GET/POST | `/companies` | Company list (DataTables) |
| GET/POST | `/companies/create` | Create company |
| GET/POST | `/companies/{id}/edit` | Edit company |
| POST | `/companies/{id}/delete` | Delete company |
| GET/POST | `/account` | Account settings |
| GET/POST | `/admin` | Admin dashboard |
| GET/POST | `/admin/users` | User management |
| GET/POST | `/admin/settings` | App settings |
| GET | `/about` | About page |
| GET | `/contact` | Contact page |
| GET | `/help` | Help page |

## CLI Commands

```bash
php spark migrate              # Run pending migrations
php spark migrate:rollback     # Rollback last migration batch
php spark migrate:refresh      # Rollback all + re-migrate
php spark make:migration       # Create a new migration file
php spark db:seed              # Run seeders
php spark serve                # Start PHP built-in dev server
php spark routes               # List all registered routes
php spark make:controller      # Create a new controller
php spark make:model           # Create a new model
php spark make:filter          # Create a new filter
```

## User Roles & Limits

| Role | Capabilities |
|------|-------------|
| **admin** | Full access to admin panel, user management, app settings, SMTP config |
| **user** | Dashboard, invoices, items, companies, account settings |

User limits are set per-user via `max_companies` and `max_invoices`. Defaults for new registrations are configurable in the admin panel (Settings → Invoice Config). Admins can override limits per user.

## Internationalization

9 supported locales: `en`, `id`, `ms`, `zh`, `vi`, `ar`, `es`, `fr`, `hi`.

Users can switch language from:
- The language dropdown in the navbar
- Their account settings page (persists to database)

The admin can set a default locale in App Settings.

