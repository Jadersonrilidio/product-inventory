# Product Inventory Backend

Product Inventory: Store, list and delete products from inventory.

## About

Webapp URL: [https://jay-product-inventory.vercel.app/](https://jay-product-inventory.vercel.app)

You can access the frontend part on github [here](https://github.com/Jadersonrilidio/product-inventory-frontend)

The Product Inventory API could be accessed directly through the URL: [https://jay-product-inventory.000webhostapp.com/api/products](https://jay-product-inventory.000webhostapp.com/api/products)

Database [DER Schema here](https://github.com/Jadersonrilidio/product-inventory-backend/blob/master/resources/img/porduct_inventory_DER.png).

There is also available an alternative webapp built entirely from the backend. It could be seen [here](https://jay-product-inventory.000webhostapp.com) Although it is recommended for evaluation purposes (due to "messy" within frontend files, poor view rendering template engine and lack of testing). Please use the main webapp in Vue **[here!](https://jay-product-inventory.vercel.app)**.

## Stack

PHP 7.4 and MySQL 5.7


## How it works is a nutshell

Classic MVC model (Request, Router, Response classes)
Router object receive a Request instance and handle the called route by the Request's HttpMethod and URI properties;
According to Request properties the Router search for the route in the map;
Get the route parameters from route map [Controller, Method, Middlewares];
Execute the Middleware queue appending the route Middlewares to the default middlewares;
Instantiate the Controller class using a Dependency Injection Container;
Call the Controller method,injecting the Request instance into it;
The controller method returns a Response or JsonResponse


## Adding a new product type on backend

- Create a new product-type Entity on the path `src/Entity` which extends the `Jayrods\ProductInventory\Entity\Product::class`;

- Create a `ProductTypeRepository` folder on the path `src/Repository`, the interface `ProductTypeRepository::class` and class `MysqlProductTypeRepository::class` whithin it, following the models of the other product-derived repositories;

- Create a `ProductTypeValidator::class` for specific attributes validation, again following the models of the other product-derived validators;

- and your new product type in the backend is ready to go! just go for testing it.

PS: Remember to add the product Enum type and the product-type on the database as well, following the provided SQL bellow, properly replacing the placeholders:

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


## Author notes

## Clonning and setting on local environment


The database code could be initialized using composer commands:

```sh
$ composer db:init
$ composer db:init:procedure
$ composer db:init:populate
$ composer db:init:all
```

Also could run commands to inspect and insert products into database by the command line:

```sh
$ composer db:get
$ composer db:make
```

The application could be served with the composer command:

```sh
$ composer serve
```

## Development Details

- The MVC structure applied on this project was taken from my undergoing project [MVC framework (See)](https://github.com/Jadersonrilidio/mvc-framework), and most of features were altered for simplicity sake.

Composer Dependencies:
- PHPDotEnv for environment variables secure loading
- PHP-DI for dependency injection through container