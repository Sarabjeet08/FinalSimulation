# 🛍️ Mini Product Catalog

A simple product catalog system built with PHP and MySQL that allows users to manage their products with image upload functionality.

## ✨ Features

- 🔐 User authentication (login/logout)
- 📝 User registration
- 👤 User profile management
- 📸 Add products with images
- ✏️ Edit existing products
- 👀 View product list
- 🗑️ Delete products
- 📱 Responsive design
- 🔒 Secure file upload handling
- 🔑 Session-based authentication
- 🔄 Smart routing based on authentication status

## 📋 Requirements

- ⚙️ PHP 7.4 or higher
- 🗄️ MySQL 5.7 or higher
- 🌐 XAMPP or similar web server
- 🖥️ Web browser

## 🚀 Installation

1. Clone or download this repository to your web server's document root (e.g., `htdocs` folder in XAMPP)
2. Create a new MySQL database named `mini_catalog`
3. Import the following SQL to create the required tables:

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  profile_image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  price DECIMAL(10,2),
  image_path VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
```

4. Create a user account by inserting a record into the users table:

```sql
INSERT INTO users (username, password) VALUES ('admin', '$2y$10$YourHashedPasswordHere');
```

> **Note:** Make sure to hash the password using PHP's `password_hash()` function.

5. Create an `uploads` directory in the project root and make it writable:

```bash
mkdir uploads
chmod 777 uploads
```

## 💻 Usage

1. Access the application through your web browser (e.g., `http://localhost/index.php`)
   - The system will automatically redirect you to the appropriate page based on your login status
2. Register a new account or log in with existing credentials
3. Update your profile information and image
4. Add products using the "Add New Product" button
5. View and manage your products in the dashboard
6. Edit or delete products as needed

## 🔒 Security Features

- 🔐 Password hashing
- 🛡️ Prepared statements for SQL queries
- 📝 File type validation
- 📏 File size limits
- 🔑 Session-based authentication
- 🧹 Input sanitization
- 🚫 XSS prevention
- 🔒 CSRF protection
- 🛡️ SQL injection prevention

## 📁 File Structure

```
/mini-catalog/
├── 📄 index.php              # Main entry point with smart routing
├── 📄 login.php
├── 📄 register.php
├── 📄 logout.php
├── 📄 dashboard.php
├── 📄 products.php
├── 📄 profile.php
├── 📄 add_product.php
├── 📄 edit_product.php
├── 📄 delete_product.php
├── 📄 db.php
├── 📄 session.php
├── 📄 db.sql
├── 📁 includes/
│   ├── 📄 header.php
│   └── 📄 footer.php
├── 📁 uploads/
│   └── (images go here)
├── 📁 css/
│   └── 📄 style.css
└── 📄 README.md
```

## 📝 Final Exam SIMULATION

### Part 1: Basic PHP Concepts

1. Which PHP function is used to display text on the screen?
   - A) echo
   - B) printText
   - C) show
   - D) printString
   - **✅ Answer: A**

2. Which superglobal contains data sent via the GET method?
   - A) $_POST
   - B) $_GET
   - C) $_SESSION
   - D) $_SERVER
   - **✅ Answer: B**

3. What does isset($_POST['name']) check?
   - A) If the variable is a string
   - B) If the variable exists and is not null
   - C) If the variable is empty
   - D) If the variable is a number
   - **✅ Answer: B**

4. Which function starts a PHP session?
   - A) start_session()
   - B) begin_session()
   - C) session_start()
   - D) session_open()
   - **✅ Answer: C**

### Part 2: Database and Control Structures

5. Which command connects to MySQL using MySQLi?
   - A) mysqli_connect()
   - B) mysql_connect()
   - C) db_connect()
   - D) connect_mysql()
   - **✅ Answer: A**

6. Which control structure repeats code while a condition is true?
   - A) if
   - B) switch
   - C) while
   - D) foreach
   - **✅ Answer: C**

7. What does the empty() function return when the value is 0?
   - A) true
   - B) false
   - C) null
   - D) empty string
   - **✅ Answer: A**

8. How do you define an associative array in PHP?
   - A) array("key" => "value")
   - B) ["key", "value"]
   - C) ("key" = "value")
   - D) array("value" => "key")
   - **✅ Answer: A**

### Part 3: File Handling and Security

9. What is the purpose of the fopen() function?
   - A) Open a database
   - B) Open a connection
   - C) Open a file
   - D) Open a session
   - **✅ Answer: C**

10. Which JavaScript function performs an AJAX request?
    - A) fetch()
    - B) request()
    - C) sendRequest()
    - D) ajaxPost()
    - **✅ Answer: A**

11. Which SQL command inserts data into a table?
    - A) ADD
    - B) INSERT INTO
    - C) UPDATE
    - D) APPEND
    - **✅ Answer: B**

12. In PDO, which command is used to prevent SQL injection?
    - A) prepare()
    - B) validate()
    - C) filter()
    - D) querySecure()
    - **✅ Answer: A**

### Part 4: Advanced PHP Concepts

13. Which function closes a MySQLi connection?
    - A) mysql_close()
    - B) mysqli_close()
    - C) db_disconnect()
    - D) close_connection()
    - **✅ Answer: B**

14. What does the htmlspecialchars() function do?
    - A) Remove spaces
    - B) Escape HTML characters
    - C) Remove HTML tags
    - D) Check if it's a string
    - **✅ Answer: B**

15. What is the best way to validate a required form field?
    - A) isset() and empty()
    - B) validate()
    - C) secure()
    - D) post_check()
    - **✅ Answer: A**

16. What keyword is used to create an object from a class in PHP?
    - A) include
    - B) new
    - C) open
    - D) class
    - **✅ Answer: B**

### Part 5: Object-Oriented PHP

17. What is polymorphism in PHP?
    - A) Functions with the same name in different scopes
    - B) Classes with multiple names
    - C) Objects with multiple identities
    - D) Methods with different behaviors in subclasses
    - **✅ Answer: D**

18. How is a constructor defined in modern PHP classes?
    - A) function construct()
    - B) function __construct()
    - C) constructor()
    - D) init()
    - **✅ Answer: B**

19. Which function sets a cookie in PHP?
    - A) setcookie()
    - B) cookie_set()
    - C) create_cookie()
    - D) cookie()
    - **✅ Answer: A**

20. Which function checks if a file was uploaded via a form?
    - A) is_uploaded_file()
    - B) file_exists()
    - C) upload_check()
    - D) form_file()
    - **✅ Answer: A**


