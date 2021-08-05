# Cart Management

Cart Management is a simple project for price a cart of products, accept multiple products, combine offers, and display a total detailed bill in different currencies

> Note: This package is an integration for the [Currency](https://github.com/Formicka/exchangerate.host) library

## Requirements
- PHP >= 7.2
- Laravel >= 6.0

## Installation

```
composer install
```

## Database And Configuration

Create database for project and add to .env file and add C_CURRENCY key to .env with Default Currency and run this commaned :

```
php artisan config:cache
php artisan config:clear
php artisan migrate:refresh --seed
```

## Usage

### 1. Products All
To show all products

```php
use link /api/v1/products/all
```
This will return the collection of products.

### 2. Show product
To show specific product

```php
use link /api/v1/products/show/{product_id}
```
This will return the product details.

### 3. Show Cart
To show cart data for convert price to another currency will send parameter key: currency and value: currency code Like ( EGP ) 

```php
use link /api/v1/cart
```
This will return the cart data.

### 4. Add To Cart
To add products to cart will send product id with link and will send parameter key: quantity

```php
use link /api/v1/add-to-cart/{product_id}
```

### 4. Update Cart
To update products quantity in cart will send parameter key: product_id and key: quantity

```php
use link /api/v1/update-cart
```
### Additional Method: 
Auth Login

```php
use link /api/v1/auth/login
```
> Note: This method using the [JWT](https://github.com/tymondesigns/jwt-auth) library


For a list of all supported symbols [here](https://api.exchangerate.host/symbols "List of supported symbols") and list of crypto currencies [here](https://api.exchangerate.host/cryptocurrencies)

## License
The MIT License (MIT). Please see [LICENSE](../master/LICENSE) for more information.