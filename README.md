# Project Setup and Gulp Tasks Documentation

This document explains how to use the provided Gulp tasks for generating JWT keys, running database migrations, and loading fixtures, as well as initializing the database for the `ultradinapp` project.

---

## Prerequisites

1. **Docker**: Ensure Docker is installed and running on your system.
2. **Node.js**: Ensure Node.js is installed, along with `gulp-cli` globally:
   ```bash
   npm install -g gulp-cli
   ```
3. **MySQL Client**: Install a MySQL client for database setup.

---

## Setting Up the Database

Before running the application, set up the database by following these steps:

1. Start the database container using Docker Compose from the root of the project:
   ```bash
   docker compose up -d
   ```
2. Access the MySQL instance:
   ```bash
   mysql -u root -h localhost -p -P 8082
   ```
   Use `root` as the password when prompted.
3. Create the database:
   ```sql
   CREATE DATABASE ultradindb;
   ```
4. Exit the MySQL client:
   ```sql
   EXIT;
   ```

---

## Gulp Tasks

### 1. `keygen`: Generate JWT Keys

This task creates a private and public key pair for JWT authentication.

#### What it Does:
- Creates the `ultradinapp/config/jwt` directory (if it doesn’t exist).
- Generates a 4096-bit RSA private key (`private.pem`).
- Generates a public key (`public.pem`).
- Sets appropriate permissions (skipped on Windows).

#### Run the Task:
```bash
yarn gulp keygen
```

---

### 2. `mif`: Run Migrations and Load Fixtures

This task handles database schema updates and populates it with initial data.

#### What it Does:
- Navigates to the `ultradinapp` directory.
- Runs Doctrine migrations to apply schema changes.
- Loads fixtures into the database.

#### Run the Task:
```bash
yarn gulp mif
```

#### Notes:
- If migrations are already applied, it will log “Migrations are up to date” and proceed.
- If a table already exists, it skips the conflicting migration and logs a warning.

---

## Detailed Gulp Task Breakdown

### Key Generation Tasks

#### `createJwtDir(cb)`
- Checks if the `ultradinapp/config/jwt` directory exists. Creates it if not.

#### `generatePrivateKey(cb)`
- Generates a private key at `ultradinapp/config/jwt/private.pem` using OpenSSL.

#### `generatePublicKey(cb)`
- Generates a public key at `ultradinapp/config/jwt/public.pem` from the private key.

#### `setPermissions(cb)`
- Sets file permissions for the private and public keys (skipped on Windows).

---

### Database Tasks

#### `runMigrations(cb)`
- Executes the following command to apply migrations:
  ```bash
  php bin/console doctrine:migration:migrate --no-interaction
  ```
- Handles scenarios where migrations are already applied or tables exist.

#### `loadFixtures(cb)`
- Executes the following command to load fixtures:
  ```bash
  php bin/console doctrine:fixtures:load --append --no-interaction
  ```

---

## Notes

- Always start the database with Docker Compose before running any database-related tasks.
- Ensure the `ultradinapp` directory contains the necessary `doctrine` configuration for migrations and fixtures.
- For any issues, check the logs output by the Gulp tasks for more details.

---

## Common Commands

### Start Database
```bash
docker compose up -d
```

### Access MySQL
```bash
mysql -u root -h localhost -p -P 8082
```

### Create Database
```sql
CREATE DATABASE ultradindb;
```

### Generate JWT Keys
```bash
yarn gulp keygen
```

### Run Migrations and Fixtures
```bash
yarn gulp mif
```

git bash into project dir and run gulp to generate jwt genkeys
docker compose up -d --build to run mariadb database
symfony console doctrine:fixtures:load to generate fake data
symfony console doctrine:migratin:migrate to run migration (might need to create ultradindb before)

