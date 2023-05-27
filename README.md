# Product Inventory API in PHP

Descriptions goes here ...

## About

The frontend part is here... link
The backend was an API and a webapp also created (but not recommended)

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



## Stack

PHP 7.4
MySQL 5.7
Composer dependencies
    PHPDotEnv
    PHP-DI

## Author notes

## How it works is a nut-shell

Classic MVC model (Request, Router, Response classes)
Router object receive a Request instance and handle the called route by the Request's HttpMethod and URI properties;
According to Request properties the Router search for the route in the map;
Get the route parameters from route map [Controller, Method, Middlewares];
Execute the Middleware queue appending the route Middlewares to the default middlewares;
Instantiate the Controller class using a Dependency Injection Container;
Call the Controller method,injecting the Request instance into it;
The controller method returns a Response or JsonResponse


## Adding a new product type

## Quick Start (Use-Guide in a nut-shell)

Fisrt at all it is needed to create a Controller class under the path/namespace "Jayrods\ScandiwebTest\Http\Controller".

create one public method for each end-point you gonna use;

Go to the 'config/config.php' file and add the routes mapping using the structure:
    'RouteHttpMethod|RouteURI' => [Full_Namespace_Controller::Class, controller_method, array_of_middlewares_to_be_used];

Example:
    'GET|\home' => [Jayrods\\ScandiwebTest\Http\Controller\HomeController::class, 'homePage', ['session-middleware', 'auth-middleware']]
If needed, create the related Entities to be called by the controller

## Development Deploy


## Development Details

- As a matter of reference, the SKU value taken from [this reference](https://squareup.com/us/en/the-bottom-line/operating-your-business/stock-keeping-unit#:~:text=SKU%20stands%20for%20%E2%80%9Cstock%20keeping,has%20a%20unique%20SKU%20number.), as they afirm: "SKU stands for stock keeping unit, usually an eight alphanumeric digit used by retailers to keep track of their stock levels".

- The MVC structure applied on this project was taken from my undergoing project [MVC framework (See)](https://github.com/Jadersonrilidio/mvc-framework), and most of features were altered for simplicity sake of this test.

- The PHP syntax used to develop is in aquaintance with the version 7.4 (although I wouldn't mind to redo in the newest PHP 8.2 version);

- Use of library/package PHPDotEnv

- Use of library/package PHP-DI

- 