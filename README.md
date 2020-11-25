# BileMo API

OpenClassRooms 7th Project
Build an API REST with Symfony

## Prerequisite
* A Web Server (Apache, Nginx...)
* PhP 7.3
* Composer
* A database engine (MySql, PostgreSQL...)
* [OpenSSL command line tool](https://www.openssl.org/docs/man1.0.2/man1/openssl.html) or already generated SSH Keys

## Generate OpenSSH Keys
* Generate private key :`openssl genrsa -out private.pem -aes256 4096`
* Generate public key : `openssl rsa -pubout -in private.pem -out public.pem`


## Installation
* Clone or download the project
* Go to project folder in a terminal
* Type `composer install`
* move SSH keys (`private.pem` and `public.pem`) in `config/jwt/`
* Configure a new host in your web server with `public/` folder as DocumentRoot