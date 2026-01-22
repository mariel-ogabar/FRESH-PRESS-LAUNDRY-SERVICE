# FreshPress: Laundry Service Order and Management System

**Course Project:** COMP 016 - Web Development (Final Project)

**Framework:** Laravel 12.x

**Language:** PHP 8.2+

## I. Project Overview

FreshPress is a web-based management system designed to digitize the operations of local laundry businesses. It provides a centralized platform for handling customer registration, service selection, automated pricing, order tracking, and payment processing. The system supports both online customer bookings and staff-managed walk-in transactions.

## II. System Requirements

Before installation, ensure your environment meets the following:

* **PHP:** 8.2 or higher
* **Composer:** Latest version
* **Node.js & NPM:** For frontend asset compilation
* **Local Server:** Laragon (Recommended) or XAMPP
* **Database:** MySQL 8.0 or MariaDB

## III. Installation and Setup

### 1. Obtain the Source Code

**Option A: Via GitHub (Recommended)**

```bash
git clone https://github.com/YourUsername/FreshPress.git
cd FreshPress

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

## IV. Database Schema and Dump

A complete SQL dump is included in the root folder of this project named `freshpress_db.sql`.

**To import the dump manually:**

1. Go to **phpMyAdmin** or **HeidiSQL**.
2. Select the `freshpress_db` database.
3. Click the **Import** tab and select the `freshpress_db.sql` file.

## V. Running the Application

To start the local development server:

```bash
php artisan serve

```

Access the application via your browser at: `http://127.0.0.1:8000`

## VI. Default Credentials

For testing purposes, use the following pre-seeded accounts:

| Role | Email | Password |
| --- | --- | --- |
| **Administrator** | `admin@freshpress.com` | `password` |
| **New Walk-in User** | *(Automatically created)* | `FreshPress123` |

## VII. Submission Folder Structure

The Google Drive folder for this project contains:

1. **/FreshPress Source:** The complete Laravel project files (including `.git` if using GitHub).
2. **README.md:** This installation guide.
3. **freshpress_db.sql:** The MySQL database export.
4. **/Documentation:** The final project report and UI screenshots.

