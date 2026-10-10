# Starline POS — Point-of-Sale System

A web-based Point-of-Sale (POS) system developed with CodeIgniter 4, PHP, MySQL, HTML, CSS, and Bootstrap.

The system includes product management, customer management, staff management, authentication, sales transactions, inventory tracking, and sales history. Its interface uses the Starline branding and a Malibu Escape pastel color palette.

## Features

### Product Management
- View and manage products.
- Maintain product prices, stock quantities, and images.
- Archive products when they are no longer active.

### Customer Management
- Add, view, edit, and delete customer records.
- Validate customer information, including email addresses.

### Staff Management
- Add, view, edit, and delete staff accounts.
- Upload, display, and replace profile avatars.
- Display a default avatar when no photo is uploaded.
- Store passwords using password hashing.

### Authentication
- Log in using staff credentials.
- Log out and invalidate the current session.
- Restrict management pages to authenticated users.

### Record Sale
- Select a product and customer.
- Specify the quantity being sold.
- Calculate the estimated transaction total.
- Validate available stock before completing a sale.
- Save the sale and update inventory within a database transaction.

### Sales History
- View recorded sales.
- Display product, customer, staff, quantity, unit price, total price, and transaction date.

## Technology Stack

- **Backend:** PHP and CodeIgniter 4
- **Database:** MySQL or MariaDB
- **Frontend:** HTML, CSS, Bootstrap, and JavaScript
- **Dependencies:** Composer
- **Development environment:** XAMPP
- **Version control:** Git and GitHub

## Requirements

Install the following software before setting up the project:

- XAMPP with PHP 8.2 or a compatible version supported by the project
- MySQL or MariaDB
- Composer
- Git
- A modern web browser

Enable the PHP extensions required by CodeIgniter and the application, including `intl`, `mbstring`, `mysqli`, and `gd`.

## Installation and Setup

### 1. Clone the repository

Clone the project from GitHub:

```bash
git clone https://github.com/anedair/pos_system.git
cd pos_system
```

### 2. Install dependencies

Run:

```bash
composer install
```

### 3. Create the database

Start Apache and MySQL from the XAMPP Control Panel.

Open phpMyAdmin and create a database named:

```text
pos_system
```

### 4. Configure the environment

Copy the example environment file to `.env`.

On Windows PowerShell:

```powershell
Copy-Item env .env
```

Open `.env` and configure the application URL and database connection.

For a local development setup, the relevant settings are:

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = pos_system
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Use the MySQL username and password configured on your own computer. Do not commit `.env` or publish database credentials.

### 5. Run database migrations

Execute:

```bash
php spark migrate
```

This runs the project's available migrations to create the database tables.

### 6. Seed the product data

Run:

```bash
php spark db:seed ProductSeeder
```

This runs the existing product seeder.

**Initial staff account:** The repository currently contains a product seeder but does not include an initial staff-account seeder. A fresh database therefore needs an initial staff account provisioned securely before the login-protected management pages can be used. Do not use a shared or hardcoded production password.

### 7. Start the development server

Run:

```bash
php spark serve
```

Open the application at:

```text
http://localhost:8080/
```

Use the login page to access the POS system after an initial staff account has been provisioned.

## Database Overview

The application uses the following tables:

| Table | Purpose |
|---|---|
| `products` | Product details, prices, stock, images, and active status |
| `customers` | Customer names, email addresses, and phone numbers |
| `users` | Staff usernames, names, password hashes, and avatar filenames |
| `sales` | Product, customer, staff, quantity, unit price, and transaction date |
| `migrations` | Tracks completed database migrations |

The project uses the existing shared database schema. The `sales` table references `product_id`, `customer_id`, and `user_id`; the product inventory field is `stock`, and the sale price field is `unit_price`.

## Testing

The following functional tests were performed during development:

- Customer creation, editing, deletion, and invalid-email validation.
- Staff creation, editing, deletion, and avatar upload and replacement.
- Default avatar display for staff without a photo.
- Password hashing and login verification.
- Login and logout, including redirection from protected pages.
- Successful sale recording and automatic stock reduction.
- Rejection of a sale exceeding available stock.
- Display of recorded transactions in Sales History.

These are development test results, not a substitute for a complete automated test suite.

## Security Notes

- Keep `.env` and database credentials private.
- Use password hashing rather than storing plaintext passwords.
- Require authentication for management operations.
- Validate submitted form data on the server.
- Validate uploaded images and restrict permitted file types and sizes.
- Use secure database configuration for production.
- Do not expose development error pages or debug tools on a public deployment.

## Deployment

The application has been tested in a local XAMPP development environment.

A publicly accessible deployment still needs to be configured and verified before a hosted application link can be provided. Production deployment should use an appropriate PHP hosting environment, a configured database, secure environment variables, and the `public` directory as the web root.

## Project Repository

GitHub repository:

https://github.com/anedair/pos_system

## Development Team

This project was developed collaboratively for the IT0049 midterm project. The team divided the implementation into product management, customer management, staff management, authentication, sales transactions, inventory tracking, and sales history.

## License

This project is an academic submission. Refer to the course requirements and instructor guidance for its permitted use and distribution.
