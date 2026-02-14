# Quick Bites - Online Food Ordering System

A complete online food ordering system built with PHP and MySQL for BCA 4th Semester Project.

![Quick Bites Logo](images/quick%20bites.png)

## 🍔 Features

### User Features
- User registration and login with secure authentication
- Browse restaurants by categories
- View restaurant menus and dishes
- Add items to cart with quantity selection
- Secure checkout process
- Order tracking with real-time status updates
- View order history
- Printable order invoices
- Cash on Delivery (COD) payment

### Admin Features
- Comprehensive admin dashboard with statistics
- User management (view, edit, delete users)
- Restaurant management (add, edit, delete restaurants)
- Category management for restaurants
- Menu/Dish management (add, edit, delete dishes)
- Order management with status updates
- Order billing panel with print functionality
- Real-time order tracking
- Secure admin authentication

## 🛠️ Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 4
- **Backend**: PHP 7+
- **Database**: MySQL
- **Server**: Apache (XAMPP/WAMP)
- **Icons**: Font Awesome
- **Additional**: AJAX, jQuery

## 📋 Prerequisites

- XAMPP/WAMP/LAMP (Apache + MySQL + PHP)
- PHP 7.0 or higher
- MySQL 5.6 or higher
- Web browser (Chrome, Firefox, Safari, Edge)

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/SuSrUt123/BCA4thsemProject.git
```

### 2. Setup Database

1. Start Apache and MySQL in XAMPP/WAMP
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Create a new database named `onlinefoodphp`
4. Import the SQL file:
   - Click on the `onlinefoodphp` database
   - Go to "Import" tab
   - Choose file: `DATABASE FILE/onlinefoodphp.sql`
   - Click "Go"

### 3. Configure Database Connection

1. Navigate to `connection/connect.php`
2. Update database credentials:

```php
<?php
$servername = "localhost";
$username = "root";        // Your MySQL username
$password = "";            // Your MySQL password
$dbname = "onlinefoodphp"; // Database name

$db = mysqli_connect($servername, $username, $password, $dbname);

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($db, "utf8");
mysqli_autocommit($db, true);
?>
```

### 4. Move Project to Server Directory

- **XAMPP**: Copy folder to `C:/xampp/htdocs/`
- **WAMP**: Copy folder to `C:/wamp64/www/`
- **LAMP**: Copy folder to `/var/www/html/`

### 5. Access the Application

- **User Interface**: `http://localhost/food/`
- **Admin Panel**: `http://localhost/food/admin/`

## 👤 Default Login Credentials

### Admin Login
- **URL**: `http://localhost/food/admin/`
- **Username**: `admin`
- **Password**: `admin123`

### Test User Login
- **Username**: `eric`
- **Password**: `password`

## 📁 Project Structure

```
food/
├── admin/                      # Admin panel files
│   ├── css/                    # Admin stylesheets
│   ├── js/                     # Admin JavaScript files
│   ├── images/                 # Admin images
│   ├── dashboard.php           # Admin dashboard
│   ├── all_users.php           # User management
│   ├── all_restaurant.php      # Restaurant management
│   ├── all_menu.php            # Menu management
│   ├── all_orders.php          # Order management
│   ├── order_billing.php       # Order billing panel
│   └── ...
├── connection/                 # Database connection
│   └── connect.php             # DB configuration
├── css/                        # User interface stylesheets
├── js/                         # User interface JavaScript
├── images/                     # User interface images
├── DATABASE FILE/              # SQL database file
│   └── onlinefoodphp.sql      # Database structure & data
├── index.php                   # Homepage
├── login.php                   # User login
├── registration.php            # User registration
├── restaurants.php             # Restaurant listing
├── dishes.php                  # Menu items
├── checkout.php                # Checkout page
├── your_orders.php             # User order history
├── order_invoice.php           # Printable invoice
└── README.md                   # This file
```

## 🔐 Security Features

- Session management with timeout
- SQL injection prevention (prepared statements)
- XSS protection
- CSRF protection
- Password hashing (MD5 - recommended to upgrade to bcrypt)
- Admin authentication and authorization
- Input validation and sanitization

## 📊 Database Schema

### Main Tables
- `users` - User accounts and profiles
- `admin` - Admin accounts
- `restaurant` - Restaurant information
- `res_category` - Restaurant categories
- `dishes` - Menu items
- `users_orders` - Customer orders
- `remark` - Order status updates

## 🎨 Features Highlights

### Order Billing System
- Professional invoice generation
- Printable order details
- Company and customer information
- Order status tracking
- Payment information

### User Management
- Secure user deletion with transaction support
- User profile editing
- Order history tracking
- Address management

### Restaurant Management
- Category-based organization
- Restaurant profiles with images
- Operating hours management
- Contact information

## 🐛 Known Issues & Solutions

### Issue: Users reappearing after deletion
**Solution**: Fixed with transaction-based deletion and proper session handling.

### Issue: Blank billing page
**Solution**: Fixed session authentication to match admin panel requirements.

## 📝 Development Notes

- All user deletions are permanent and use database transactions
- Cache prevention headers implemented for real-time data
- Responsive design for mobile compatibility
- Print-optimized invoice layouts

## 🤝 Contributing

This is a college project. Contributions, issues, and feature requests are welcome!

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is open source and available under the MIT License.

## 👨‍💻 Author

**SuSrUt123**
- GitHub: [@SuSrUt123](https://github.com/SuSrUt123)
- Project: [BCA4thsemProject](https://github.com/SuSrUt123/BCA4thsemProject)

## 🙏 Acknowledgments

- Bootstrap for responsive design
- Font Awesome for icons
- jQuery for DOM manipulation
- All open-source contributors

## 📞 Support

For support, email: billing@quickbites.com

---

**Note**: Remember to update database credentials and secure sensitive information before deploying to production. Never commit `connection/connect.php` with real passwords to public repositories.

## 🔄 Version History

- **v2.0** - Complete user deletion fix, billing panel, invoice system
- **v1.0** - Initial release with basic functionality

---

Made with ❤️ for BCA 4th Semester Project
