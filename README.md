<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Online Marketplace Platform

## Overview
This is an online marketplace platform built using Laravel, allowing users to buy and sell various products. The platform supports user authentication using Sanctum, product management, order processing, and product image uploads.

## Features
- **User Authentication**: Secure user login and registration using Laravel Sanctum.
- **Multi-device Authentication**: Users can authenticate from multiple devices simultaneously.
- **Logout from All Devices**: Users can log out from all devices.
- **Product Management**: Add products.
- **Order Processing**: Basic order management.
- **Image Uploads**: Secure image upload for product images.

## Getting Started

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL

### Installation


1. **Install PHP dependencies**
    ```bash
    composer install
    ```


2. **Set up environment variables**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

3. **Configure the database**
    - Update the `.env` file with your database credentials.

4. **Run migrations**
    ```bash
    php artisan migrate
    ```

5. **Start the development server**
    ```bash
    php artisan serve
    ```

## Usage

### API Endpoints
- **User Authentication**
    - Register: `POST /api/user/register`
    - Login: `POST /api/user/login`
    - Logout: `POST /api/user/logout`
    - Logout from all devices: `POST /api/logout-from-all-devices`

- **Product Management**
    - Create Product: `POST /api/product/add`
    - List Products: `GET /api/user/products`




- **Order Processing**
    - Place Order: `POST /api/order/create`
    - View Orders: `GET /api/user/orders`
    - View Orders Details: `GET /api/user/order/details`





Thank you.
