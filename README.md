# 🎉 Laravel Event Management API

A RESTful API built with Laravel for managing events, attendees, and notifications — including user registration, authentication, password reset, and scheduled event reminders.

---

## 🚀 Features

- User registration & login via API tokens
- Event creation, updating, and deletion
- Attend/unattend events
- Password reset with email notification
- Scheduled event reminder notifications (queued)
- Laravel Sanctum-based API authentication

---

## ⚙️ Installation

### 1. Clone the repository

```bash
git clone https://github.com/mechetenco/event-management-API.git
cd event-management-api
```

### 2. Install dependencies

```bash
composer install
cp .env.example .env
php artisan key:generate
```

### 3. Configure `.env`

Update the following in `.env`:

```env
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=app@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Run migrations and seeders

```bash
php artisan migrate
php artisan db:seed
```

### 5. Serve the application

```bash
php artisan serve
```

---

## 🔑 Authentication

This API uses **Laravel Sanctum** for token-based authentication.

- After login, the client receives a token to include in all authenticated requests:

```http
Authorization: Bearer {token}
```

---

## 📬 Password Reset

- Endpoint: `POST /api/forgot-password`
- Laravel sends a reset link to the user’s email.
- The token can be used to reset the password via:

```http
POST /api/reset-password
Body:
{
  "email": "user@example.com",
  "token": "from-email-link",
  "password": "new_password",
  "password_confirmation": "new_password"
}
```

---

## 🔔 Event Reminders

This API automatically sends email reminders to attendees **1 hour before event start time** using Laravel’s scheduler.

### To enable:
Set the following cron job on your server:

```bash
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```

---

## 🧪 API Endpoints

### Auth

| Method | Endpoint              | Description                     |
|--------|-----------------------|---------------------------------|
| POST   | `/api/register`       | Register a new user             |
| POST   | `/api/login`          | Log in and get access token     |
| POST   | `/api/forgot-password`| Request password reset link     |
| POST   | `/api/reset-password` | Reset password with token       |

### Events

| Method | Endpoint                    | Description                    |
|--------|-----------------------------|--------------------------------|
| GET    | `/api/events`               | List all events                |
| POST   | `/api/events`               | Create a new event             |
| GET    | `/api/events/{id}`          | View a single event            |
| PUT    | `/api/events/{id}`          | Update an event                |
| DELETE | `/api/events/{id}`          | Delete an event                |

### Attendance

| Method | Endpoint                                 | Description                    |
|--------|------------------------------------------|--------------------------------|
| POST   | `/api/events/{id}/attendees`             | Attend an event                |
| DELETE | `/api/events/{id}/attendees/{attendeeId}`| Unattend an event              |

---

## 🛠️ Technologies

- Laravel 12
- Laravel Sanctum
- MySQL
- Mailtrap (email testing)
- Laravel Notifications
- Laravel Scheduler

---


---

## 🙌 Contributions

Pull requests are welcome! For major changes, please open an issue first.

---
