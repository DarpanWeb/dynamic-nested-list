# Nested List

## Installation

Follow these steps to install the project locally:

### Prerequisites

- PHP >= 8.1
- Composer
- MySQL or any other compatible database

### Clone the repository

```bash
git clone https://github.com/DarpanWeb/dynamic-nested-list.git
```

### Install Php Dependencies
composer install

### Environment Configuration

- Duplicate the .env.example file and rename it to .env.
- Update the .env file with your local environment settings:
- Set DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD for database configuration.
- Generate an application key by running:
```
php artisan key:generate
```

### Database Setup
- Create a new database in your MySQL server.
- Run the database migrations to create tables and seed the database with sample data:
``` 
  php artisan migrate 
```

### Running the Application
```
php artisan serve
```
The application should now be running locally at http://localhost:8000.

### Configure the cron on server
```
0 * * * * php /public_html/artisan hour:update-nested-list >> /public_html/storage/logs/laravel.log 2>&1
```

### Alternatively Manually Run The Command
- The above configuration will ensure the command runs once every hour. However, If you want to manually run the command to generate the list you can do so via the following command
```
php artisan hour:update-nested-list
```