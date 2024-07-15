
![Home page before login](data\ecommerce-1.png)
![Home page after login](data\ecommerce-2.png)
![Product Page](data\ecommerce-3.png)
![Product Individual Page](data\ecommerce-4.png)
![Cart Page](data\ecommerce-5.png)

# Clone the repository
git clone https://github.com/Pandey-Narendra/task.git
cd ecommerce-website

# Install PHP dependencies
composer install

# Install JavaScript dependencies and compile assets
npm install
npm run dev

# Set up the database
1. Configure your `.env` file with your database credentials (copy `.env.example` to `.env` and update).
2. Import the SQL file located in the `data` folder into your database management tool.

# Run migrations
php artisan migrate

# Start the development server
php artisan serve

# Logging In
For User 1:
Email: abc@gmail.com
Password: abc@1234

For User 2:
Email: xyz@gmail.com
Password: xyz@1234
