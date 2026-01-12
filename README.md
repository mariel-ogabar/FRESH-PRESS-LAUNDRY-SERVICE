# FreshPress Laundry Management System (v1)

FreshPress is a comprehensive, service-oriented Laundry Management System built on **Laravel 12**. It is designed to bridge the gap between physical walk-in laundry operations and a digital customer tracking experience.

## 🚀 Core Features

* **Walk-in Order Management:** Staff can create orders for customers using just an email. The system automatically provisions a digital account for new customers.
* **Automated Pricing Engine:** Real-time calculation based on service types (Per KG vs. Per Item) and auxiliary add-ons.
* **Live Status Tracking:** Customers can track their laundry progress (Washing, Drying, Ironing, etc.) through a reactive timeline.
* **Database-Level Auditing:** Uses MySQL Triggers to ensure a 100% tamper-proof history of every status change.
* **Role-Based Access Control (RBAC):** Granular permissions for Admin, Staff, and Customers using Spatie.
* **Data Privacy Scopes:** Built-in global security scopes that ensure customers can never view orders belonging to others at the database driver level.

---

## 🛠 Tech Stack

* **Framework:** Laravel 12
* **Language:** PHP 8.2+
* **Database:** MySQL 8.0+ (With Native Triggers)
* **Frontend:** Tailwind CSS, Alpine.js (via Laravel Breeze)
* **Security:** Spatie Laravel-Permission, Laravel Sanctum

---

## 📂 Project Structure

```text
app/
├── Http/Controllers/   # Request handling & UI orchestration
├── Http/Requests/      # Rigorous input validation & sanitization
├── Models/             # Eloquent entities with Global Security Scopes
├── Providers/          # Global system settings & RBAC Master Key
└── Services/           # Complex Business Logic (Pricing & Identity)
database/
├── migrations/         # Schema definitions & MySQL Triggers
└── seeders/            # Roles, Permissions, and Master Data

```

---

## ⚙️ Installation Guide

### 1. Prerequisites

Ensure you have the following installed:

* PHP 8.2+
* Composer
* Node.js & NPM
* MySQL

### 2. Clone and Setup

```bash
# Clone the repository
git clone https://github.com/your-username/freshpress-v1.git
cd freshpress-v1

# Install Backend Dependencies
composer install

# Install Frontend Dependencies
npm install

```

### 3. Environment Configuration

Copy the example environment file and generate the app key:

```bash
cp .env.example .env
php artisan key:generate

```

*Configure your `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in the `.env` file.*

### 4. Database Initialization

This will create the tables, inject the MySQL Triggers, and seed the Roles/Permissions:

```bash
php artisan migrate --seed

```

### 5. Launch

```bash
# Compile assets
npm run dev

# Start the local server
php artisan serve

```

---

## 🔒 Security Architecture

FreshPress implements a **"Security-by-Default"** model:

1. **Gate Bypass:** The `AppServiceProvider` contains a `Gate::before` rule allowing the `ADMIN` role to bypass all permission checks to prevent lockouts.
2. **Global Scopes:** The `Order` model automatically filters queries so `CUSTOMERS` only see their own records.
3. **Atomic Transactions:** All order creations are wrapped in `DB::transaction` to ensure data integrity across multiple tables (Payments, Status, Logistics).

---

## 📊 Database Triggers

The system utilizes a native MySQL trigger `trg_LaundryStatus_Audit`. This ensures that even if a record is updated via a database terminal (bypassing the PHP application), a historical log of the status change is still generated in the `laundry_status_audits` table.

---

## 🤝 Contributing

For development, please ensure that you run `npm run dev` to compile Tailwind styles and Alpine.js logic. All business logic changes should be made within the `app/Services` layer rather than the Controllers.

---

## 📄 License

This project is licensed under the MIT License.