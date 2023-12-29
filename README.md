# Laravel Boiler Template

## Overview
Laravel Boiler Template is a comprehensive starting point for Laravel applications, providing a pre-configured structure, authentication system, and common functionalities to jumpstart your projects.

## Requirements
- php ^8.1

## suggestion
- Try to use a new Laravel project, but you may use an existing project.

## Features
- **Repository Pattern:** The Repository Pattern is a design pattern commonly used in Laravel and other frameworks to abstract the data access layer from the rest of the application. It provides a clean and organized way to interact with databases and other data sources by encapsulating the logic for retrieving and storing data.

- **Authentication System:** Integrated Laravel authentication system with login, registration, and password reset functionalities **Web/API**
- **API Ready:** Easily integrate and build APIs with the provided structure.

## How To Install
You can install the package via Composer:

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
  Now you can create a single model or multiple models at once with migration/migrations. when you will run this command on the terminal, The system ask you, are you want to create repository with the model.

- A new command has been registered.
  ```
  php artisan auth:generate
  ```
  You can create the authentication system in your application with the newly registered command. When you run this command on the terminal. The system will ask you, Will you create an authentication for the web or API? After that, If you say web, It will generate web authentication. If you say API, It will ask you to know. Do you create authentication with Passport or Sanctum then the system will be created as per your wish.
  
  You can also run the command this way if you want
  ```
  php artisan auth:generate web
  or
  php artisan auth:generate api --type=passport/sanctum
  ```
  
  
