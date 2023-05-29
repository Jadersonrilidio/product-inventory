# Product Inventory Backend

Product Inventory: Store, list and delete products from inventory.

## About

Webapp URL: [https://jay-product-inventory.vercel.app/](https://jay-product-inventory.vercel.app)

You can access the frontend part on github [here](https://github.com/Jadersonrilidio/product-inventory-frontend)

The Product Inventory API could be accessed directly through the URL: [https://jay-product-inventory.000webhostapp.com/api/products](https://jay-product-inventory.000webhostapp.com/api/products)

Database [DER Schema here](https://github.com/Jadersonrilidio/product-inventory-backend/blob/master/resources/img/porduct_inventory_DER.png).

There is also available an alternative webapp built entirely from the backend. It could be seen [here](https://jay-product-inventory.000webhostapp.com) Although it is recommended for evaluation purposes (in my opinion, due to "messy" within frontend files, poor view rendering template engine and lack of testing). Please use the main webapp in Vue **[here!](https://jay-product-inventory.vercel.app)**.

## Stack

PHP 7.4 and MySQL 5.7

## How the app works is a nutshell

The application was built as a classic MVC model;

The entrypoint is the `index.php` file located at the `public` folder:

```php
<?php

declare(strict_types=1);

use Jayrods\ProductInventory\Http\Core\Request;
use Jayrods\ProductInventory\Http\Core\Router;
use Jayrods\ProductInventory\Http\Middleware\MiddlewareQueue;
use Psr\Container\ContainerInterface;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

/** @var ContainerInterface */
$diContainer = require CONFIG_DIR . 'dependencies.php';

$routes = require CONFIG_DIR . 'routes.php';

$router = new Router(
    new Request(),
    new MiddlewareQueue(),
    $diContainer,
    $routes
);

$router->handleRequest()->sendResponse();
```

The Application Core is located at `src/Http/Core`, and is represented by the classes `Request`, `Router` and `Response`;

As could be seen above in the `index.php`, the `Router` receives a:
    - `Request` instance;
    - dependency injection container instance `$diContainer`;
    - queue of middlewares represented by `MiddlewareQueue` instance;
    - Map of routes => parameters, found at `config/routes.php` in the form 'route' => ['Controller class', 'method', 'required middlewares'].

The `Router::handleRequest()` method uses the `httpMethod` and `uri` properties from the `Request` to find if the route exists in the `config.routes.php` map;

if the route is not found, it returns the `fallback` route parameters, set in the `config/routes.php`, as shown bellow;

```php
    // Web Fallback Route
'fallback' => [Jayrods\ProductInventory\Http\Controller\ProductController::class, 'index', []]
```

if found, it returns the route parameters, represented by an array of Controller class, method, and middlewares to be executed, as the example below:

```php
    // Web Route
'GET|/' => [Jayrods\ProductInventory\Http\Controller\ProductController::class, 'index', ['session', 'auth']]
```

With the route parameters in hand, the `Router::handleRequest()` method:
- Execute the middlewares queue, adding the route middlewares to it;
- Instantiate the Controller class, using a dependency injection container `$diContainer`;
- Call the Controller method, injecting the `Request` instance into it and returning a `Response::class` object;

Then, the `Response::sendResponse()` method is called, forwarding the response content to the client, setting the Headers, HTTP code, Content-Type, CORS Policy (and all sort of settings needed), thus completing the app lifecycle.

### Project folder structure

**OBS:** The filesystem used in this project was based in the [php-pds/skeleton](https://github.com/php-pds/skeleton) standards proposal, available at github.

The `src` folder structure is briefly explained in this JSON schema bellow:

```json
{
    "Entity" : "Classes which represents the database entities. ValueObjects also could be stored inside Entity.",
    "Helper": {
        "Traits": "Traits that could be used in a global scope are stored here.",
        "Helper classes are stored in the Helper folder."
    },
    "Http": {
        "Controller": {
            "API": "Store the API related Controllers.",
            "Validator": "User input validation classes are stored here.",
            "Web Controllers are stored at the root of the Controller folders."
        },
        "Core": "The app Core classes are stored here.",
        "Middlewares": "All Middleware classes and interface + MiddlewareQueue are stored here."
    },
    "Infrastructure": {
        "Database": "Database Connection abstraction classes are stored here.",
        "Classes related to the app infrastructure are stored here."
    },
    "Repository": "All the database logic and interaction is abstracted by Repository classes and stored here."
}
```

- Entities from database are represented by an `Entity` class, located at the `src/Entity` folder;
- Controllers are located at `src/Http/Controller` path, and the `src/Http/Controller/Api` path holds the API controllers;
- User input validation is abstracted by the `Validator` classes, located at `src/Http/Controller/Validator` folder, which validate inputs according to the domain rules and could take an action in case of success or fail;
- Database connection abstraction is located at `src/Infrastructure/Database`;
- All the Database logic and interaction is abstracted by the Repository classes, located at `src/Repository`;
- Each `Entity::class` may have a respective Repository-specific folder, containing a `EntityRepository` interface and its implementations, as example `MysqlEntityRepository::class` or `JsonEntityrepository::class`;

**In any case of doubt or questions, please do not hesitate to contact the author** [jaderson.rodrigues@yahoo.com](jaderson.rodrigues@yahoo.com).

## Adding a new product type on backend

**1 -** Create a new product-type Entity on the path `src/Entity` which extends the `Jayrods\ProductInventory\Entity\Product::class`;

```php
<?php

namespace Jayrods\ProductInventory\Entity;

use Jayrods\ProductInventory\Entity\Product;

class NewProductType extends Product
{
    // Properties and Methods goes here ...
}
```

**2 -** Create a `ProductTypeRepository` folder on the path `src/Repository`, the interface `ProductTypeRepository::class` and class `MysqlProductTypeRepository::class` within it, following the models of the other product-derived repositories;

```php
<?php

namespace Jayrods\ProductInventory\Repository\NewProductTypeRepository;

use Jayrods\ProductInventory\Entity\NewProductType;

interface NewProductTypeRepository
{
    public function save(NewProductType $NewProductType): bool;

    public function all(): array;
}
```

```php
<?php

namespace Jayrods\ProductInventory\Repository\NewProductTypeRepository;

use Jayrods\ProductInventory\Entity\NewProductType;
use Jayrods\ProductInventory\Repository\NewProductTypeRepository\NewProductTypeRepository;
use Jayrods\ProductInventory\Repository\Repository;
use PDO;

class MysqlNewProductTypeRepository extends Repository implements NewProductTypeRepository
{
    // Properties and Methods goes here ...
}
```

**3 -** Create a `ProductTypeValidator::class` for specific attributes validation, again following the models of the other product-derived validators;

```php
<?php

namespace Jayrods\ProductInventory\Http\Controller\Validator\ProductValidator;

use Jayrods\ProductInventory\Http\Core\Request;
use Jayrods\ProductInventory\Http\Controller\Validator\ProductValidator\AbstractProductValidator;

class NewProductTypeValidator extends AbstractProductValidator
{
    // Properties and Methods goes here ...
}
```

**4 -** Rewrite the `MysqlProductRepository::all()` method's SQL query, properly replacing the `{{placeholders}}`: 

```sql
SELECT products.sku, products.name, products.price, products.type,
        dvds.size,
        books.weight,
        furniture.height, furniture.width, furniture.length,
        {{NewProducts}}.{{NewAttribute}}
    FROM products
        LEFT JOIN dvds ON dvds.sku = products.sku
        LEFT JOIN books ON books.sku = products.sku
        LEFT JOIN furniture ON furniture.sku = products.sku
        LEFT JOIN {{NewProducts}} ON {{NewProducts}}.sku = products.sku
    ORDER BY products.sku ASC;
```

**NOTE:** This approach could be considered a violation of the Open-Closed SOLID principle ("Software entities ... should be open for extension, but closed for modification."), therefore the alternative **[presented here](https://github.com/Jadersonrilidio/product-inventory-backend/tree/master#improvement-proposal-1)** could be done to fix it.

... and your new product type in the backend is ready to go!

**OBS:** Remember to add the product Enum type and the product-type on the database as well. To accomplish that, you can use the following SQL queries, properly replacing the `{{placeholders}}`:

```sql
ALTER TABLE IF EXISTS products (
    ALTER COLUMN type ENUM('DVD', 'Book', 'Furniture', '{{NewProduct}}') NOT NULL
);

CREATE TABLE IF NOT EXISTS {{NewProducts}} (
    sku CHAR(32) NOT NULL PRIMARY KEY,
    {{NewAttribute}} INT NOT NULL,
    CONSTRAINT FK_sku_{{NewProducts}} FOREIGN KEY (sku) REFERENCES products(sku)
        ON DELETE CASCADE
        ON UPDATE NO ACTION
);

DELIMITER $$

CREATE PROCEDURE insert_into_products_{{NewProducts}}_tables
    (sku CHAR(32), name VARCHAR(128), price INT, type ENUM('DVD', 'Book', 'Furniture'), {{NewAttribute}} INT)
    BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            RESIGNAL;
        END;
        START TRANSACTION;
        INSERT INTO products(sku, name, price, type) VALUES(sku, name, price, type);
        INSERT INTO {{NewProducts}}(sku, {{NewAttribute}}) VALUES(sku, {{NewAttribute}});
        COMMIT;
    END $$

DELIMITER ;
```

## Project Setup on Local Machine

**1 -** Clone the project from Github:

```sh
$ git clone https://github.com/Jadersonrilidio/product-inventory-backend
```

**2 -** Install composer and its dependencies:

```sh
$ composer install
```

**3 -** Create `.env` file and set the following environment variables:

```sh
DB_DRIVER=mysql
DB_NAME=
DB_HOST=localhost
DB_PORT=3306
DB_USER=****
DB_PASSWORD=****
```

**NOTE** `DB_NAME` should not be set until running the next step.

**4 -** Create database and populate it using the following command:

```sh
$ composer db:init:all
```

It runs the schema and tables creation queries, create insertion procedures and populate the database with mock products.

**5 -** Now set the `DB_NAME` on `.env` file as following:

```sh
DB_NAME=product_inventory_db
```

**6 -** Now the application should be ready to serve.

To run the application in a local environment from PHP default server, run the command:

```sh
$ composer serve
```

To run the application with Apache2 server, create the `.htaccess` file in the root folder with the script:

```sh
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-f

RewriteRule (.*) ./public/index.php/$1 [QSA,L]
```

Also could run commands to list and insert products into database by the commands:

```sh
$ composer db:get
$ composer db:make
```

## Development Details

- The MVC structure applied on this project was taken from my undergoing project [MVC framework (See)](https://github.com/Jadersonrilidio/mvc-framework). Most of features were altered for simplicity sake.

- Required Composer dependencies:

```json
{
    "require": {
        "vlucas/phpdotenv ^5.5": "for environment variables secure loading",
        "php-di/php-di 6.0.2": "for dependency injection through container"
    },
    "require-dev": {
        "squizlabs/php_codesniffer ^3.7": "PSR12 code checking",
        "phan/phan ^5.4": "Code review helper"
    },
}
```

## Improvement proposal 1

In order to fix the SOLID Open-Closed principle violation, the `MysqlProductRepository::all()` method could be implemented this manner:

```php
// Jayrods\ProductInventory\Repository\ProductRepository\MysqlProductRepository::class

public function all(): array
{
    $products = [];

    foreach ($this->getEnumTypes() as $type) {
        $specificRepository = $this->repositoryFactory->create($type, $this->conn);
        array_push($products, $specificRepository->all());
    }

    usort($products, fn ($prev, $next) => strcasecmp($prev->sku(), $next->sku()));

    return $products;
}
```

Provided all ProductType repositories have the `all()` method implemented.
