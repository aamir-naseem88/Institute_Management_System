# Academy Management System

A comprehensive web-based application for managing an institute's operations, built with PHP, MySQL, and Bootstrap.

## Features

- **User Authentication**: Secure login system with session management.
- **Student Management**: Add, edit, delete, and view student records.
- **Course Management**: Manage courses with details like name, duration, and fees.
- **Enrollment Management**: Enroll students in courses and track enrollment status.
- **Fee Management**: Collect and track fees for enrollments.
- **Reports**: Generate custom reports on students, enrollments, courses, and fees with date filters.
- **Responsive Design**: Mobile-friendly interface using Bootstrap.

## Technologies Used

- **Backend**: PHP 7+
- **Database**: MySQL
- **Frontend**: HTML, CSS, Bootstrap 5, JavaScript

## Installation

1. **Prerequisites**:
   - Server with PHP and MySQL.
   - Web browser.

2. **Setup if you want to add to local server**:
   - Clone or download the project to your `htdocs` folder (e.g., `C:\xampp\htdocs\academy_system02`).
   - Start XAMPP and ensure Apache and MySQL are running.

3. **Database Setup**:
   - Open phpMyAdmin (http://localhost/phpmyadmin).
   - Create a new database named `academy_system02`.
   - Import the SQL file (if provided) or run the following queries:

     ```sql
     CREATE TABLE users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         username VARCHAR(50) UNIQUE NOT NULL,
         password VARCHAR(255) NOT NULL,
         role ENUM('admin', 'user') DEFAULT 'user'
     );

     CREATE TABLE students (
         student_id INT AUTO_INCREMENT PRIMARY KEY,
         student_name VARCHAR(100) NOT NULL,
         phone VARCHAR(15),
         address TEXT,
         date_of_birth DATE
     );

     CREATE TABLE courses (
         course_id INT AUTO_INCREMENT PRIMARY KEY,
         course_name VARCHAR(100) NOT NULL,
         course_duration VARCHAR(50),
         course_fee DECIMAL(10,2)
     );

     CREATE TABLE enrollments (
         enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
         student_id INT NOT NULL,
         course_id INT NOT NULL,
         enrollment_date DATE NOT NULL,
         enrollment_status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
         FOREIGN KEY (student_id) REFERENCES students(student_id),
         FOREIGN KEY (course_id) REFERENCES courses(course_id)
     );

     CREATE TABLE fees (
         fee_id INT AUTO_INCREMENT PRIMARY KEY,
         enrollment_id INT NOT NULL,
         amount_paid DECIMAL(10,2) NOT NULL,
         payment_date DATE NOT NULL,
         notes TEXT,
         FOREIGN KEY (enrollment_id) REFERENCES enrollments(enrollment_id)
     );

     -- Insert default admin user (password: admin)
     INSERT INTO users (username, password, role) VALUES ('root', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
     ```

4. **Configuration**:
   - Update `config/config.php` with your database credentials if needed.

5. **Access the Application**:
   - Open your browser and go to `http://localhost/academy_system02`.
   - Login with username: `root`, password: `admin`.

## Usage

- **Dashboard**: Overview after login.
- **Manage Students/Courses/Enrollments**: Use the sidebar navigation.
- **Reports**: Generate filtered reports from the Custom Reports page.
- **Logout**: Securely end your session.

## Project Structure

```
academy_system02/
├── assets/
│   ├── img/
│   │   └── institute_bg.jpg
│   └── styles.css
├── Classes/
│   ├── Courses.php
│   ├── Enrollments.php
│   ├── Fees.php
│   ├── Reports.php
│   └── Students.php
├── config/
│   └── config.php
├── layout/
│   ├── footer.php
│   ├── header.php
│   └── sidebar.php
├── pages/
│   ├── add_course.php
│   ├── add_student.php
│   ├── collect_fee.php
│   ├── courses.php
│   ├── custom_report.php
│   ├── delete_course.php
│   ├── delete_student.php
│   ├── edit_course.php
│   ├── edit_student.php
│   ├── enroll_student.php
│   ├── login.php
│   ├── report_student.php
│ 
├── index.php
└── README.md
```

## Contributing

1. Fork the repository.
2. Create a feature branch.
3. Commit your changes.
4. Push to the branch.
5. Open a Pull Request.

## License

This project is licensed under the MIT License.

## Contact

For questions or support, please contact [aamir.naseem.wd@gmail.com] or open an issue on GitHub.
Visit aamirnaseem.com