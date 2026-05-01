================================================================================
                    PROJECT INSTALLATION GUIDE
================================================================================

This guide will help you install and set up this Laravel project on a new computer.

================================================================================
PREREQUISITES
================================================================================

Before starting, ensure you have the following installed:

1. PHP >= 8.2
   - Download from: https://www.php.net/downloads
   - Required extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo

2. Composer (PHP Dependency Manager)
   - Download from: https://getcomposer.org/download/

3. Node.js and NPM (v18 or higher)
   - Download from: https://nodejs.org/

4. MySQL or MariaDB
   - MySQL: https://dev.mysql.com/downloads/
   - MariaDB: https://mariadb.org/download/

5. Python 3.8 or higher (for CLIP API)
   - Download from: https://www.python.org/downloads/

6. Git (optional, for cloning)
   - Download from: https://git-scm.com/downloads/

================================================================================
INSTALLATION STEPS
================================================================================

STEP 1: CLONE OR COPY THE PROJECT
----------------------------------
Option A: If using Git
   git clone <repository-url>
   cd Final_Project

Option B: If copying files
   - Extract/copy all project files to your desired directory
   - Open terminal/command prompt in the project directory


STEP 2: INSTALL PHP DEPENDENCIES
----------------------------------
Run the following command in the project root directory:

   composer install

This will install all Laravel and PHP dependencies defined in composer.json.


STEP 3: INSTALL NODE.JS DEPENDENCIES
-------------------------------------
Run the following command:

   npm install

This will install all frontend dependencies (Tailwind CSS, Vite, etc.).


STEP 4: INSTALL PYTHON DEPENDENCIES
------------------------------------
Run the following command:

   pip install -r requirements.txt

This will install the required Python packages for the CLIP API service.


STEP 5: CONFIGURE ENVIRONMENT FILE
-----------------------------------
1. Copy the example environment file:
   
   Windows:
   copy .env.example .env
   
   Linux/Mac:
   cp .env.example .env

2. Open the .env file and configure the following:

   DATABASE CONFIGURATION:
   -----------------------
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password

   MAIL CONFIGURATION (for email verification):
   --------------------------------------------
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your_email@gmail.com
   MAIL_PASSWORD=your_app_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your_email@gmail.com
   MAIL_FROM_NAME="${APP_NAME}"

   Note: For Gmail, you need to generate an App Password:
   https://support.google.com/accounts/answer/185833

   CLIP API CONFIGURATION:
   -----------------------
   CLIP_API_URL=http://127.0.0.1:5000

   APP CONFIGURATION:
   ------------------
   APP_NAME="Your App Name"
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://localhost


STEP 6: GENERATE APPLICATION KEY
---------------------------------
Run the following command:

   php artisan key:generate

This generates a unique encryption key for your application.


STEP 7: CREATE DATABASE
------------------------
1. Open MySQL/MariaDB command line or phpMyAdmin
2. Create a new database:
   
   CREATE DATABASE your_database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

3. Make sure the database name matches what you set in the .env file


STEP 8: RUN DATABASE MIGRATIONS
--------------------------------
Run the following command to create all database tables:

   php artisan migrate

If you want to seed the database with sample data:

   php artisan db:seed --class=PostSeeder

Or to migrate and seed in one command:

   php artisan migrate:fresh --seed


STEP 9: CREATE STORAGE LINK
----------------------------
Run the following command to create a symbolic link for file storage:

   php artisan storage:link

This allows public access to files stored in the storage directory.


STEP 10: BUILD FRONTEND ASSETS
-------------------------------
For development:
   npm run dev

For production:
   npm run build


STEP 11: START THE SERVICES
----------------------------
You need to start three services:

1. START LARAVEL SERVER:
   Open a terminal and run:
   php artisan serve
   
   The application will be available at: http://localhost:8000

2. START VITE DEV SERVER (for development only):
   Open another terminal and run:
   npm run dev

3. START CLIP API SERVER:
   Option A - Using batch file (Windows):
   Double-click: start_clip_api.bat
   
   Option B - Manual start:
   python clip_api.py
   
   The CLIP API will run on: http://localhost:5000

Note: You can also use the start_project.bat file to start all services at once (Windows only).


STEP 12: ACCESS THE APPLICATION
--------------------------------
Open your web browser and navigate to:
   http://localhost:8000

Default seeded user credentials (if you ran the seeder):
   Email: user1@example.com
   Password: 12345678

   Email: user2@example.com
   Password: 12345678

   Email: user3@example.com
   Password: 12345678

   Email: user4@example.com
   Password: 12345678


================================================================================
TROUBLESHOOTING
================================================================================

ISSUE: "Class not found" errors
SOLUTION: Run: composer dump-autoload

ISSUE: Permission errors on storage or cache directories
SOLUTION (Linux/Mac): 
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache

SOLUTION (Windows):
   Make sure your user has write permissions to these folders

ISSUE: Database connection errors
SOLUTION: 
   - Verify MySQL/MariaDB is running
   - Check database credentials in .env file
   - Ensure database exists

ISSUE: CLIP API not working
SOLUTION:
   - Verify Python is installed: python --version
   - Verify requirements are installed: pip list
   - Check if port 5000 is available
   - Verify CLIP_API_URL in .env matches the running API

ISSUE: Email verification not working
SOLUTION:
   - Check MAIL_* settings in .env
   - For Gmail, ensure you're using an App Password
   - Check spam folder for verification emails

ISSUE: CSS not loading
SOLUTION:
   - Run: npm run build
   - Clear browser cache
   - Check if Vite dev server is running (npm run dev)

ISSUE: Images not displaying
SOLUTION:
   - Run: php artisan storage:link
   - Check storage/app/public directory permissions


================================================================================
ADDITIONAL COMMANDS
================================================================================

Clear application cache:
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear

Reset database (WARNING: Deletes all data):
   php artisan migrate:fresh

Reset database with sample data:
   php artisan migrate:fresh --seed

Run tests:
   php artisan test

Queue worker (if using queues):
   php artisan queue:work


================================================================================
PRODUCTION DEPLOYMENT
================================================================================

For production deployment, additional steps are required:

1. Set environment to production in .env:
   APP_ENV=production
   APP_DEBUG=false

2. Optimize the application:
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache

3. Build production assets:
   npm run build

4. Set proper file permissions (Linux/Mac)

5. Configure a proper web server (Apache/Nginx)

6. Set up SSL certificate (Let's Encrypt recommended)

7. Configure proper database backups

8. Set up process manager for queue workers (Supervisor)


================================================================================
SUPPORT & DOCUMENTATION
================================================================================

Laravel Documentation: https://laravel.com/docs
Tailwind CSS: https://tailwindcss.com/docs
Vite: https://vitejs.dev/guide/

For project-specific issues, refer to the project documentation or contact
the development team.


================================================================================
PROJECT STRUCTURE
================================================================================

app/                    - Application core files (Models, Controllers, etc.)
config/                 - Configuration files
database/               - Migrations, seeders, factories
public/                 - Public assets (entry point)
resources/              - Views, CSS, JS source files
routes/                 - Application routes
storage/                - File storage, logs, cache
tests/                  - Test files
.env                    - Environment configuration
composer.json           - PHP dependencies
package.json            - Node.js dependencies
requirements.txt        - Python dependencies
clip_api.py             - CLIP API service
start_clip_api.bat      - Windows batch file to start CLIP API
start_project.bat       - Windows batch file to start all services


================================================================================
END OF INSTALLATION GUIDE
================================================================================
