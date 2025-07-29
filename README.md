# Filament Admin User Starter Kit

## Introduction

This starter kit will help you to make filament admin panel with pre installed feature like user management, notification, profile edit and more.
## Features

- Database notification added
- User authentication to secure access to asset data.
- Manage User

## System Requirements

Before installing Filament, ensure your server meets the following requirements:

- PHP >= 8.3
- Composer
- Laravel >= 12.0
- Node.js & NPM (for frontend assets)
- A database (MySQL, PostgreSQL, SQLite, etc.)

## Installation

To get started with the Asset Management System, follow these steps:

1. Clone the repository:
    ```bash
    git clone https://github.com/miteshviras/filament-admin-user-starter-kit.git
    ```
2. Navigate to the project directory:
    ```bash
    cd filament-admin-user-starter-kit
    ```
3. Install the dependencies:
    ```bash
    composer install
    npm install
    ```
4. Set up the environment variables:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
5. Configure the database in the `.env` file and run the migrations:
    ```bash
    php artisan migrate
    ```
6. Start the development server:
    ```bash
    php artisan serve
    npm run dev
    ```
7. **Create Admin User**: Execute the command below to create an admin user.
    ```bash
    php artisan user:make-admin
    ```
8. **Create Storage Link**: Execute the command below to create a symbolic link from `public/storage` to `storage/app/public`.
    ```bash
    php artisan storage:link
    ```
9. **Start Queue**: Execute the command below to start laravel queue.
    ```bash
    php artisan queue:work
    ```


## Usage

Once the application is installed and running, you can access it in your web browser at `http://localhost:8000`. Register or log in to start managing assets.

You can now access the Filament admin panel by navigating to `/admin` in your browser.

## Building for Production

To compile the frontend assets for production, use the following command:
```bash
npm run build
```

## Learning Filament

Filament has comprehensive [documentation](https://filamentphp.com/docs) and a growing community of developers. You can also find tutorials and guides on various topics related to Filament and the TALL stack.

## Contributing

We welcome contributions to the Asset Management System! If you would like to contribute, please fork the repository and submit a pull request. For major changes, please open an issue first to discuss what you would like to change.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contact

If you have any questions or feedback, please feel free to reach out to the project maintainer at [virashmitesh@gmail.com](mailto:virashmitesh@gmail.com).

