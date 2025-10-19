# Soccer Manager

A RESTful API application for managing soccer teams, players, and transfers. Built with Laravel 12, this application provides a complete system for team management including player transfers, team updates, and multi-language support.

## Features

- User authentication with Laravel Sanctum
- Team management (view and update teams)
- Player management (update player details)
- Transfer market system (list players, buy players, view transfer history)
- Multi-language support (English and Georgian)
- Comprehensive API endpoints for all operations

## Prerequisites

Before you begin, ensure you have the following installed:

- PHP >= 8.4
- Composer
- MySQL or SQLite

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd Soccer-Manager
```

2. Install PHP dependencies:
```bash
composer install
```

3. Copy the environment file and configure your database:
```bash
cp .env.example .env
```

4. Edit the `.env` file and set your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=soccer_manager
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Run database migrations:
```bash
php artisan migrate
```

7. Seed the database with sample data:
```bash
php artisan db:seed
```

## Running the Application

Start the Laravel development server:

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## API Documentation

A complete Postman collection is available in the repository:
- **File**: `Soccer manager.postman_collection.json`
- **Base URL**: `http://localhost:8000`

### Available Endpoints

#### Authentication
- `POST /api/register` - Register a new user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user
- `GET /api/me` - Get authenticated user details

#### Team Management
- `GET /api/team` - Get user's team
- `PUT /api/team` - Update team details

#### Player Management
- `PUT /api/players/{player_id}` - Update player details

#### Transfer Market
- `GET /api/transfer-list` - View available players for transfer
- `POST /api/players/{player_id}/list-for-transfer` - List a player for transfer
- `DELETE /api/players/{player_id}/remove-from-transfer-list` - Remove player from transfer list
- `POST /api/transfers/buy` - Buy a player from transfer list
- `GET /api/transfers/history` - View transfer history

### Using the Postman Collection for Documentation

1. Import [`Soccer_manager.postman_collection.json`](Soccer_manager.postman_collection.json) into Postman
2. The collection includes environment variables:
   - `base`: Base URL (default: `http://localhost:8000`)
   - `token`: Authentication token (automatically set after login/register)
3. Start with the Auth folder to register or login
4. The bearer token will be automatically set for subsequent requests



### Localization

If you need localization, you must pass the Accept-Language header in your requests. Available languages are:

- `ka` (Georgian)
- `en` (English)


## Running Tests

The application uses Pest PHP for testing. Run the test suite with:

```bash
./vendor/bin/pest
```


### Code Quality Tools

Run Laravel Pint for code formatting:

```bash
./vendor/bin/pint
```
