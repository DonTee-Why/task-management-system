# Task Management System

A comprehensive task management system built with Laravel. This application allows users to create, manage, and track tasks efficiently.

## Features

- User Authentication and Authorization
- Task Creation, Editing, and Deletion

## Installation

This project requires [PHP](https://www.php.net/) v8.1+ and [Composer](https://getcomposer.org/) to run.

1. Clone the project from the repository and then navigate into the root directory.

    ```sh
    git clone https://github.com/DonTee-Why/task-management-system.git
    cd task-management-system
    ```

2. Install the dependencies.

    ```sh
    composer install
    ```

3. Copy the `.env.example` file to `.env` and configure your environment variables.

    ```sh
    cp .env.example .env
    ```

4. Generate the application key.

    ```sh
    php artisan key:generate
    ```

5. Run the database migrations.

    Please note that the database driver used in this project is the sqlite driver. If you choose to use it, you will be prompted to create the database file.

    ```sh
    php artisan migrate
    ```

6. Seed the database with initial data (optional).

    ```sh
    php artisan db:seed
    ```

7. Start the development server.

    ```sh
    php artisan serve
    ```

## Testing

The project includes unit tests written with `PHPUnit`.

- To run all the tests:

    ```sh
    php artisan test
    ```

## API Documentation

The API is documented using Postman. You can access the Postman documentation [here](https://documenter.getpostman.com/view/17638908/2sA3kYhz64).

## License

MIT License
