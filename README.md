<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<h1 align="center">INVENTARIS</h1>
<p align="center"><em>Empowering Innovation, Simplifying Asset Management Efficiently</em></p>

<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" /></a>
  <a href="#"><img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=flat-square&logo=php&logoColor=white" /></a>
  <a href="#"><img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" /></a>
  <a href="#"><img src="https://img.shields.io/github/issues/username/inventaris?style=flat-square" /></a>
  <a href="#"><img src="https://img.shields.io/github/stars/username/inventaris?style=flat-square" /></a>
</p>

---

## 🧭 Table of Contents
- [Overview](#overview)
- [Core Features](#core-features)
- [Technology Stack](#technology-stack)
- [Getting Started](#getting-started)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

---

## 📌 Overview
**Inventaris** is an integrated **Inventory & Asset Management System** built with **Laravel 11**.  
The platform is designed to help organizations monitor, track, and maintain assets efficiently through automation, structured data processing, and real-time visibility.

This system is ideal for:
- Educational institutions  
- Offices & corporate environments  
- Government & public service units  
- Warehouses & production facilities  

---

## ✨ Core Features

### 🔐 Role-Based Access Control (RBAC)
Granular permission management for:
- **Admin** – full system access  
- **Staff/Operator** – asset/data entry  
- **Auditor** – review & reporting  

### 📦 Asset & Inventory Management
- CRUD for categories, assets, and inventory items  
- Status tracking (active, maintenance, broken, disposed)  
- Barcode/QR Code support  
- Import/export via Excel/CSV  

### 📊 Reporting & Analytics
- Real-time dashboard (TailwindCSS + Alpine.js)  
- Exportable reports (PDF, CSV, XLSX)  
- Audit logs & activity tracking  

### 🛠 Maintenance & Monitoring
- Scheduled maintenance reminders  
- Asset lifecycle history  
- Notification system ready (email/telegram webhook optional)  

### 🎨 Customizable UI Components
- Reusable form & table components  
- Fully responsive mobile-first design  

---

## 🧱 Technology Stack

| Layer | Technology |
|------|------------|
| Backend | Laravel 11, PHP 8.2 |
| Frontend | TailwindCSS, Alpine.js, Vite |
| Database | MySQL / PostgreSQL |
| Tools | Composer, NPM, Git |
| Optional | Laravel Socialite, Spatie Permissions, Laravel Excel |

---

## ⚙️ Getting Started

### Prerequisites
Pastikan perangkat Anda memiliki:
- **PHP ≥ 8.2**
- **Composer (latest)**
- **Node.js & NPM**
- **MySQL / PostgreSQL**
- **Git**

---

## 🚀 Installation

```bash
# Clone the repository
git clone https://github.com/username/inventaris.git

# Move into project directory
cd inventaris

# Install backend dependencies
composer install

# Install frontend dependencies
npm install
npm run build

# Setup environment file
cp .env.example .env

# Generate application key
php artisan key:generate
