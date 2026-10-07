# Buku Masjid - Development Guide

This document contains essential information for understanding the architecture, setup, commands, and coding patterns of the Buku Masjid application.

---

## 1. Project Overview

[Buku Masjid](https://github.com/buku-masjid/buku-masjid) is a web-based financial management and lecturing schedule system for mosques (masjid/mushalla) built with Laravel. It serves both authenticated mosque administrators and unauthenticated public visitors.

**Core objectives:**
- **Transparency**: Publish cash/financial reports online for congregation and public.
- **Convenience**: Simplify income/spending transaction logging for treasurers.
- **Automation**: Auto-generate monthly, weekly, and categorized financial reports.
- **Scheduling**: Manage routine lecturing (pengajian) and Friday khatib schedules.

---

## 2. Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP `^8.1`, Laravel `10.x` |
| Database | MySQL / MariaDB (production), SQLite in-memory (tests) |
| Frontend | Bootstrap `4.0.0`, SCSS/Sass, Vanilla JS |
| Asset Bundler | Laravel Mix `6.x` (Webpack) |
| Reactive UI | Livewire `2.x` |
| API Auth | Laravel Passport `11.x` (OAuth2) |
| Testing | PHPUnit `10.x`, Laravel BrowserKit Testing |
| Code Style | Laravel Pint (Laravel preset) |
| Deployment | Deployer (`deploy.php`) |
| Package Manager | Yarn (see `.nvmrc` for Node version) |

---

## 3. Development Commands

### Initial Setup

```bash
cp .env.example .env
php artisan key:generate
php artisan passport:keys          # Required for API authentication
php artisan storage:link           # Link public disk for file uploads
php artisan migrate --seed         # Creates tables + seeds default user/book/categories
```

**Default credentials after seeding:**
```
URL:      http://localhost:8000
Email:    admin@example.net
Password: password
```

### Local Server

```bash
php artisan serve
```

### Database

```bash
php artisan migrate --seed                           # Fresh migration with seed
php artisan buku-masjid:generate-demo-data           # Generate dummy data (last 3 months)
php artisan buku-masjid:remove-demo-data             # Remove dummy data (where created_at IS NULL)
php artisan partner:generate {type_code} {--count=} {--reset}  # Generate fake partner records
php artisan partner:upgrade-type-levels              # One-time migration for partner type/level format
```
> **Note:** Custom commands can be found in `app/Console/Commands` or as closures in `routes/console.php`.

### Frontend Assets

```bash
yarn                   # Install JS dependencies (use yarn, not npm)
npm run dev            # Development build (one-time)
npm run watch          # Watch mode for development
npm run prod           # Production build
```

### Testing

```bash
vendor/bin/phpunit                    # Run all tests (395 tests, uses SQLite in-memory)
vendor/bin/phpunit --filter TestName  # Run specific test
```

> **Note:** Tests run on SQLite in-memory (configured in `phpunit.xml`). No database setup needed for tests.

### Code Quality

```bash
vendor/bin/pint          # Fix code style issues
vendor/bin/pint --test   # Check code style without fixing (used in CI)
```

---

## 4. Key Environment Variables (`.env`)

All variables below come from `.env.example`. Critical ones to configure:

### Application
```dotenv
APP_NAME="Buku Masjid"
APP_ENV=local
APP_URL=http://localhost
APP_TIMEZONE="Asia/Makassar"    # Affects Carbon date/time calculations
```

### Database
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

### Mosque Identity
```dotenv
MASJID_NAME="Masjid Ar-Rahman"
MASJID_DEFAULT_BOOK_ID=1         # Fallback active book
AUTH_DEFAULT_PASSWORD=password   # Default password used by seeder
```

### Money Formatting
```dotenv
MONEY_CURRENCY_CODE="Rp"
MONEY_CURRENCY_TEXT="Rupiah"
MONEY_PRECISION=2
MONEY_DECIMAL_SEPARATOR=","
MONEY_THOUSANDS_SEPARATOR="."
```

### Partners (Congregation Members/Donors)
```dotenv
PARTNER_TYPES="donatur|Donatur"                            # Pipe-delimited: code|label
PARTNER_LEVELS="donatur:silver|Silver|gold|Gold|platinum|Platinum"           # typeCode:levelCode|label,...
PARTNER_INCOME_DEFAULT_VALUE="Hamba Allah"
PARTNER_SPENDING_DEFAULT_VALUE="Tanpa Nama"
```

### Feature Flags
```dotenv
FEATURES_LECTURINGS_IS_ACTIVE=true       # Enable lecturing/schedule module
FEATURES_DONORS_IS_ACTIVE=true           # Enable donor sub-module (subset of partners)
FEATURES_SHALAT_TIME_IS_ACTIVE=true      # Enable shalat time display on public page
FEATURES_PUBLIC_DISPLAY_IS_ACTIVE=false  # Enable /display TV screen page
```

### Shalat Time Integration
```dotenv
SHALAT_TIME_PROVIDER=myquran_api
MYQURAN_CITY_NAME="Kota Banjarmasin"    # Must match a city name in MyQuran API
```

### Public Display (TV Screen Mode)
```dotenv
PUBLIC_DISPLAY_THEME=default   # or "light"
```

### Disk & Queue
```dotenv
DISK_QUOTA=1GB             # Used by DiskUsageService to warn when storage is full
QUEUE_DRIVER=sync          # Change to "database" or "redis" for async image optimization
FILESYSTEM_DRIVER=public   # File uploads go to storage/app/public
```

---

## 5. Architecture & Core Patterns

### A. Multi-Tenancy via Active Book

Each mosque activity/group has its own "buku catatan" (cash book). The system filters all data by the currently active book.

- **Session/Token macro**: `auth()->activeBook()` and `auth()->activeBookId()` are added as macros on both `SessionGuard` and `TokenGuard` in [AppServiceProvider.php](file:///c:/xampp/htdocs/buku-masjid/app/Providers/AppServiceProvider.php).
- **Global query scope**: The [ForActiveBook](file:///c:/xampp/htdocs/buku-masjid/app/Traits/Models/ForActiveBook.php) trait adds `WHERE book_id = auth()->activeBookId()` to every query on models that use it (`Transaction`, `Category`).
- **Bypassing the scope**: Use `->withoutGlobalScope('forActiveBook')` when you need cross-book queries (e.g., `Partner->transactions()` or `Book->getBalance()`).

### B. User Roles & Authorization

Four roles defined as constants in [User.php](file:///c:/xampp/htdocs/buku-masjid/app/User.php):

| Role | Constant | Capabilities |
|---|---|---|
| Admin | `ROLE_ADMIN = 1` | Full access: manage books, users, backups, masjid profile |
| Chairman | `ROLE_CHAIRMAN = 2` | Read-only reports |
| Secretary | `ROLE_SECRETARY = 3` | Read-only reports |
| Finance | `ROLE_FINANCE = 4` | Manage transactions/categories for books they manage |

Authorization is enforced via [Policies](file:///c:/xampp/htdocs/buku-masjid/app/Policies) (`BookPolicy`, `TransactionPolicy`, etc.) and Gates (defined in `AuthServiceProvider`).

> **Key rule in `BookPolicy`**: A `ROLE_FINANCE` user can only manage transactions in books where they are the `manager_id`.

### C. Request-Based Business Logic

Thin controllers — validation and database persistence are delegated to FormRequest classes:

```php
// Controller delegates to CreateRequest::save()
$transaction = $transactionCreateForm->save();
```

The `save()` method in [CreateRequest.php](file:///c:/xampp/htdocs/buku-masjid/app/Http/Requests/Transactions/CreateRequest.php) handles transaction creation, file upload, and dispatching the `OptimizeImage` job.

### D. Polymorphic Settings via Facade

A key-value settings store that supports both global and per-model settings:

```php
// Global setting
Setting::get('masjid_name');
Setting::set('masjid_address', 'Jl. Masjid No. 1');

// Model-specific setting (e.g., per-book)
Setting::for($book)->get('income_partner_codes');
Setting::for($book)->set('income_partner_codes', json_encode(['donatur']));
```

Implemented in [Setting.php Helper](file:///c:/xampp/htdocs/buku-masjid/app/Helpers/Setting.php), accessed via real-time facade `Facades\App\Helpers\Setting` (aliased as `Setting` in `config/app.php`).

### E. Partner System (Congregation Members)

The `Partner` model is highly configurable via env vars. Partner types and levels are defined in `.env` as pipe-delimited strings:

```dotenv
PARTNER_TYPES="donatur|Donatur,jamaah|Jamaah"
PARTNER_LEVELS="donatur:silver|Silver|gold|Gold|platinum|Platinum"
```

`type_code` and `level_code` columns on `partners` table are **JSON arrays** (cast via `$casts`). A partner can have multiple types.

The `Donor` feature is a subset of Partners, filtered to `type_code = 'donatur'`.

### F. File Attachments & Queue

Transactions can have photo attachments (receipts). File workflow:
1. Upload via `Transactions\FileController` or during transaction creation.
2. Raw image stored immediately to disk (`storage/app/public/files/YYYY/MM/DD/`).
3. `OptimizeImage` job dispatched to resize to max 1000x1000px using Intervention Image.
4. Job updates `File.type_code` from `raw_image` → `image` when done.

> **Important**: With `QUEUE_DRIVER=sync` (default), image optimization happens synchronously during the request. Set to `database` for async processing.

### G. Shalat Time Integration

- Service interface: `App\Services\ShalatTimes\ShalatTimeService`
- Implementation: [MyQuranShalatTimeService](file:///c:/xampp/htdocs/buku-masjid/app/Services/ShalatTimes/MyQuranShalatTimeService.php) — calls `api.myquran.com/v2`
- Provider is resolved via [ShalatTimeServiceProvider](file:///c:/xampp/htdocs/buku-masjid/app/Providers/ShalatTimeServiceProvider.php) using the `SHALAT_TIME_PROVIDER` env var.
- City list is **cached for 24 hours** to avoid repeated API calls.

### H. Public Display (TV Screen)

A feature for displaying mosque info on a TV screen at `/display`. Controlled by `FEATURES_PUBLIC_DISPLAY_IS_ACTIVE`. Includes real-time shalat time with iqamah countdown, financial summary carousel, and ayat/hadith quotes (configured in `config/public_display.php`).

### I. Automated Database Backup

Scheduled in [Console/Kernel.php](file:///c:/xampp/htdocs/buku-masjid/app/Console/Kernel.php) to run daily at 03:00:
```php
$schedule->command('db:backup --database=mysql --destination=local --compression=gzip ...')->dailyAt('03:00');
```
Managed via a forked `backup-manager/laravel` package (custom VCS repository in `composer.json`).

---

## 6. Code Organization

```
app/
├── Console/Kernel.php          # Scheduled tasks (daily DB backup)
├── EloquentFilters/            # Fluent query filters (e.g. PartnersFilter for search/filter)
├── Helpers/
│   ├── functions.php           # Global helpers: flash(), format_number(), balance(), etc.
│   ├── date_time.php           # Date helpers: get_months(), get_date_range_per_week(), etc.
│   ├── MapHelper.php           # Extract GPS coordinates from Google Maps links
│   └── Setting.php             # Key-value settings manager (used as facade)
├── Http/
│   ├── Controllers/
│   │   ├── Api/                # REST API controllers (Passport-authenticated)
│   │   ├── Auth/               # Login, profile, change-password
│   │   ├── BankAccounts/       # Sub-resource: bank account balances
│   │   ├── Books/              # Sub-resource: book report titles
│   │   ├── Reports/            # Internal & public financial reports (PDF support)
│   │   ├── Transactions/       # Sub-resources: export CSV, print receipt, file upload
│   │   └── Controller.php      # Base controller with shared query helpers
│   ├── Livewire/               # Reactive Livewire components
│   │   ├── Books/              # FinancialSummary (per-book dashboard)
│   │   ├── Dashboard/          # Dashboard widgets
│   │   ├── Donors/             # Donor stats (DonorsCount, IncomeStats, LevelStats, etc.)
│   │   ├── Partners/           # Partner demographic Livewire charts
│   │   ├── PublicBooks/        # Public book listing
│   │   ├── PublicDisplay/      # TV screen components
│   │   ├── PublicHome/         # Public homepage widgets
│   │   ├── SystemInfo/         # Disk/system info display
│   │   └── Transactions/       # FilesIndicator component
│   ├── Middleware/
│   │   ├── Lang.php            # Sets app locale from session; applied to all web routes
│   │   └── Cors.php            # Allows cross-origin requests for API routes
│   ├── Requests/               # FormRequest classes with validation + save() methods
│   └── Resources/              # API JSON resources (Transaction, TransactionCollection, User)
├── Jobs/Files/OptimizeImage.php # Queued job: resize uploaded images
├── Models/                     # Eloquent models (Book, Category, BankAccount, etc.)
├── Policies/                   # Authorization policies per model
├── Providers/
│   ├── AppServiceProvider.php  # Active book macros, morph map, view composers
│   ├── AuthServiceProvider.php # Policies + gates (manage_database_backup, etc.)
│   ├── ResponseMacroServiceProvider.php # Response::csv() macro for export
│   └── ShalatTimeServiceProvider.php   # Binds ShalatTimeService implementation
├── Rules/                      # Custom validation rules (PhoneNumberRule, Lecturings/)
├── Services/
│   ├── ShalatTimes/            # Shalat time API integration
│   ├── SystemInfo/             # DiskUsageService (monitors storage quota)
│   └── Transactions/           # CsvTransformer (transaction export)
├── Traits/Models/
│   ├── ForActiveBook.php       # Global scope: filter by active book_id
│   └── ConstantsGetter.php     # getConstants($group) via ReflectionClass
├── Transaction.php             # ⚠️ In app/ root, not app/Models/
└── User.php                    # ⚠️ In app/ root, not app/Models/

config/
├── features.php        # Feature toggles (lecturings, donors, shalat_time, public_display)
├── masjid.php          # Mosque identity (name, default_book_id, income/spending colors)
├── money.php           # Currency formatting (code, precision, separators)
├── partners.php        # Partner types, levels, default names, age groups
├── public_display.php  # TV display themes, iqamah intervals, sharing quotes
├── shalat_time.php     # Shalat time provider config & per-prayer adjustments
└── lecturing.php       # Emoji mappings for lecturing schedule display

routes/
├── web.php             # All HTML routes; feature-gated sections via config()
├── api.php             # REST API routes (public + Passport-authenticated)
└── console.php         # Artisan closure commands

resources/
├── assets/
│   ├── js/app.js       # JS entry (Bootstrap, jQuery, Axios)
│   └── sass/app.scss   # SCSS entry; compiled to public/css/app.css
├── lang/
│   ├── en/             # English translations
│   └── id/             # Indonesian translations (default)
└── views/              # Blade templates organized by module

tests/
├── Feature/            # HTTP/feature tests (BrowserKit-style)
├── Unit/               # Unit tests for isolated logic
├── Fakes/              # FakeDiskUsageService (replaces real service in tests)
├── Traits/
│   └── ValidateFormRequest.php  # assertValidationPasses/Fails helpers
└── TestCase.php        # Base: loginAsUser($role), createUser($role)
```

---

## 7. Database Schema (Key Tables)

| Table | Description |
|---|---|
| `users` | Auth users with `role_id`, `is_active` |
| `books` | Cash books (multi-book per mosque) |
| `categories` | Income/spending categories per book; color-coded |
| `transactions` | Income/spending records; belongs to book, category, partner, bank_account |
| `bank_accounts` | Bank accounts linked to books |
| `bank_account_balances` | Manual balance snapshots for bank accounts |
| `partners` | Congregation members/donors; JSON `type_code` & `level_code` |
| `lecturings` | Pengajian/khatib schedule entries |
| `settings` | Polymorphic key-value store (global + per-model) |
| `files` | File attachments (polymorphic `fileable`), used for transaction receipts |
| `jobs` / `failed_jobs` | Laravel queue tables |

---

## 8. API Layer

Base URL: `/api`

**Public endpoints (no auth):**
- `GET /api/schedules` — Today's lecturing schedule
- `GET /api/masjid_profile` — Mosque public info
- `GET /api/shalat_time` — Today's prayer times (if feature enabled)
- `POST /api/login` — Legacy API login
- `POST /api/auth/login` — Passport login
- `POST /api/auth/logout` — Passport logout

**Authenticated (Passport Bearer token):**
- `GET/POST/PUT/DELETE /api/transactions` — Transactions CRUD
- `GET/POST/PUT/DELETE /api/categories` — Categories CRUD
- `GET/POST/PUT/DELETE /api/books` — Books CRUD
- `GET /api/user` — Authenticated user profile

**Session-auth AJAX (for Blade forms):**
- `POST /api/masjid_profile/upload_logo`
- `POST /api/masjid_profile/upload_photo`
- `POST /api/bank_account/{id}/qris_image`
- `POST /api/books/{id}/upload_poster_image`
- `POST /api/books/{id}/upload_thumbnail_image`

API responses for Transactions use [TransactionResource](file:///c:/xampp/htdocs/buku-masjid/app/Http/Resources/Transaction.php) and [TransactionCollection](file:///c:/xampp/htdocs/buku-masjid/app/Http/Resources/TransactionCollection.php).

---

## 9. CI/CD (GitHub Actions)

Three workflows in `.github/workflows/`:

| Workflow | Trigger | What it does |
|---|---|---|
| `deploy.yml` | Every push | Compiles assets (yarn prod) + runs PHPUnit tests |
| `pint.yml` | Push to `master` or any PR | Checks code style with Laravel Pint (`--test` mode) |
| `docker.yml` | (see file) | Builds Docker image |

> **Note**: The `pint.yml` runs in **test mode** (no auto-fix). PRs with style violations will fail CI.

---

## 10. Coding Guidelines & Best Practices

### 1. Code Style (Pint)
Run `vendor/bin/pint` before committing. CI will reject PRs that fail Pint checks. Config is in [pint.json](file:///c:/xampp/htdocs/buku-masjid/pint.json) (Laravel preset with minor overrides).

### 2. Polymorphic Morph Map
When referencing models in polymorphic relationships, **always use the morph map alias** defined in `AppServiceProvider`, not the full class name:

| Use this | ❌ Not this |
|---|---|
| `'books'` | `App\Models\Book::class` |
| `'users'` | `App\User::class` |
| `'transactions'` | `App\Transaction::class` |
| `'bank_accounts'` | `App\Models\BankAccount::class` |

### 3. Active Book Scoping
Any table containing per-book data **must** have a `book_id` column, and its model must use the `ForActiveBook` trait. When querying cross-book data, use `->withoutGlobalScope('forActiveBook')`.

### 4. Namespace Anomaly — `User` and `Transaction`
`User` and `Transaction` models live in **`app/` root** (`App\User`, `App\Transaction`), **not** in `app/Models/`. This is a legacy structure. Do not move them — it would break many references.

### 5. Thin Controllers
Keep controller methods minimal. Delegate:
- **Validation + persistence** → FormRequest's `save()` method
- **Reusable queries** → base `Controller.php` protected methods (`getTansactions()`, `getYearMonth()`, etc.)
- **External services** → `app/Services/`

### 6. Localization
Use `__('key')` for all user-facing text. Both `en/` and `id/` translations must be kept in sync. Default locale is `id` (Indonesian). Users can switch language via `LangSwitcherController`, stored in session.

### 7. Feature Gates
Check feature availability via `config('features.feature_name.is_active')`. Routes for optional features are conditionally registered in `routes/web.php` and `routes/api.php` at boot time.

### 8. Test Helpers
- Extend `Tests\TestCase` for all test classes.
- Use `$this->loginAsUser('admin')` / `$this->loginAsUser('finance')` for role-based auth setup.
- Use `factory(Model::class)->create()` (legacy factory syntax via `laravel/legacy-factories`).
- Use `Tests\Traits\ValidateFormRequest` for testing FormRequest validation rules.
- Use `Tests\Fakes\FakeDiskUsageService` to mock disk checks in tests.

### 9. Money Formatting
Use the global `format_number(float $number)` helper (from `app/Helpers/functions.php`) for displaying currency values. Never hardcode currency symbols — use `config('money.currency_code')`.
