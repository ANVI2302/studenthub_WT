CHARUSAT STUDENT HUB - PHP + MySQL starter

LOCAL XAMPP SETUP
1. Install XAMPP and start Apache and MySQL.
2. Copy this entire folder to C:\xampp\htdocs\student_hub (Windows) or your XAMPP htdocs folder.
3. Open http://localhost/phpmyadmin
4. Import database.sql. It creates the student_hub database and tables.
5. Check config.php: XAMPP defaults are host localhost, database student_hub, user root, blank password.
6. Open http://localhost/student_hub/index.html
7. Register at register.php, then login at login.php.

ADMIN
A registered account is student by default. To grant admin, in phpMyAdmin run:
UPDATE users SET role='admin' WHERE student_id='YOUR_STUDENT_ID';
Then sign out and sign in again, and visit admin.php.

DEPLOYMENT
This project requires a PHP hosting provider with MySQL/MariaDB. Upload all files to the provider's web root, create a database and database user in its control panel, import database.sql (omit CREATE DATABASE/USE if provider assigns a database name), and update config.php with the provider's host, database, username and password. Keep config.php private and do not commit credentials to public source control. Set HTTPS on. Vercel static hosting alone does not execute PHP.

NOTES
- Login/register/profile and database forms are connected to MySQL.
- Existing static event/FAQ content is retained from the supplied frontend. Event registration and live event management would require further backend wiring.
- Do not use this sample for sensitive production data without additional security review.
