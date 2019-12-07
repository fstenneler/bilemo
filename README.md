# Bilemo
## API Rest for the Bilemo phones based on the API Plaform bundle

Online version [here](http://bilemo.orlinstreet.rocks).

For more informations about API Platform, check the official documentation [here](https://api-platform.com/docs).

## Certifications

### Symfony Insights
[![SymfonyInsight](https://insight.symfony.com/projects/af37613e-6fa3-4203-9ebd-ae9978c0b14d/big.svg)](https://insight.symfony.com/projects/af37613e-6fa3-4203-9ebd-ae9978c0b14d)

### Codacy
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/e2c03f2f71994d3a9689143e48c8b17b)](https://www.codacy.com/manual/fstenneler/snowtricks?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=fstenneler/snowtricks&amp;utm_campaign=Badge_Grade)

## Main features
- JWT Authentication
- JSON and JSON-LD
- Pagination
- HTTP Cache management
- Backoffice to manage users, products and customers

## Setup instructions

### Download the repository

#### Either from the url
[Download repository using the web URL](https://github.com/fstenneler/bilemo/archive/master.zip)

#### Or from Git
    git clone https://github.com/fstenneler/bilemo.git

### Download and install Composer
[Download Composer](https://getcomposer.org/download/)

### Update dependencies

#### In command line from the project directory
    composer update

### Setup the .env file (at the root of the project) with your own parameters

    DATABASE_URL=mysql://user:password@hostName:port/bilemo

### Create database and load data
In command line from the project directory

#### Create database
    php bin/console doctrine:database:create

#### Create tables and relations
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate

#### Load initial data
    php bin/console doctrine:fixtures:load

### Setup authentication
The authentication process uses the LexikJWTAuthenticationBundle. Click [here](https://github.com/lexik/LexikJWTAuthenticationBundle) for more informations.

#### Set your own app passphrase
    # .env

    JWT_PASSPHRASE=yourpassphrase

#### Generate the SSH keys

    $ mkdir -p config/jwt
    $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

### Deploy the app

#### Change the APP_ENV and APP_DEBUG values in the .env file
    APP_ENV=prod
    APP_DEBUG=0

#### Empty cache
    php bin/console cache:clear

#### Upload all project files on your server

#### In case or errors, run the debug mode in the .env file
    APP_DEBUG=1

### Starting with the app

#### Run fonctionnal tests
    php bin/phpunit tests

#### Backoffice
Route to acces to backoffice : /backoffice.
An admin user was created :

    Username : admin
    Password : admin
    
Please edit this user after your first connection with a new password.

#### API documentation
Route do access to the API documentation : /api

#### API routes
- /api/products
- /api/brands
- /api/categories
- /api/colors
- /api/media
- /api/customers
- /api/customer_addresses
