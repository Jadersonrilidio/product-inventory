# Scandiweb Test Project

Descriptions goes here ...

## Quick Start (Use-Guide in a nut-shell)

Fisrt at all it is needed to create a Controller class under the path/namespace "Jayrods\ScandiwebTest\Http\Controller".

create one public method for each end-point you gonna use;

Go to the 'config/config.php' file and add the routes mapping using the structure:
    'RouteHttpMethod|RouteURI' => [Full_Namespace_Controller::Class, controller_method, array_of_middlewares_to_be_used];

Example:
    'GET|\home' => [Jayrods\\ScandiwebTest\Http\Controller\HomeController::class, 'homePage', ['session-middleware', 'auth-middleware']]
If needed, create the related Entities to be called by the controller


### About

Description and stuff here plz...


## Development Details

- As a matter of reference, the SKU value taken from [this reference](https://squareup.com/us/en/the-bottom-line/operating-your-business/stock-keeping-unit#:~:text=SKU%20stands%20for%20%E2%80%9Cstock%20keeping,has%20a%20unique%20SKU%20number.), as they afirm: "SKU stands for stock keeping unit, usually an eight alphanumeric digit used by retailers to keep track of their stock levels".

- The MVC structure applied on this project was taken from my undergoing project [MVC framework (See)](https://github.com/Jadersonrilidio/mvc-framework), and most of features were altered for simplicity sake of this test.

- The PHP syntax used to develop is in aquaintance with the version 7.4 (although I wouldn't mind to redo in the newest PHP 8.2 version);

- Use of library/package PHPDotEnv

- Use of library/package PHP-DI

- 