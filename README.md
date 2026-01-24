# FreshPress: Laundry Service Order and Management System

A laundry operations platform built with the TALL stack (Tailwind CSS, Alpine.js, Laravel, and Laravel Breeze). This application digitizes the entire laundry lifecycle—from intake and weight calculation to real-time progress tracking and delivery management.

---

## 📑 Table of Contents

* [Project Overview](https://www.google.com/search?q=%23project-overview)
* [Course Information](https://www.google.com/search?q=%23course-information)
* [Project Features](https://www.google.com/search?q=%23project-features)
* [Quick Start](https://www.google.com/search?q=%23quick-start)
* [Demo Credentials](https://www.google.com/search?q=%23demo-credentials)
* [Database Architecture](https://www.google.com/search?q=%23database-architecture)
* [API & Route structure](https://www.google.com/search?q=%23api--route-structure)
* [Code Organization](https://www.google.com/search?q=%23code-organization)

---

## 📋 Project Overview

The **FreshPress Laundry Management System** is an automated solution designed to streamline the operations of a laundry service provider. The project focuses on bridging the gap between physical walk-in transactions and digital customer engagement.

By utilizing a **Service-Oriented Architecture** within Laravel, the system automates background tasks such as account auto-provisioning for walk-in clients, real-time total price calculations, and the maintenance of an immutable audit trail for every garment processed.

---

## 🎓 Course Information

* **Subject**: [COMP 016 - Web Development]
* **Course/Year**: [BS Information Technology 3-2N]
* **Project Title**: FreshPress Laundry Order Management System

---

## ✨ Project Features

### 1. Automated Account Provisioning

When a staff member enters a walk-in order, the system automatically checks if the email exists. If not, it creates a "Shadow Account" with a hashed default password (`FreshPress123`), allowing the customer to log in later and track their items.

### 2. 6-Stage Operational Protocol

The system tracks the lifecycle of every order through six distinct stages:

* **Pending → Washing → Drying → Folding → Ironing → Ready**

### 3. Automated Audit Trail (MySQL Triggers)

To ensure 100% accountability, a **MySQL Trigger** is implemented on the database level. Every time the laundry stage is updated, a record is automatically inserted into the `laundry_status_audits` table, creating a timestamped history.

### 4. Reactive Pricing Engine

Utilizing **Alpine.js**, the booking form calculates totals in real-time on the client side. It supports:

* **Weight-based Pricing** (PHP per KG)
* **Item-based Pricing** (PHP per Piece)
* **Premium Add-ons** (Multiple modifiers per service)

### 5. Role-Based Access Control (RBAC)

Using the Spatie Permission package, the application enforces the **Principle of Least Privilege**:

* **ADMIN**: Full control over financials, staff permissions, and service definitions.
* **STAFF**: Focused on operational updates (Laundry progress, Logistics). Restricted from system settings.
* **CUSTOMER**: Restricted strictly to viewing and tracking their own service records.

---

## ⚡ Quick Start

## I. System Requirements

Before installation, ensure your environment meets the following:

* **PHP:** 8.2 or higher
* **Composer:** Latest version
* **Node.js & NPM:** For frontend asset compilation
* **Local Server:** Laragon (Recommended) or XAMPP
* **Database:** MySQL 8.0 or MariaDB

## II. Installation Steps
### 1. Obtain the Source Code

**Option A: Via GitHub (Recommended)**

```bash
git clone https://github.com/mariel-ogabar/FRESH-PRESS-LAUNDRY-SERVICE.git

```

**Option B: Via Google Drive / ZIP**
Extract the files into your local server's root directory:

* **Laragon:** `C:\laragon\www\FreshPress`
* **XAMPP:** `C:\xampp\htdocs\FreshPress`

### 2. Install Dependencies

Open your terminal (or Laragon Terminal) inside the project folder:

```bash
# Install PHP dependencies
composer install

# Install Frontend dependencies
npm install
npm run build

```

### 3. Configure Environment Variables

Copy the example environment file and generate a unique application key:

```bash
cp .env.example .env
php artisan key:generate

```

### 4. Database Setup

#### Using Laragon:

1. Click **"Start All"** on the Laragon dashboard.
2. Right-click anywhere in Laragon > **MySQL** > **Create new database** > name it `freshpress_db`.

#### Using XAMPP:

1. Open XAMPP Control Panel and start **Apache** and **MySQL**.
2. Go to `http://localhost/phpmyadmin`.
3. Create a new database named `freshpress_db`.

#### Update .env File:

Open the `.env` file in your project root and ensure the credentials match your server:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=freshpress_db
DB_USERNAME=root
DB_PASSWORD=         # Leave empty for XAMPP/Laragon default, or enter your password

```

### 5. Run Migrations and Seeders

This command builds the database schema and populates the roles, permissions, and initial services:

```bash
php artisan migrate --seed

```

## IV. Running the Application

To start the local development server:

```bash
php artisan serve

```
---

## 🔐 Database Seeder & Dummy Data

The system uses a multi-layered seeding strategy to initialize the application. Running the seeder creates the necessary roles, core services, administrative users, and a pre-populated history of laundry orders.

### 1. Default Access Credentials

Use these accounts to test the **Role-Based Access Control (RBAC)** and dashboard redirections:

| Role | Email | Password | Access Level |
| --- | --- | --- | --- |
| **Administrator** | `admin@freshpress.com` | `password123` | Full System & Financial Oversight |
| **Staff Member** | `staff@freshpress.com` | `password` | Operational Updates & Logistics |
| **Test Customer** | `customer@freshpress.com` | `password` | Personal Service Protocol Tracking |

### 2. Operational Dummy Data (14 Total Orders)

Upon seeding, the `OrderLifecycleSeeder` generates **14 simulated orders** distributed across different operational stages. This allows for immediate visualization of dashboard statistics and audit trails:

| Laundry Stage | Order Count | Payment Status | Purpose |
| --- | --- | --- | --- |
| **READY** | 5 | PAID | Demonstrates completed sales metrics |
| **WASHING** | 3 | PENDING | Showcases active machine utilization |
| **PENDING** | 4 | PENDING | Demonstrates new intake queue |
| **IRONING** | 2 | PAID | Shows high-value finishing stages |

### 3. Core Service Catalog

The seeder also initializes the following base services and add-ons:

* **Main Services:** Basic Wash & Fold (₱150/kg), Dry Cleaning (₱350/pcs), Stain Removal.
* **Add-ons:** Express Service, Delicate Care, Ironing Service.

### 🛠 How to Reset and Seed

To wipe existing data and apply this fresh operational state, run:

```bash
php artisan migrate:fresh --seed

```
---

## 📂 Database Architecture

The system uses a highly normalized relational schema to ensure data integrity and security.

### 👤 Identity & Security

* **`users`**: Core profile and credential data.
* **`roles` & `permissions**`: Spatie implementation for Role-Based Access Control.
* **`model_has_roles`**: Junction table mapping users to their specific system roles.

### 📦 Transactional & Operations

* **`orders`**: Master record aggregating total price and order status.
* **`main_services` & `add_ons**`: The service catalog and pricing definitions.
* **`order_services` & `order_addons**`: Junction tables handling many-to-many relationships for customized orders.
* **`laundry_status`**: Real-time tracking of the current processing phase.
* **`laundry_status_audits`**: Immutable historical logs generated by **MySQL Triggers** for accountability.

---

## 🔗 Entity-Relationship Summary

### 🏛️ Core Logic

* **User ↔ Order** (1:M): One user can have multiple transactions.
* **Order ↔ (Status, Payment, Logistics)** (1:1): Every order has one current status, one payment record, one collection, and one delivery schedule.

### 🛍️ Pricing Logic

* **Order ↔ Order_Service** (1:M): An order can contain multiple service types (e.g., Wash & Fold + Dry Cleaning).
* **Order_Service ↔ Order_Addon** (1:M): Premium modifiers (e.g., Fabric Softener) are linked to specific services within an order.

### ⚙️ Backend Automation

* **Trigger (`trg_LaundryStatus_Audit`)**: Automatically creates an entry in `laundry_status_audits` whenever `laundry_status` is updated, ensuring a permanent "Chain of Custody."

---

## 🎨 Design

### System Architecture

```text
The system follows a standard Laravel MVC architecture with an added Service Layer.
Client (Browser) -> Routes (web.php, auth.php)
    -> Controllers (Admin/Staff/OrderController)
    -> Services (OrderProcessingService)
    -> Models / Eloquent (User, Order, OrderService, MainService, AddOn, LaundryStatus, Collection, Delivery, Payment)
    -> MySQL (Migrations & Triggers)

```

### Entity Relationship Summary

```text
freshpress-laundry/relationships
│
├── users/
│   ├── hasMany -> orders
│   └── spatieRBAC -> roles/permissions
│
├── orders/
│   ├── belongsTo -> user
│   ├── hasOne -> laundry_status
│   ├── hasOne -> collection
│   ├── hasOne -> delivery
│   ├── hasOne -> payment
│   ├── hasMany -> order_services
│   └── hasMany -> laundry_status_audits
│
├── order_services/
│   ├── belongsTo -> order
│   ├── belongsTo -> main_service
│   └── belongsToMany -> add_ons (via order_addons)
│
└── laundry_status_audits/
    └── fields: order_id, old_status, new_status, changed_at

```

### Typical Workflow

```text
1. Intake: Customer/Staff creates an order via POST /orders. System auto-provisions user if email is new.
2. Collection: Staff updates intake via /orders/{id}/collection; status moves to RECEIVED.
3. Processing: Staff updates laundry protocol stages via /orders/{id}/status; MySQL Trigger logs history.
4. Logistics: Staff sets return schedule via /orders/{id}/set-schedule and updates /orders/{id}/delivery.
5. Settlement: Admin processes financial closure via /orders/{id}/payment using DB Stored Procedure.
6. Completion: Once delivered and paid, the system marks the protocol as COMPLETED.

```
---

### **Key Relationships**

| Key | Talks To | Purpose | Example / Route |
| --- | --- | --- | --- |
| **Route** | Middleware & Controller | Maps operational URLs to the correct handler based on staff or customer role. | `/orders/{id}/status` → `OrderController@updateStatus` |
| **Middleware** | Route & Controller | Enforces boutique security: validates session (`auth`) and role-based permissions (`role:ADMIN`, `permission:manage staff`). | `role_or_permission:ADMIN|manage staff` |
| **Controller** | Model, Service & View | Acts as the traffic cop: validates intake, triggers the **Service Layer** for math, and returns the operational dashboard. | `Admin\StaffController@update` |
| **Service** | Model & Request | The "Heavy Lifter": handles complex logic like **Auto-Provisioning** walk-in accounts and multi-factor pricing calculations. | `OrderProcessingService@getOrCreateCustomer` |
| **Model** | Database & View | Manages data logic: defines the **6-Stage Protocol**, applies **Global Security Scopes**, and interacts with tables (orders, services, audits). | `Order::withoutGlobalScope(...)` |
| **View** | Controller | Renders high-fidelity HTML using Blade; uses reusable components for real-time tracking progress and stat cards. | `resources/views/dashboard/admin_staff.blade.php` |

---

## 🏗 Code Structure & Organization

The system follows a **Service-Oriented Architecture (SOA)**, keeping controllers lean by moving complex business rules to dedicated layers.

### 📂 Logic & Business Rules

* **`app/Services/OrderProcessingService.php`**: The core engine of the app. It handles **Auto-Provisioning** (creating walk-in accounts) and **Dynamic Pricing** (KG vs. Piece-based math).
* **`app/Http/Requests`**: Specialized **FormRequests** manage validation. Logic is role-aware, ensuring data requirements change based on who is placing the order.

### 🛡️ Security & Authorization

* **`app/Http/Controllers/Admin/StaffController.php`**: Implements **Role-Based Access Control (RBAC)**. It manages permissions and forces a cache clear (`forgetCachedPermissions`) to apply security updates instantly.
* **`app/Models/Order.php`**: Features a **Global Security Scope**. This is a database-level filter that automatically restricts customers to viewing only their own service records.

### 🎨 Presentation & UI

* **`resources/views/components`**: A library of reusable **Blade Components**. This ensures a consistent "FreshPress" aesthetic for badges, buttons, and stat cards across all portals.
* **Reactive UI**: Uses **Alpine.js** to handle front-end logic, such as real-time total price calculation and modal state management.

### ⚡ Database Automation

* **MySQL Triggers**: The `trg_LaundryStatus_Audit` trigger handles the **Audit Trail** on the database side, ensuring an unalterable history of laundry progress.
* **Stored Procedures**: `sp_ProcessPayment` executes multi-table financial updates as a single atomic transaction.
---

## 📂 Project Directory Structure

The project follows the standard Laravel 12 structure, enhanced with a Service Layer and dedicated Administrative modules.

```text
FreshPress-Laundry/
├── app/
│   ├── Http/
│   │   ├── Controllers/             # Lean controllers handling request flow
│   │   │   ├── Admin/               # Administrative logic (Staff, Services)
│   │   │   ├── Auth/                # Authentication logic (Laravel Breeze)
│   │   │   └── OrderController.php  # Core operational request handler
│   │   ├── Middleware/              # Route filters and RBAC guards
│   │   └── Requests/                # Form validation logic (e.g., StoreOrderRequest)
│   ├── Models/                      # Eloquent models (includes Global Security Scopes)
│   ├── Policies/                    # Authorization logic for specific models
│   ├── Providers/                   # System booting and service registration
│   └── Services/                    # Core business logic layer (Pricing & Onboarding)
├── bootstrap/                       # Framework initialization and app configuration
├── config/                          # Application-wide configuration files
├── database/
│   ├── factories/                   # Model factories for dummy data generation
│   ├── migrations/                  # Schema definitions & MySQL Trigger scripts
│   └── seeders/                     # Initial data and operational dummy records
├── public/                          # Publicly accessible assets and entry point
├── resources/
│   ├── css/                         # Tailwind CSS source files
│   ├── js/                          # Alpine.js scripts and frontend logic
│   └── views/                       # Blade templates
│       ├── admin/                   # Staff and Service management portals
│       ├── components/              # Atomic reusable UI components
│       ├── dashboard/               # Role-based dashboard layouts
│       └── layouts/                 # Master app and navigation templates
├── routes/
│   ├── auth.php                     # Authentication-specific routes
│   ├── console.php                  # CLI-based artisan commands
│   └── web.php                      # Main application web routes
├── storage/                         # Generated logs, cache, and file uploads
├── tests/                           # Unit and Feature test suites
├── vendor/                          # Third-party dependencies (managed by Composer)
├── .env                             # Environment-specific configuration
├── composer.json                    # Backend dependency manifest
├── package.json                     # Frontend dependency manifest
└── vite.config.js                   # Asset compilation configuration

```
---
Here is the **API Routes & Endpoints** documentation for the FreshPress Laundry Service App. This table details the available endpoints, the HTTP methods used, and the specific permissions required to access them.

---

### **Core API Endpoints**

| Method | Endpoint | Controller Action | Access / Permission | Description |
| --- | --- | --- | --- | --- |
| `GET` | `/dashboard` | `DashboardController@index` | `auth` | Fetches role-based order statistics and data tables. |
| `POST` | `/orders` | `OrderController@store` | `create orders` | Creates a new order; triggers user auto-provisioning if the email is new. |
| `PATCH` | `/orders/{id}/status` | `OrderController@updateStatus` | `update order status` | Updates the laundry protocol stage (Washing, Drying, etc.). |
| `PATCH` | `/orders/{id}/collection` | `OrderController@updateCollection` | `update order status` | Marks laundry as `RECEIVED` or `PENDING` drop-off. |
| `PATCH` | `/orders/{id}/delivery` | `OrderController@updateDelivery` | `update order status` | Updates return status and sets order to `COMPLETED` if delivered. |
| `PATCH` | `/orders/{id}/payment` | `OrderController@updatePayment` | `process payments` | Updates financial status (PAID/PENDING). |
| `PATCH` | `/orders/{id}/cancel` | `OrderController@cancel` | `cancel any order` | Soft-deletes the order and updates status to `CANCELLED`. |
| `PATCH` | `/admin/staff/{id}` | `Admin\StaffController@update` | `manage staff` | Updates staff roles and granular system permissions. |
| `PATCH` | `/admin/services/{id}/toggle` | `Admin\ServiceController@toggleService` | `manage services` | Enables or disables a service from public view. |

---

### **Request Lifecycle & Response Design**

The endpoints follow a modern **RESTful design**, primarily using `PATCH` for state transitions to ensure that only the intended data (like a status change) is modified.

* **Validation**: Every `POST` and `PATCH` request is funneled through specialized **FormRequest** classes (e.g., `UpdateStatusRequest`). If validation fails, the API returns a `422 Unprocessable Entity` response with specific error messages.
* **Authorization**: Access is enforced using **Laravel Policies** and **Spatie Middleware**. If a user lacks the correct permission (e.g., a Customer trying to update a payment), a `403 Forbidden` response is returned.
* **Atomic Transactions**: Critical endpoints like `/orders` (Store) are wrapped in `DB::transaction`. This ensures that if the server fails while creating the `order_services` or `payments` records, the entire transaction is rolled back, preventing "orphaned" data.

---

### **Front-End Integration**

The dashboard utilizes **Alpine.js** to talk to these endpoints via the `fetch()` API. This allows the staff to update a laundry stage from a dropdown menu and receive an immediate "Success" notification without the page reloading, maintaining a high-fidelity user experience.

---

### API Routes & Endpoints

| Method | Endpoint | Controller Action | Access / Permission |
| --- | --- | --- | --- |
| **GET** | `/services` | `ServiceController@publicIndex` | Public |
| **GET** | `/dashboard` | `DashboardController@index` | Authenticated |
| **GET** | `/profile` | `ProfileController@edit` | Authenticated |
| **PATCH** | `/profile` | `ProfileController@update` | Authenticated |
| **GET** | `/orders/create` | `OrderController@create` | `ADMIN` or `CUSTOMER` |
| **POST** | `/orders` | `OrderController@store` | `ADMIN` or `CUSTOMER` |
| **GET** | `/orders/{id}` | `OrderController@show` | Authenticated |
| **PATCH** | `/orders/{id}/cancel` | `OrderController@cancel` | `ADMIN` or `CUSTOMER` |
| **PATCH** | `/orders/{id}/collection` | `OrderController@updateCollection` | `update order status` |
| **PATCH** | `/orders/{id}/status` | `OrderController@updateStatus` | `update order status` |
| **PATCH** | `/orders/{id}/delivery` | `OrderController@updateDelivery` | `update order status` |
| **PATCH** | `/orders/{id}/set-schedule` | `OrderController@setDeliverySchedule` | `update order status` |
| **PATCH** | `/orders/{id}/payment` | `OrderController@updatePayment` | `ADMIN` or `process payments` |
| **GET** | `/admin/staff` | `Admin\StaffController@index` | `ADMIN` or `manage staff` |
| **POST** | `/admin/staff` | `Admin\StaffController@store` | `ADMIN` or `manage staff` |
| **PATCH** | `/admin/staff/{staff}` | `Admin\StaffController@update` | `ADMIN` or `manage staff` |
| **DELETE** | `/admin/staff/{staff}` | `Admin\StaffController@destroy` | `ADMIN` or `manage staff` |
| **GET** | `/admin/services` | `Admin\ServiceController@index` | `ADMIN` or `manage services` |
| **POST** | `/admin/services` | `Admin\ServiceController@storeService` | `ADMIN` or `manage services` |
| **PATCH** | `/admin/services/{id}/toggle` | `Admin\ServiceController@toggleService` | `ADMIN` or `manage services` |
| **POST** | `/admin/addons` | `Admin\ServiceController@storeAddon` | `ADMIN` or `manage services` |
| **POST** | `/orders/{id}/pay` | `DB Stored Procedure` | `role:ADMIN` |
| **DELETE** | `/orders/{id}` | `OrderController@destroy` | `role:ADMIN` |

