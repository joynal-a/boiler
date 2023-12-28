# Laravel Boilerplate Template

## Overview
Laravel Boilerplate Template is a comprehensive starting point for Laravel applications, providing a pre-configured structure, authentication system, and common functionalities to jumpstart your projects.

## Features

- **Repository Pattern:** The Repository Pattern is a design pattern commonly used in Laravel and other frameworks to abstract the data access layer from the rest of the application. It provides a clean and organized way to interact with databases and other data sources by encapsulating the logic for retrieving and storing data.

- **Authentication System:** Integrated Laravel authentication system with login, registration, and password reset functionalities **Web/API**
- **API Ready:** Easily integrate and build APIs with the provided structure.

## How To install
You can install the package via composer:

```
composer require joynal.a/boiler
```


## What happened after installing this package?
- The php artisan make:model command has been customized, with a new repository pattern added.
  **How is the command now?**
  ```
  php artisan make:model ExampleModal --m
  ```
  ```
  php artisan make:model ExampleModal1 ExampleModal2 ExampleModal3 --m
  ```
  Now you can create a single model or multiple models at once with migration/migrations. when you will run this command on the terminal, system ask you, are you want to create repository with model?

- Register a new command
  ```
  php artisan auth:generate
  ```
  **About this command**
  
   
  
  
