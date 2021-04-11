# Mini web-app
This is a mini web-app with only two pages (login page and user list page)
and simple REST API (two endpoints). All api-users and list of users are stored
in MySQL database.


### Local deployment
1. Make sure you have MySQL server installed and running.
2. Import initial data into database running in command line from project
directory (where README.MD file is) (replace [DB_USERNAME]):
```bash
mysql -u [DB_USERNAME] -p < db_dump/ac_db_v1.sql
```
3. Rename file **db_connection.php.example** (inside **config** directory) to **db_connection.php** and edit it to fill in credentials for database.
4. Configure Apache web server to point DocumentRoot to **public** directory
inside project directory (where README.MD file is) and restart Apache web server.  
(Or run development web server inside project directory:  
**php -S localhost:8000 -t public/**  
Development web server starts on port 8000: [http://localhost:8000/login.html](http://localhost:8000/login.html) )
5. Open browser and navigate to [http://localhost/login.html](http://localhost/login.html) and enjoy :)
