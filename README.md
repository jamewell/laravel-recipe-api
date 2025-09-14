# Laravel Recipe API

A RESTful API for managing and sharing recipes, built with the Laravel framework. This project leverages modern PHP development practices and is optimized for local development using [DDEV](https://ddev.readthedocs.io/).

---

## Project Summary

The Laravel Recipe API provides endpoints for creating, reading, updating, and deleting recipes. It is designed for easy integration with frontend applications or mobile clients, supporting authentication, validation, and scalable architecture.

---

## DDEV Requirements

- [DDEV](https://ddev.readthedocs.io/en/stable/) v1.21.0 or newer
- Docker (see [DDEV Docker requirements](https://ddev.readthedocs.io/en/stable/#system-requirements))
- Composer (for managing PHP dependencies)
- Node.js & npm (for frontend asset compilation, if needed)

---

## Installation

1. **Clone the repository:**
   ```sh
   git clone https://github.com/your-username/laravel-recipe-api.git
   cd laravel-recipe-api
   ```
2. **Start DDEV:**
   ```sh
   ddev start
   ```
3. **Install PHP dependencies:**
    ```sh
    ddev composer install
    ```
4. **Copy environment file and generate app key:**
    ```sh
    ddev cp .env.example .env
    ddev php artisan key:generate
    ```
5. **Run database migrations:**
    ```sh
    ddev php artisan migrate --seed
    ```
6. **Access the application:**
    - API: http://localhost:8080 (or the URL provided by ddev describe)

## Usage
- Use API endpoints to manage recipes (see API documentation or routes).
- Authentication and authorization are handled via Laravel's built-in features.

