# Laravel Travels & Orders API

This project is a Laravel API that uses JWT authentication to handle travels and orders. The API is containerized using Docker and Docker Compose, and includes MailHog as an email catcher so that outgoing emails are intercepted and not sent to a real SMTP server.

## Table of Contents

- [Getting Started](#getting-started)
- [Docker Setup](#docker-setup)
- [Environment Variables](#environment-variables)
- [Routes](#routes)
- [Usage Examples](#usage-examples)
- [Email Catcher](#email-catcher)
- [Additional Information](#additional-information)

## Getting Started

Clone the repository and install the PHP dependencies:

```bash
git clone https://github.com/yourusername/your-laravel-project.git
cd your-laravel-project
composer install
```

## Docker Setup

This project includes a Dockerfile and docker-compose.yml for containerization. To start the project, run:

```bash
docker-compose up --build
```

- The API will be available at http://localhost:8000
- The PHP container uses port 9000 internally, and Nginx serves the app on port 8000.
- MySQL is available on port 3306.
- MailHog is available on port 8025 (web interface) and port 1025 (SMTP).

## Environment Variables

Ensure your .env file is properly set. Below is an example configuration:

```
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=example@example.com
MAIL_FROM_NAME="Laravel App"

JWT_SECRET=your_jwt_secret_here
```

## Routes

Below is the main routes table for the API:

| HTTP Method | URI | Controller Action | Middleware | Description |
|------------|-----|------------------|------------|-------------|
| POST | /login | AuthController@login | - | Authenticates user and returns a JWT token. |
| POST | /register | AuthController@register | - | Registers a new user and returns a JWT token. |
| GET | /orders | OrdersController@index | JwtMiddleware | Lists all orders (JWT required). |
| POST | /orders | OrdersController@store | JwtMiddleware | Creates a new order (JWT required). |
| GET | /orders/{order} | OrdersController@show | JwtMiddleware | Shows a specific order (JWT required). |
| PUT/PATCH | /orders/{order} | OrdersController@update | JwtMiddleware | Updates an order (JWT required). |
| DELETE | /orders/{order} | OrdersController@destroy | JwtMiddleware | Deletes an order (JWT required). |
| PATCH | /orders/{order} | OrdersController@updateStatus | JwtMiddleware | Updates the status of an order (JWT required). |
| GET | /travels | TravelsController@index | JwtMiddleware | Lists all travels (JWT required). |
| POST | /travels | TravelsController@store | JwtMiddleware | Creates a new travel record (JWT required). |
| GET | /travels/{travel} | TravelsController@show | JwtMiddleware | Shows a specific travel (JWT required). |
| PUT/PATCH | /travels/{travel} | TravelsController@update | JwtMiddleware | Updates a travel record (JWT required). |
| DELETE | /travels/{travel} | TravelsController@destroy | JwtMiddleware | Deletes a travel record (JWT required). |

## Usage Examples

### User Authentication

**Login Example:**
```bash
curl -X POST http://localhost:8000/api/login \
     -H "Content-Type: application/json" \
     -d '{
        "email": "user@example.com",
        "password": "password123"
     }'
```

**Register Example:**
```bash
curl -X POST http://localhost:8000/api/register \
     -H "Content-Type: application/json" \
     -d '{
        "email": "john.doe@example.com",
        "password": "secret123",
        "password_confirmation": "secret123"
     }'
```

### Accessing Protected Routes

After authentication, include the JWT token as a Bearer token in the Authorization header.

**Get Orders:**
```bash
curl -X GET http://localhost:8000/api/orders \
     -H "Authorization: Bearer YOUR_JWT_TOKEN" \
     -H "Accept: application/json"
```

**Update Order Status:**
```bash
curl -X PATCH http://localhost:8000/api/orders/ORDER_ID \
     -H "Authorization: Bearer YOUR_JWT_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{"status": "completed"}'
```

**Travel Endpoints Example:**
```bash
# List travels
curl -X GET http://localhost:8000/api/travels \
     -H "Authorization: Bearer YOUR_JWT_TOKEN" \
     -H "Accept: application/json"

# Create travel (automatically creates associated order)
curl -X POST http://localhost:8000/api/travels \
     -H "Authorization: Bearer YOUR_JWT_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{"destination": "Paris", "departure_date": "2025-05-01", "return_date": "2025-05-10"}'
```

## Email Catcher

This project uses MailHog to catch outgoing emails. With the Docker setup provided, MailHog will run on:

- SMTP Host: mailhog
- SMTP Port: 1025
- Web Interface: http://localhost:8025

Ensure your .env file has the following mail settings so that Laravel sends emails through MailHog:

```
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=example@example.com
MAIL_FROM_NAME="Laravel App"
```

To test email sending (for example, during password resets or notifications), trigger an email in your application and then check the MailHog web interface at http://localhost:8025.

## Additional Information

- **JWT Middleware**: The custom middleware JwtMiddleware protects routes that require user authentication.
- **Polymorphic Relations**: The Travels model is related polymorphically with Orders. This implementation allows, for example, updating the order status via travel endpoints.
- **Testing**: The repository includes feature tests (using Pest or PHPUnit) demonstrating login, registration, and CRUD operations.
