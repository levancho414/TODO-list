# Task Manager — Laravel Midterm Project

A small, self-contained task manager built with **Laravel** to demonstrate MVC
architecture, migrations, Eloquent, validation, Blade templating, resource
routes, and a responsive Bootstrap UI.

Built as the **midterm project** and designed to be the foundation for the
final project.

---

## Features

- Full CRUD for tasks (create, read, update, delete)
- Mark a task as **Done / Pending** with a single click (PATCH toggle)
- **Filter** tasks by status: All / Pending / Done
- **Search** tasks by title or description (LIKE-safe, wildcard-escaped)
- Server-side **validation** with inline error messages
- **Deadline** field with automatic Carbon date casting
- Tasks ordered by nearest deadline (null deadlines sink to the bottom)
- Flash "success" messages after every write action
- Responsive, dark-themed **Bootstrap 5.3** UI
- Seed data so the app is never empty on first run
- SQLite by default (zero-config); MySQL-compatible `.sql` dump included

---

## Tech stack

| Layer      | Technology                           |
|------------|--------------------------------------|
| Backend    | Laravel 12 (PHP 8.2+)                |
| Database   | SQLite (default) / MySQL-compatible  |
| Frontend   | Blade + Bootstrap 5.3 (CDN)          |
| Styling    | Custom dark theme (`public/css/task-theme.css`) |

No external backend framework is used — only Laravel, as required.

---

## Project structure (key files)

```
app/
  Http/Controllers/TaskController.php   # CRUD + toggle + filter/search logic
  Models/Task.php                       # Eloquent model (fillable + casts)
database/
  migrations/..._create_tasks_table.php # Tasks schema
  seeders/TaskSeeder.php                # Sample rows
  seeders/DatabaseSeeder.php            # Seeder entry point
  database.sqlite                       # SQLite DB (pre-populated)
resources/views/
  layouts/app.blade.php                 # Shared layout + navbar
  tasks/index.blade.php                 # List + filter + search UI
  tasks/create.blade.php                # Create form
  tasks/edit.blade.php                  # Edit form
  tasks/show.blade.php                  # Task detail page
  tasks/_form.blade.php                 # Shared form partial
routes/web.php                          # Resource + toggle routes
public/css/task-theme.css               # Dark-mode theme overrides
database.sql                            # MySQL-compatible DB export
```

---

## Requirements

- PHP **8.2** or higher
- Composer
- (Bundled) SQLite extension — enabled by default in PHP 8.2+

---

## Setup & run (fresh clone / unzipped submission)

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy the example environment file and generate an app key
cp .env.example .env
php artisan key:generate

# 3. Create the SQLite database file (if it doesn't exist already)
#    On Windows PowerShell:
#      New-Item -ItemType File -Path database/database.sqlite
#    On macOS/Linux:
#      touch database/database.sqlite

# 4. Run migrations and seed sample data
php artisan migrate:fresh --seed

# 5. Start the dev server
php artisan serve
```

Then open <http://127.0.0.1:8000> — you'll be redirected to `/tasks`.

### Using MySQL instead of SQLite

Import the included `database.sql` into a database (e.g. `task_manager`),
then set the following values in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

No code changes are required — Laravel picks the driver from `.env`.

---

## Routes

| Method       | URI                       | Name            | Action                     |
|--------------|---------------------------|-----------------|----------------------------|
| GET          | `/`                       | —               | Redirects to `/tasks`      |
| GET          | `/tasks`                  | tasks.index     | List, filter, search       |
| GET          | `/tasks/create`           | tasks.create    | Show create form           |
| POST         | `/tasks`                  | tasks.store     | Persist a new task         |
| GET          | `/tasks/{task}`           | tasks.show      | Task detail page           |
| GET          | `/tasks/{task}/edit`      | tasks.edit      | Show edit form             |
| PUT / PATCH  | `/tasks/{task}`           | tasks.update    | Update a task              |
| DELETE       | `/tasks/{task}`           | tasks.destroy   | Delete a task              |
| PATCH        | `/tasks/{task}/toggle`    | tasks.toggle    | Flip status: done ↔ pending|

---

## Validation rules

Both `store` and `update` apply the same rules:

| Field        | Rule                                  |
|--------------|---------------------------------------|
| title        | required, string, max 255             |
| description  | optional, string                      |
| status       | required, in: `pending`, `done`       |
| deadline     | optional, valid date                  |

Errors are returned with the old input and rendered inline via the
Blade `@error` directive.

---

## Database export

A MySQL-compatible dump of the seeded database is provided at the project
root as **`database.sql`**, per the submission requirements.

---

## Submission checklist

- [x] Entire Laravel project (zipped)
- [x] Database export (`database.sql`)
- [x] Clean, commented, well-structured code
- [x] Runs without errors (`php artisan serve`)
- [x] Uses only Laravel — no external backend framework
