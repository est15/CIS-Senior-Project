# CIS-Senior-Project
The GitHub containing my entire University of Akron Computer Information Systems - Cybersecurity degree's capstone project. 

## Project Sections:
A. **[Server Setup and Configuration](#-Server-Setup-&-Configuration)**
- [Installations](#Installations)
- [Configurations](#Configurations)
- [Verifying Operations](#Verifying-Operations)


B. **[Mileage Master Schema](#Mileage-Master-Database-Configuration])**


C. **[PHP Sessions Setup](#Configuring-PHP-Sessions)**

D. **[Switching from Vulnerable to Secured Application](#Switch-Vulnerable-to-Secured-Application)**


# Server Setup & Configuration
Follow the below secitions to configure the [Ubuntu Server 22.04.4](https://releases.ubuntu.com/jammy/#:~:text=at%20all%20unsure.-,Server%20install%20image,-The%20server%20install) to host the web application.
 
## Installations
Install all of the below.

### Apache:
```shell
sudo apt install apache2
```

### MySQL:
```shell
sudo apt install mysql-server
```
*reference configurations [MySQL Setup](#MySQL-Server-Setup) section for walkthrough of setting up the MySQL server*

### PHP
Install all of the necessary PHP components:
```bash
sudo apt install php php-mysql libapache2-mod-php
```
 
## Configurations

### Firewall Configurations
Enable Uncomplicated Firewall:
```bash
sudo ufw enable
```

Allow SSH Access:
```bash
sudo ufw allow OpenSSH
```

Allow HTTP Traffic:
```bash
sudo ufw allow in "Apache"
```

### Configure Web Server
Create a Mileage Master directory within /var/www:
```bash
sudo mkdir /var/www/mileagemaster
```

Change ownership to the dev-admin user:
```bash
sudo chown -R $USER:$USER /var/www/mileagemaster
```

### Configure Virtual Host
Create a new Apache configuration file for Mileage Master domain:
```bash
sudo vim /etc/apache2/sites-available/mileagemaster.conf
```

mileagemaster.conf:
```bash
<VirtualHost *:80>
	ServerName MileageMaster.com
	ServerAlias www.MileageMaster.com
	ServerAdmin dev-admin@localhost
	DocumentRoot /var/www/mileagemaster
	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Enable the Virtual Host:
```bash
sudo a2ensite mileagemaster
```

Disable the default Apache Website:
```bash
sudo a2dissite 000-default
```

Reload Apache:
```bash
sudo systemctl reload apache2
```

### ServerName Global Error Fix
If the ServerName Global Error comes up when attempting verify Apache's operations, simply add these two lines to Apache's configuration file:
```bash
echo -e "# Global ServerName Directive\nServerName 127.0.0.1" >> /etc/apache2/apache2.conf
```

Reload Apache:
```bash
sudo systemctl reload apache2
```

### MySQL Server Setup
First thing is to run the **mysql_secure_installation** script, but it errors out wihtout first adjusting MySQL's root user authentication.

Start MySQL Prompt:
```bash
sudo mysql
```

Alter root's Authentication:
```mysql
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';
```

Run Secure Script:
```bash
sudo mysql_secure_installation
```

Utilize the following for the script's prompts:
	1. Root Password: password
	2. Validate Password Plugin: NO
	3. Disable Anonymous Logins: YES
	4. Remote Root Logins: YES
	5. Removal of Test Database: YES


## Verifying Operations
Reference the below section to illustrates what tests were performed to ensure the successful operation of the server. 

### Verify Firewall:
```bash
sudo ufw status
```

### Verify Apache Server:
```bash
sudo apache2ctl configtest
```

**NOTE: [Set ServerName directive Globally Error Fix](#ServerName-Global-Error-Fix)**

### Verify MySQL Server:
```bash
sudo mysql -u root -p -h localhost
``` 

### Verify PHP is Installed:
```bash
php -v
```

### Verify PHP Processing
Create an info.php file in Mileage Master's root directory:
```php
<?php phpinfo(); ?>
```

Accessing info.php in a web browser should output a page with the current PHP installation's details. 

# Mileage Master Database Configuration
First log onto the MySQL Server:
```bash
mysql -u root -p -h localhost
```

## Create Mileage Master Schema
```mysql
CREATE DATABASE mileage_master;
```

## Create Users Tables
```mysql
CREATE TABLE `users` (
	`id` int(10) UNSIGNED NOT NULL,
	`fullname` VARCHAR(255) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`username` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

**Add Primary Key && Unique Key:**
```mysql
ALTER TABLE `users`
	ADD PRIMARY KEY (`id`);
```

**Ensure ID auto increments:**
```mysql
ALTER TABLE `users`
	MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
```

## Create Mileages Table
```mysql
CREATE TABLE `mileages` (
	`id` int(10) UNSIGNED NOT NULL,
	`username` VARCHAR(255) NOT NULL,
	`VIN` VARCHAR(255) NOT NULL,
	`mileage` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

**Configure Primary Key:**
```mysql
ALTER TABLE 'mileages' ADD PRIMARY KEY ('id');
```

**Configure Auto Increment:**
```mysql
ALTER TABLE 'mileages' MODIFY 'id' int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
```
# Configuring PHP Sessions
## Create the Sessions Table
```mysql
CREATE TABLE `sessions` (
	`session_id` varchar(255) NOT NULL,
	`id` int(10) UNSIGNED NOT NULL,
	`login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

**Add Primary Key:**
```mysql
ALTER TABLE `sessions`
	ADD PRIMARY KEY (`session_id`);
```

**Make sure to update PHP-MySQL-Sessions database.class.php:**
```php
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "password");
define("DB_NAME", "mileage_master");
```
insert the above into the *// Define database configuration* section

# Switch Vulnerable to Secured Application
The [Application](Application) directory contains both secured and vulnerable code. This is for testing purposes. To assist with this I created a bash script [swap.sh](swap.sh). 

Just run *swap.sh* from CLI, the script will detect if either secured or vulnerable preceeds index.php. The script then replaces the current index.php, Mileage.php, and Search.php with the opoosite of what currently preceeds [vuln/secure]-index.php.









