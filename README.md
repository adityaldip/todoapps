
To get started with this project:

1. Clone the repository:
    ```bash
    git clone https://github.com/adityaldip/todoapps.git
    ```

2. Navigate into the project directory:
    ```bash
    cd todoapp
    ```

3. Install the required dependencies:
    ```bash
    composer install
    ```
    ```bash
    npm install
    ```

4. Set up the environment file:
    ```bash
    cp .env.example .env
    ```

5. Configure the `.env` file with your database connection details.

6. Run migrations to set up the database:
    ```bash
    php artisan migrate
    ```

7. Optionally, seed the database with sample data:
    ```bash
    php artisan db:seed
    ```
8. Running NPM commands:
    ```bash
    npm run dev
    ```

8. Serve the application:
    ```bash
    php artisan serve
    ```

## Testing

The project includes various tests to ensure correctness:

- **Feature Tests:** To test user-facing functionalities.
- **Unit Tests:** For testing individual methods and logic.
- **Browser Tests:** To test the complete user workflow in a browser environment.

To run tests, execute:
```bash
php artisan test
