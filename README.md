# Production-Ready Recipes API

## Overview

This project is a production-ready PHP API for managing recipes and user authentication. It demonstrates a complete modern API built using:

- **PHP 8.2+**  
- **PostgreSQL** for persistent storage  
- **PDO** with PostgreSQL driver  
- **JWT** for authentication  
- **Docker & Docker Compose** (optional) for containerized deployment  
- **Composer** for dependency management  
- **PHPUnit** for testing

Key features include:

- **CRUD Endpoints for Recipes:**  
  Create, read, update, delete, and search recipes; also, rate recipes with calculated averages.
  
- **User Authentication:**  
  Simple registration and login endpoints (implemented as GET endpoints for demo purposes) that generate JWT tokens after verifying credentials.
  
- **Automatic Initial Data Load:**  
  If the recipes table is empty on startup, the API auto-loads recipe data from [TheMealDB](https://www.themealdb.com).

- **Database Schema Initialization:**  
  The application auto-creates required tables (`users`, `recipes`, and `ratings`) if they do not exist. Foreign key constraints (including `ON DELETE CASCADE` on ratings) are applied for data integrity.

## Requirements

- **PHP 8.2+** with these extensions enabled:
  - `pdo`
  - `pdo_pgsql`
  - `pgsql`
- **PostgreSQL** 9.5+
- **Composer**
- **Docker & Docker Compose** (optional)
- **PHPUnit 9+** for testing

## Setup & Installation

### 1. Clone the Repository

```bash
git clone https://github.com/adarshkr357/recipes-api.git
cd recipes-api
```

### 2. Install Dependencies

Run the following command to install PHP dependencies:

```bash
composer install
```

### 3. Configure Environment Variables

Create a `.env` file in the project root with your environment-specific settings. For example:

```dotenv
# Database configuration
DB_HOST=postgres
DB_PORT=5432
DB_NAME=hellofresh
DB_USER=hellofresh
DB_PASS=hellofresh

# JWT configuration
JWT_SECRET=<Your JWT Secret Key>
JWT_ISSUER=http://localhost
JWT_AUDIENCE=http://localhost
JWT_EXPIRATION=3600
```

#### Generating Your JWT Secret Key

Use this PHP command to generate a secure key:

```bash
php -r "echo bin2hex(random_bytes(32));"
```

Copy the output (a 64-character hexadecimal string) into your `.env` under `JWT_SECRET`.

### 4. Database Setup

When the application starts, it automatically initializes the database:
- It creates the required tables (`users`, `recipes`, and `ratings`) if they do not exist.
- It seeds recipe data from TheMealDB if the `recipes` table is empty.

**To manually truncate (empty) all tables:**

```bash
docker exec -it <container> psql -h localhost -p 5432 -U hellofresh -d hellofresh -c "TRUNCATE TABLE users, recipes, ratings RESTART IDENTITY CASCADE;"
```

Make sure to adjust the host, port, user, and database name as needed.

### 5. Running the Application

#### Using Docker

Build and start containers using Docker Compose:

```bash
docker-compose build
docker-compose up -d
```

Then, open your browser at `http://localhost:8080` (or your configured port).

#### Without Docker

You can start a local PHP server from the project root:

```bash
php -S localhost:8080 -t web
```

Then open [http://localhost:8080](http://localhost:8080) in your browser.

## API Endpoints

### Public (Unauthenticated) Endpoints

- **GET /**  
  Returns API documentation (an overview of all endpoints).

- **GET /recipes**  
  List all recipes from the database.

- **GET /recipes/{id}**  
  Retrieve details for a specific recipe.

- **GET /recipes/search?q=QUERY**  
  Search for recipes by name.

### Protected Endpoints  
*(These endpoints are intended to be protected by JWT-based authentication using AuthMiddleware. They are demonstrated here but are not enforced by default in this example.)*

- **POST /recipes** – Create a new recipe.
- **PUT/PATCH /recipes/{id}** – Update an existing recipe.
- **DELETE /recipes/{id}** – Delete a recipe.
- **POST /recipes/{id}/rating** – Submit a recipe rating.

### Authentication Endpoints (Implemented as GET for Demo)

- **GET /auth/register?username=...&password=[...]**  
  Registers a new user using the provided username and password. An email is optional; if omitted, a default email value (`username@example.com`) is used.

- **GET /auth/login?username=...&password=...**  
  Logs in a user and returns a JWT token upon successful authentication.

## Testing

To run the tests, simply execute:

```bash
vendor/bin/phpunit tests
```

*Note:*  
Ensure your test environment is configured (for example, using `DB_HOST=localhost` if running locally).

## Deployment Considerations

- **JWT Secret Management:**  
  Keep your `JWT_SECRET` secure and do not commit it to source control.
- **Database Migrations:**  
  Although this project auto-creates and seeds the database, for production you should use a tool or strategy to manage database migrations.
- **Security & Error Handling:**  
  Enhance authentication middleware, input validation, and error logging as needed for production.

## Contributing

Contributions, issues, and feature requests are welcome!  
Please open an [issue](https://github.com/adarshkr357/recipes-api/issues) or submit a [pull request](https://github.com/adarshkr357/recipes-api/pulls).

## License

This project is licensed under the [MIT License](LICENSE).
