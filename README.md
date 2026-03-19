# Nexus CRM Alerts

The notification and reminder microservice for the **Nexus CRM** platform. This Laravel-based API manages follow-up scheduling, real-time notification broadcasting, and notification lifecycle operations across the CRM ecosystem.

## Features

- **Follow-Up Reminders** — Create, update, and manage scheduled follow-up tasks for leads and contacts
- **Real-Time Broadcasting** — Push notifications to connected clients via WebSocket integration
- **Notification Center** — Centralized notification list with read/unread status management
- **Status Management** — Mark notifications as read, archive, or dismiss
- **User-Scoped Queries** — Filter follow-ups and notifications by user context
- **Scheduled Broadcasts** — Automated reminder delivery based on configured schedules
- **RESTful API** — Clean, resource-oriented endpoints for all notification operations

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 10 |
| Language | PHP 8.1+ |
| Authentication | Laravel Sanctum |
| WebSockets | beyondcode/laravel-websockets |
| HTTP Client | Guzzle |
| Database | MySQL |
| Testing | PHPUnit 10 |
| Code Style | StyleCI |

## Prerequisites

- PHP >= 8.1
- Composer
- MySQL 5.7+ or MariaDB 10.3+

## Getting Started

1. **Clone the repository**

   ```bash
   git clone https://github.com/mhmalvi/nexus-crm-alerts.git
   cd nexus-crm-alerts
   ```

2. **Install dependencies**

   ```bash
   composer install
   ```

3. **Configure environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Update `.env` with database credentials, WebSocket settings, and inter-service URLs.

4. **Run database migrations**

   ```bash
   php artisan migrate
   ```

5. **Start the development server**

   ```bash
   php artisan serve
   ```

6. **Start the WebSocket server** (for real-time broadcasting)

   ```bash
   php artisan websockets:serve
   ```

   The API will be available at `http://localhost:8000`.

## API Overview

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/follow-up` | GET | List all follow-up reminders |
| `/api/follow-up` | POST | Create a new follow-up reminder |
| `/api/{id}/follow-up-update` | PUT | Update an existing reminder |
| `/api/follow-up-by-user` | POST | Get reminders for a specific user |
| `/api/{id}/notifications-list` | POST | List notifications for a user |
| `/api/change-status/{id}` | POST | Update notification read status |
| `/api/{id}/destroy` | GET | Delete a notification |
| `/api/follow` | GET | Trigger scheduled broadcast |

## Microservices Integration

| Service | Interaction |
|---------|------------|
| nexus-crm-users | Resolves user identities for notification targeting |
| nexus-crm-leads | Receives events on lead status changes to trigger reminders |
| nexus-crm-orgs | Scopes notifications to company/organization context |
| nexus-crm-client | Frontend consumes notification feeds via REST and WebSocket |
| nexus-crm-portal | Portal consumes notification feeds for the general CRM interface |

## License

This project is proprietary software. All rights reserved.
