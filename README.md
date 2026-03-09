# Clothing Rental Marketplace Web System

A complete rental clothing marketplace platform built with Laravel, allowing customers to rent traditional clothing from verified sellers.

## Features

### Customer Features
- Browse and search rental items without login
- View product details (size, color, description, price, availability)
- Add items to cart and manage cart
- User registration and authentication
- Place rental orders with date selection
- Choose delivery method (pickup or home delivery)
- Multiple payment methods (Digital payment, Cash on Delivery)
- View order history and status

### Seller Features
- Seller registration with shop details
- Seller verification by admin
- Add and manage rental items
- Upload product images
- View platform fees and earnings breakdown
- Manage orders
- Handle returns and report damages
- Damage fee calculation

### Admin Features
- Seller account verification
- Product approval/rejection system
- Order management
- Category management
- Platform commission settings
- View revenue and commission statistics
- Manage all system users

## Tech Stack

- **Framework:** Laravel 10.x
- **Backend:** PHP 8.x
- **Frontend:** HTML, CSS, Bootstrap 5.3
- **Database:** MySQL
- **Server:** XAMPP
- **Development Tool:** VS Code

## System Requirements

- PHP >= 8.1
- MySQL >= 5.7
- Composer
- XAMPP (Apache + MySQL)
- Node.js & NPM (optional, for asset compilation)

## Installation & Setup

### 1. Clone the Repository

```bash
cd C:\xampp\htdocs
git clone https://github.com/tchoden01/clothing_rental_system.git
cd clothing_rental
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Configuration

The `.env` file is already configured with:
- Database name: `clothing_rental_system`
- Database username: `root`
- Database password: (empty)

### 4. Create Database

Open phpMyAdmin (http://localhost/phpmyadmin) and create a database named `clothing_rental_system`

Or use MySQL command line:
```sql
CREATE DATABASE clothing_rental_system;
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Seed Database

```bash
php artisan db:seed
```

This will create:
- Admin account (email: admin@clothing.com, password: admin123)
- Sample categories
- Platform settings (15% commission rate)

### 7. Create Storage Link

```bash
php artisan storage:link
```

### 8. Start the Server

```bash
php artisan serve
```

Visit: http://localhost:8000

## Default Login Credentials

**Admin Account:**
- Email: admin@clothing.com
- Password: admin123

## Database Structure

### Tables Created:

1. **users** - System users (customers, sellers, admin)
2. **sellers** - Seller profile information
3. **categories** - Product categories
4. **products** - Rental items
5. **carts** - Shopping cart items
6. **orders** - Rental orders
7. **order_items** - Items in each order
8. **payments** - Payment records
9. **damage_reports** - Product damage reports
10. **platform_settings** - System configuration

## Application Structure

### Models
- User, Seller, Category, Product, Cart, Order, OrderItem, Payment, DamageReport, PlatformSetting

### Controllers
- AuthController - Authentication
- CustomerController - Customer operations
- ProductController - Product browsing
- CartController - Cart management
- OrderController - Order processing
- SellerController - Seller operations
- AdminController - Admin operations

### Middleware
- CheckAdmin - Admin access control
- CheckSeller - Seller access control
- CheckCustomer - Customer access control

### Views
- layouts/app.blade.php - Main layout
- home.blade.php - Homepage
- auth/* - Login and registration
- products/* - Product listing and details
- cart/* - Shopping cart
- orders/* - Order management
- seller/* - Seller dashboard and management
- admin/* - Admin dashboard and management

## Routes

### Public Routes
- `/` - Homepage
- `/products` - Browse products
- `/products/{id}` - Product details
- `/login` - Login page
- `/register` - Customer registration
- `/register/seller` - Seller registration

### Customer Routes (Authenticated)
- `/cart` - Shopping cart
- `/checkout` - Checkout page
- `/orders` - Order history
- `/profile` - User profile

### Seller Routes (Authenticated + Seller Role)
- `/seller/dashboard` - Seller dashboard
- `/seller/products` - Manage products
- `/seller/orders` - View orders
- `/seller/orders/{id}/return` - Process returns

### Admin Routes (Authenticated + Admin Role)
- `/admin/dashboard` - Admin dashboard
- `/admin/sellers` - Manage sellers
- `/admin/products` - Approve products
- `/admin/orders` - Manage orders
- `/admin/categories` - Manage categories
- `/admin/settings` - Platform settings

## Order Flow

1. Customer browses and adds products to cart
2. Customer proceeds to checkout
3. Customer selects rental dates and delivery method
4. Customer chooses payment method and places order
5. Admin assigns staff to collect item from seller
6. Staff collects item and stores at pickup point
7. Customer picks up or receives delivery
8. Customer uses item during rental period
9. Customer returns item
10. Staff returns item to seller
11. Seller checks condition and reports damage if any
12. System calculates damage fees
13. Final settlement and order completion

## Commission System

- Platform charges a configurable commission (default: 15%)
- Sellers receive: Rental Price - Commission
- Admin can adjust commission rate in settings
- Commission is calculated per order item

## Image Upload

- Product images stored in `storage/app/public/products`
- Multiple images supported per product
- Accepted formats: JPEG, PNG, JPG, GIF
- Max size: 2MB per image

## Development Notes

- No starter kits (Breeze/Jetstream) used
- Custom authentication system
- Bootstrap for responsive UI
- MVC architecture following Laravel best practices
- Foreign key relationships in database
- Role-based access control

## Future Enhancements

- Email notifications
- Payment gateway integration
- Rating and review system
- Real-time order tracking
- Advanced search filters
- Seller analytics dashboard
- Customer wishlist
- Promotional codes

## Support

For issues or questions, please contact the development team or create an issue on GitHub.

## License

This project is developed for educational purposes.

---
Built with ❤️ using Laravel
