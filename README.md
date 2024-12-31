# Laravel 11 Project with Inertia.js and Vue.js

## Setup and Installation

### 1. **Clone the Repository**

First, clone the repository from GitHub:

```bash
git clone https://github.com/your-username/your-project-name.git
2. Install Dependencies
Navigate to the project directory:

bash
Copy code
cd your-project-name
Step 1: Install PHP dependencies
Run the following command to install Laravel's PHP dependencies:

bash
Copy code
composer install
Step 2: Install Node.js dependencies
Run the following command to install JavaScript dependencies:

bash
Copy code
npm install
3. Set Up Environment File
Create a .env file by copying the example file:

bash
Copy code
cp .env.example .env
Then, generate the application key:

bash
Copy code
php artisan key:generate
4. Configure Database
Set up your database configuration in the .env file. Modify the following lines based on your database settings:

env
Copy code
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
5. Run Database Migrations
Run the migrations to set up the database schema:

bash
Copy code
php artisan migrate
6. Start the Development Server
Run the Laravel development server:

bash
Copy code
php artisan serve
This will start the application on http://127.0.0.1:8000.

7. Run the Frontend
To compile the frontend assets, run the following command:

bash
Copy code
npm run dev
8. Visit the Application
Open your browser and navigate to http://127.0.0.1:8000 to see the application in action.

Additional Information
Inertia.js is used to build single-page applications with Laravel and Vue.js.
Vue.js is used as the frontend JavaScript framework.