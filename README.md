# 🧑‍🏫 Tutoring Platform

A web platform for students looking for tutors and for tutors offering their services.  
The project is built with HTML, CSS, JavaScript, and PHP, with a PostgreSQL backend.  
Runs entirely in a Dockerized environment.

---

## 🎯 Project Goals

- Allow students to browse tutors and book lessons.
- Allow tutors to create profiles and list the subjects they teach.
- Provide reservation management, user roles, and login functionality.

---

## 🛠️ Technologies

- **Frontend:** HTML5, CSS3, JavaScript (ES6+), Fetch API
- **Backend:** PHP 8 (OOP)
- **Database:** PostgreSQL
- **Communication:** AJAX / JSON
- **Authentication:** PHP Session
- **Environment:** Docker + Docker Compose

---

## 🚀 Getting Started (with Docker)

### 1. Prerequisites

- [Docker](https://www.docker.com/products/docker-desktop)
- [Docker Compose](https://docs.docker.com/compose/)

### 2. Clone the Repository

### 3. Environment Configuration

Create a .env file in the project root directory with the following content:
````
POSTGRES_DB=tutoring
POSTGRES_USER=postgres
POSTGRES_PASSWORD=YourStrongPassword
PGADMIN_DEFAULT_EMAIL=admin@example.com
PGADMIN_DEFAULT_PASSWORD=admin
````
Make sure .env is listed in your .gitignore file.

### 4. Run the Application
`docker-compose up --build`

### 5. Access the Platform

- **Frontend (Main Website):** http://localhost:8080
- **PGAdmin (Database GUI):** http://localhost:5050
#### PGAdmin Credentials:
- **Email:** `admin@example.com`
- **Password:** `admin`

### Add a new server in PGAdmin:
- **Host:** `db`
- **Database:** `korepetycje`
- **Username:** `postgres`
- **Password:** as set in `.env`

