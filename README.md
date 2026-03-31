# 🚀 LAMP Stack Deployment on AWS EC2 (RHEL)

## Project Overview

This project demonstrates the end-to-end deployment of a full **LAMP stack** (Linux, Apache, MariaDB, PHP) on **AWS EC2** running **Red Hat Enterprise Linux (RHEL 9)**. The stack powers a fully functional **Student Records Management System** — a CRUD web application that allows adding, viewing, and deleting student records through a browser interface, backed by a MariaDB database in a secure cloud environment.

---

## 🔹 Key Features

- **Cloud Deployment** — EC2 instance deployed with a public IP, Security Group configured for SSH and HTTP access
- **Web Server** — Apache (httpd) installed, configured, and managed via `systemd`
- **Server-Side Scripting** — PHP 8.3 integrated with Apache for dynamic content generation
- **Database** — MariaDB installed and secured; dedicated database and least-privilege user created following security best practices
- **Security Hardening** — SELinux enforcing mode, firewalld rules, Apache version masking, directory listing disabled
- **Prepared Statements** — PDO with parameterized queries throughout to prevent SQL injection
- **Browser Verification** — App tested via public EC2 IP confirming full database connectivity and dynamic content delivery

---

## 🛠️ Technology Stack

| Component     | Technology                        |
|---------------|-----------------------------------|
| Cloud         | AWS EC2 (t2.micro / t3.micro)     |
| OS            | Red Hat Enterprise Linux (RHEL 9) |
| Web Server    | Apache httpd                      |
| Scripting     | PHP 8.3                           |
| Database      | MariaDB (MySQL compatible)        |
| Firewall      | firewalld + AWS Security Groups   |
| Access Control| SELinux (Enforcing mode)          |
| SSH Client    | PuTTY (.ppk key pair)             |

---

## 🔹 Architecture

```
Internet
    │
    ▼
AWS Security Group (port 22, 80)
    │
    ▼
EC2 Instance — RHEL 9
    │
    ├── firewalld (HTTP/HTTPS rules)
    │
    ├── Apache httpd (port 80)
    │       │
    │       ▼
    │   PHP 8.3 (processes .php files)
    │       │
    │       ▼
    │   MariaDB (studentdb)
    │
    └── SELinux (Enforcing)
```

**Highlights:**
- Users access the EC2 instance over the internet via HTTP
- Apache serves PHP application files from `/var/www/html/`
- PHP connects to MariaDB using PDO for secure database operations
- SELinux and firewalld add layered security on top of AWS Security Groups

---

## 🔹 AWS Infrastructure

- **Instance type:** t2.micro / t3.micro (Free Tier eligible)
- **AMI:** Red Hat Enterprise Linux 9
- **Security Group:** SSH (port 22 — restricted to my IP), HTTP (port 80 — open)
- **Key pair:** RSA `.ppk` format (PuTTY)
- **Storage:** 10 GB gp2 EBS volume

---

## 🔹 Steps Implemented

1. Launched RHEL 9 EC2 instance with public IP and configured Security Group
2. SSH'd into instance via PuTTY using `.ppk` key pair as `ec2-user`
3. Set server hostname using `hostnamectl`
4. Updated all system packages with `sudo dnf update -y`
5. Installed and started Apache; enabled for auto-start on reboot
6. Configured `firewalld` to allow HTTP and HTTPS traffic
7. Installed MariaDB, ran `mysql_secure_installation` to harden defaults
8. Created `studentdb` database and `lampuser` with least-privilege access
9. Created `students` table with appropriate schema
10. Installed PHP 8.3 with `php-mysqlnd`, `php-fpm`, `php-json`, `php-mbstring`
11. Verified PHP with `phpinfo()` test page, then deleted it immediately (security practice)
12. Built Student Records CRUD app in a single `index.php` file
13. Applied SELinux file contexts with `restorecon` and enabled `httpd_can_network_connect_db`
14. Hardened Apache — `ServerTokens Prod`, `ServerSignature Off`, `-Indexes`
15. Set correct file permissions (`644` files, `755` directories)

---

## 🔹 Database Schema

```sql
CREATE DATABASE studentdb;

CREATE USER 'lampuser'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON studentdb.* TO 'lampuser'@'localhost';
FLUSH PRIVILEGES;

USE studentdb;

CREATE TABLE students (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    course     VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 🔹 Application Features

The Student Records Management System supports full **CRUD** operations:

| Operation | Description                            |
|-----------|----------------------------------------|
| Create    | Add a new student via HTML form        |
| Read      | Display all student records in a table |
| Delete    | Remove a student record by ID          |

---

## 🔹 How to Access

```
# View the application
http://<EC2-Public-IP>/

# Direct access to app file
http://<EC2-Public-IP>/index.php
```

---

## 🔹 Security Hardening Applied

| Area        | Measure                                                        |
|-------------|----------------------------------------------------------------|
| SELinux     | Enforcing mode; correct `httpd_sys_content_t` contexts applied |
| SELinux     | `httpd_can_network_connect_db` boolean enabled                 |
| Apache      | `ServerTokens Prod` — hides version from response headers      |
| Apache      | `ServerSignature Off` — removes version from error pages       |
| Apache      | `Options -Indexes` — directory listing disabled                |
| MariaDB     | Anonymous users removed via `mysql_secure_installation`        |
| MariaDB     | Root login restricted to localhost only                        |
| MariaDB     | Dedicated app user (`lampuser`) with least privilege           |
| PHP         | PDO prepared statements — prevents SQL injection               |
| PHP         | `htmlspecialchars()` on all user input — prevents XSS          |
| Firewall    | `firewalld` — only HTTP/HTTPS open; SSH restricted to my IP    |
| File system | Files `644`, directories `755`                                 |

---

## 🔹 Key Linux Skills Demonstrated

- Package management with `dnf`
- Service management with `systemctl` (start, enable, status, restart)
- Firewall management with `firewall-cmd`
- SELinux context management with `restorecon` and `setsebool`
- File permission management with `chmod` and `chown`
- Server hardening best practices
- SSH key-based authentication
- Hostname configuration with `hostnamectl`

---

## 🔹 Outcome

A secure, fully functional LAMP stack running on AWS EC2 (RHEL 9), demonstrating:

- Linux server provisioning and administration from scratch
- Web server installation, configuration, and hardening
- Database setup, security, and schema design
- Full-stack PHP web application development
- Cloud infrastructure configuration on AWS
- Production-grade security practices (SELinux, firewalld, least privilege)

---
