# BileMo API

OpenClassRooms 7th Project
Build an API REST with Symfony

[![SonarCloud](https://sonarcloud.io/images/project_badges/sonarcloud-white.svg)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P7-API_REST)

[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P7-API_REST&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P7-API_REST)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P7-API_REST&metric=alert_status)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P7-API_REST)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P7-API_REST&metric=security_rating)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P7-API_REST)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P7-API_REST&metric=bugs)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P7-API_REST)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P7-API_REST&metric=code_smells)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P7-API_REST)
[![Duplicated Lines (%)](https://sonarcloud.io/api/project_badges/measure?project=OSEvohe_OC-P7-API_REST&metric=duplicated_lines_density)](https://sonarcloud.io/dashboard?id=OSEvohe_OC-P7-API_REST)

Code Climate  
[![Maintainability](https://api.codeclimate.com/v1/badges/3c10ea86ff7ba94bf013/maintainability)](https://codeclimate.com/github/OSEvohe/OC-P7-API_REST/maintainability)

## Prerequisite
* A Web Server (Apache, Nginx...)
* PhP 7.3
* Composer
* A database engine (MariaDB, MySql, PostgreSQL...)
* [OpenSSL command line tool](https://www.openssl.org/docs/man1.0.2/man1/openssl.html) or already generated SSH Keys

## Installation
* Clone or download the project
* Go to project folder in a terminal
* Type `composer install`
* Configure a new host in your web server with `public/` folder as DocumentRoot

### Database setup
* Copy `.env` to `.env.local` and edit database parameters
* Initialize the database : 
  * `php bin/console doctrine:database:create`
  * `php bin/console make:migration`
  * `php bin/console doctrine:migrations:migrate`
  
### JWT Authentication Setup
* Generate private key :`openssl genrsa -out private.pem -aes256 4096`
* Generate public key : `openssl rsa -pubout -in private.pem -out public.pem`
* move SSH keys (`private.pem` and `public.pem`) to `config/jwt/`
* set passphrase used to generate the keys in your env.local file (`JWT_PASSPHRASE`)

### Initial database data
* To start with no data : `php bin/console doctrine:fixtures:load --group=starting_users`
* To start with samples data : `php bin/console doctrine:fixtures:load`

## Secure your site
### Admin access
By default, you can retrieve an admin token :  
JSON : `{"username":"admin","password":"superPassword34!"}`   
URL :  `/api/login_check`   
METHOD :  `POST`

#### Other users
Samples data come with 1 additional users :  
JSON : `{"username":"user1","password":"PassWord01!"}`   
URL :  `/api/login_check`   
METHOD :  `POST`