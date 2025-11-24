<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<h1 align="center">INVENTARIS</h1>
<p align="center"><em>Empowering Innovation, Simplifying Asset Management Efficiently</em></p>

<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" /></a>
  <a href="#"><img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php&logoColor=white" /></a>
  <a href="#"><img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" /></a>
  <a href="#"><img src="https://img.shields.io/github/issues/chairyandwi/inventaris?style=flat-square" /></a>
  <a href="#"><img src="https://img.shields.io/github/stars/chairyandwi/inventaris?style=flat-square" /></a>
</p>

---

## 🧭 Table of Contents
- [Overview](#overview)
- [Core Features](#core-features)
- [Technology Stack](#technology-stack)
- [Getting Started](#getting-started)
- [Configuration](#configuration)
- [Usage](#usage)
- [License](#license)

---

## 📌 Overview
**Inventaris** is an advanced **Inventory & Asset Management System** built using **Laravel 12** and **PHP 8.3**.  
Designed to help organizations efficiently track, manage, and maintain assets using automation, structured workflows, and real-time insights.

Suitable for **campuses, offices, warehouses, industries, and government institutions**.

---

## ✨ Core Features
- 🔐 **RBAC (Role-Based Access Control)**
- 📦 **Asset & Inventory CRUD Modules**
- 🏷 **Barcode & QR Code Support**
- 📊 **Reporting (PDF, CSV, XLSX)**
- 📈 **Analytics Dashboard**
- 🛠 **Maintenance Scheduling & Logs**
- 🧩 **Reusable UI Components (TailwindCSS + Alpine.js)**

---

## 🧱 Technology Stack

| Layer    | Technology                        |
|----------|-----------------------------------|
| Backend  | Laravel 12.x, PHP 8.3             |
| Frontend | TailwindCSS 3, Alpine.js 3, Vite 7|
| Database | MySQL / PostgreSQL                |
| Tools    | Composer, NPM, Git                |
| Optional | Spatie Permissions, Laravel Excel |

> Laravel Version: **12.38.x**  
> PDF Generator: **barryvdh/laravel-dompdf v3.1.1**

---

## ⚙️ Getting Started

### Prerequisites
- PHP ≥ 8.2 (recommended 8.3)
- Composer
- Node.js & NPM
- MySQL / PostgreSQL
- Git

### Installation
```bash
git clone https://github.com/chairyandwi/inventaris.git
cd inventaris

composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate

```

## 🔧 Configuration

### Configure Database
edit file .env 
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<<database name>>
DB_USERNAME=<<username>>
DB_PASSWORD=<<password>>
```
### Run Migration & Seeders
```bash
php artisan migrate --seed
```
### Link Storage
```bash
php artisan storage:link
```

## ▶️ Usage
### Start Local Development Server 
```bash
php artisan serve
```
- New Terminal
```bash
npm run dev
```
### Login Credentials 
| Role     | Email                | Password     |
|----------|----------------------|--------------|
| Admin    | admin@example.com    | admin123     |
| Auditor  | auditor@example.com  | auditor123   |
| Staff    | staff@example.com    | staff123     |
| Borrower | borrower@example.com | borrower123  |

## 📄 License
MIT License

Copyright (c) 2025 Chairian

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

