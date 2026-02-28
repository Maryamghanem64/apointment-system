# Appointment System - Website Report

## Overview
This is a **Laravel-based Appointment Booking System** with comprehensive multi-role support. The application enables clients to book appointments with service providers, while administrators manage the entire system and providers manage their schedules.

---

## Technology Stack
- **Framework**: Laravel 10
- **Programming Language**: PHP
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Breeze
- **Authorization**: Spatie Permission Package
- **Frontend**: Tailwind CSS
- **Frontend Framework**: Blade Templates

---

## Core Features

### 1. Authentication System
- **User Registration**: New users can create accounts
- **Login/Logout**: Secure authentication
- **Password Reset**: Forgot password functionality with email reset links
- **Email Verification**: Account verification via email
- **Session Management**: Secure password confirmation for sensitive actions

### 2. Role-Based Access Control (RBAC)
The system implements three distinct user roles:

| Role | Description |
|------|-------------|
| **Admin** | Full system access, manages users, providers, services, appointments, and payments |
| **Provider** | Manages their profile, working hours, holidays, and appointments |
| **Client** | Books appointments, views history, manages profile |

### 3. User Management (Admin)
- Create new users
- View user list with details
- Edit user information
- Delete users (soft delete)
- Assign roles to users

### 4. Role Management (Admin)
- Create custom roles
- Edit existing roles
- Delete roles
- Role-based permissions

### 5. Provider Management (Admin)
- Add new service providers
- Edit provider details
- Delete providers
- Assign services to providers
- Manage provider working hours
- Manage provider holidays

### 6. Service Management (Admin)
- Create new services with:
  - Service name
  - Duration (in minutes)
  - Price
- Edit existing services
- Delete services
- Assign services to multiple providers

### 7. Provider Working Hours
- Set daily working schedules
- Define start and end times for each day
- Per-provider working hour configuration

### 8. Provider Holidays
- Schedule holidays/vacations
- Set holiday dates
- Add reason for holidays
- Block appointments on holidays

### 9. Appointment Management

#### For Clients:
- View available services
- Select provider and service
- Choose date and time
- Add notes to appointment
- View appointment history
- Cancel appointments

#### For Providers:
- View incoming appointments
- View scheduled appointments
- Add provider notes to appointments
- Cancel/manage appointments

#### For Admins:
- View all appointments
- Create appointments on behalf of clients
- Delete appointments
- Monitor appointment status

#### Appointment Fields:
- Client ID
- Provider ID
- Service ID
- Start time & End time
- Status (pending, confirmed, cancelled, completed)
- Client notes
- Provider notes

### 10. Payment Management (Admin)
- View all payments
- Track payment status:
  - **Unpaid**: Payment pending
  - **Paid**: Payment completed
  - **Refunded**: Payment returned
- Store transaction IDs
- Record payment methods

### 11. User Profile Management
- Edit profile information (name, email)
- Update password
- Delete account
- View personal dashboard

### 12. Dashboard & Navigation
- **Welcome Page**: Public landing page with login/register
- **Client Dashboard**: Personal appointment overview
- **Provider Dashboard**: Schedule and appointment management
- **Admin Dashboard**: System-wide statistics and management
- Role-based menu navigation
- Responsive design

---

## Database Models

| Model | Description |
|-------|-------------|
| **User** | Authenticatable user with roles |
| **Provider** | Service provider linked to user |
| **Service** | Bookable service with duration and price |
| **Appointment** | Booking between client and provider |
| **Payment** | Payment record for appointments |
| **ProviderWorkingHour** | Working schedule for providers |
| **ProviderHoliday** | Holiday/vacation schedule |
| **Role** | User roles |

---

## Key Routes

### Public Routes
- `/` - Welcome page
- `/login` - User login
- `/register` - User registration
- `/forgot-password` - Password recovery

### Client Routes (Authenticated)
- `/client/dashboard` - Personal dashboard
- `/client/appointments` - View/book appointments
- `/client/profile` - Edit profile
- `/client/settings` - Account settings

### Provider Routes (Authenticated)
- `/provider/dashboard` - Schedule overview
- `/provider/appointments` - Manage appointments
- `/provider/profile` - Edit profile
- `/provider/settings` - Account settings

### Admin Routes (Authenticated)
- `/admin/dashboard` - System overview
- `/admin/users` - User management
- `/admin/settings` - System settings
- `/users` - CRUD operations
- `/roles` - Role management
- `/services` - Service management
- `/providers-management` - Provider management
- `/appointments` - Appointment management
- `/payments` - Payment management

---

## Security Features
- Password hashing
- Email verification
- CSRF protection
- Role-based middleware
- Route-level authorization
- Soft deletes for data preservation

---

## Summary
This Appointment System is a comprehensive Laravel application that provides a complete booking solution for service-based businesses. It features:
- ✅ Multi-role authentication
- ✅ Complete CRUD operations
- ✅ Appointment scheduling
- ✅ Payment tracking
- ✅ Provider management
- ✅ Service management
- ✅ Responsive UI with Tailwind CSS
- ✅ Modern Laravel best practices

The system is production-ready and can be easily extended with additional features such as email notifications, calendar integration, online payments (Stripe/PayPal), and more.
