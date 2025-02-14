# Project Overview
This project is about github-clone. It is built using Laravel as the backend framework and Vue.js as the frontend framework.

## Requirements
* PHP 8.0 or higher
* Laravel 11.0 or higher
* Vue.js 3.0 or higher


## Installation
To get started with the project, follow these steps:
1. **Clone the repository**: `[git clone https://github.com/your-repo/your-project.git](https://github.com/JamJam126/github-clone.git)`
2. **Install dependencies**: `composer install` and `npm install`
3. **Create a new database**: Create a new database and update the `.env` file with your database credentials
4. **Generate key**: `php artisan key:generate`
5. **Run migrations**: `php artisan migrate`
6. **Run the development server**: `php artisan serve`
7. **Run the frontend development server**: `npm run dev`

## Project Structure
* `app/`: Laravel application code
* `resources/js/`: Vue.js frontend code
* `public/`: Publicly accessible files
* `storage/`: Storage for uploaded files
* `tests/`: Unit tests and feature tests
