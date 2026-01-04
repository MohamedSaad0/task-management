# Task Management API

A RESTful Task Management API built with Laravel 12.  
The system supports task creation, assignment, status updates, and dependency management, enforcing business rules such as preventing task completion until all dependencies are completed.

This project was implemented as part of a technical assessment.

---

## Features

- Token-based authentication using Laravel Sanctum
- Role-based authorization (Manager / User)
- Task dependency management
- Business rule enforcement using service layer
- Clean architecture with separation of concerns
- Dockerized environment for easy setup

---

## Tech Stack

- PHP 8.2+
- Laravel 12
- MySQL 8
- Laravel Sanctum
- Docker & Docker Compose

---

## Architecture Overview

The application follows a layered architecture:

- **Controllers**  
  Thin controllers responsible for request handling and orchestration.

- **Form Requests**  
  Used for input validation and request shaping.

- **Services**  
  Encapsulate business logic and domain rules (e.g., task completion constraints).

- **Policies**  
  Handle authorization logic based on user roles and task ownership.

- **Enums**  
  Used for task statuses to ensure type safety and clarity.

This approach improves maintainability, testability, and clarity while avoiding unnecessary overengineering.

---

## Task Dependencies

Tasks can depend on other tasks.  
A task **cannot be completed** unless all its dependencies are completed.

Dependencies are modeled using a self-referencing many-to-many relationship through a `task_dependencies` pivot table.

Domain rules are enforced inside the service layer, not in controllers or validation.

---

## Authentication & Authorization

- Authentication is handled via **Laravel Sanctum** using API tokens.
- Managers can:
  - Create tasks
  - Assign dependencies
- Users can:
  - View tasks assigned to them
  - Update the status of their assigned tasks

Authorization is enforced using Laravel Policies.

---

## API Endpoints (High-Level)

- `POST /api/login`
- `POST /api/logout`
- `GET /api/tasks`
- `POST /api/tasks`
- `PUT /api/tasks/{task}`
- `PATCH /api/tasks/{task}/status`
- `POST /api/tasks/{task}/dependencies`

All task-related endpoints require authentication.

---

## Setup Instructions (Docker)

### Prerequisites
- Docker & Docker Compose

### Steps

```bash
git clone https://github.com/MohamedSaad0/task-management
cd task-mgt
docker compose up --build
