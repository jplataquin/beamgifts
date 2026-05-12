# Beam Gifts - Project Documentation

Beam Gifts is a digital gift-selling platform where gifters can purchase QR-code vouchers for products from local stores and send them to recipients via a shareable link.

## 🚀 Tech Stack
- **Framework:** Laravel 12 (PHP 8.2)
- **Frontend:** Bootstrap 5.3.8, Sass, Vite, JavaScript (AJAX/Fetch)
- **Database:** MySQL
- **Payments:** HitPay Integration (PHP Currency)
- **Utilities:** `simplesoftwareio/simple-qrcode`, `html5-qrcode` (Scanner)

## 🏗️ Core Architecture

### 🔐 Multi-Guard Authentication
The system uses three distinct authentication guards to enforce strict isolation:
1. **Admin (`admins` table):** Full platform control.
2. **Partner (`partners` table):** For store owners to manage stores, products, and redemption.
3. **Gifter (`gifters` table):** Public users who browse and buy gifts.

### 🏙️ City-Scoped Browsing
The application uses URL-prefixed routing (e.g., `/{city_slug}/...`) to maintain context.
- **Middleware (`SetCityContext`):** Extracts the city from the URL and shares it globally via `app('current_city')`.
- **Global Scoping:** Products, stores, and carts are automatically filtered based on the active city.

### 📁 Robust File Uploads
All file uploads (Product images and Personalization photos) use a custom **Chunked Upload** mechanism:
- **Frontend:** JavaScript slices files into 1MB chunks and sends them via Fetch.
- **Backend:** Atomic locks prevent race conditions, and chunks are reassembled using the `Storage` facade for cross-version compatibility.

## 🎁 Features & Workflows

### Gifter Experience
- **Shopping Cart:** AJAX-powered, city-specific cart with a live badge counter.
- **Personalization:** Add a private message and a custom photo to any purchased gift.
- **Unwrap Experience:** A delightful interactive reveal animation for the gift recipient.
- **History:** Comprehensive order and gift management dashboard.

### Partner Portal
- **Management:** CRUD for stores and branches (assigned to specific cities).
- **Products:** Manage catalog with categorized groupings.
- **Redemption:** Live QR scanner to instantly validate and claim vouchers.

### Admin Portal
- **Control:** Create partner accounts, manage active cities, and define global categories.
- **Oversight:** Edit or ban any product/partner and manage site-wide price markups.
- **Dynamic Content:** Edit About, Terms, and Privacy pages directly from the settings dashboard.

## 🛠️ Local Development & Testing

### Credentials
- **Admin:** `admin@beamgifts.com` / `password`
- **Partner (Sample):** `partner@example.com` / `password`
- **Gifter (Sample):** `test@example.com` / `password`

### Checkout Simulation
Because HitPay webhooks cannot reach `localhost`, a **Local Bypass** is implemented in `CheckoutController`. In local environments, returning from a successful HitPay redirect will automatically trigger the voucher generation and mark the order as `paid`.

### Commands
- **Seed Data:** `php artisan db:seed --class=DatabaseSeeder` (Sequentially seeds all roles and sample data).
- **Expire Vouchers:** `php artisan vouchers:expire` (Scheduled to run hourly).
- **Storage Link:** `php artisan storage:link` (Mandatory for photo previews).

---
*Created by Gemini CLI - May 10, 2026*
