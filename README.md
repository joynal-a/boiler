# Laravel Boiler Template
<p align="center">
  <img src="https://img.shields.io/github/stars/joynal-a/boiler?style=for-the-badge" alt="Total Issues">
  <img src="https://img.shields.io/github/issues/joynal-a/boiler?style=for-the-badge">
  <img src="https://img.shields.io/github/license/joynal-a/boiler?style=for-the-badge" alt="License">
  <!-- Add more badges with different icons if necessary -->
</p>

## 🚀 Overview
Laravel Boiler Template is an elegant starting point for Laravel applications, meticulously crafted to provide a pre-configured structure, a sophisticated authentication system, and essential functionalities. Propel your projects forward with this carefully designed foundation.

## 🌟 Requirements
- **PHP** ^8.1
- **Laravel** ^10

## ✨ Suggestion
- It is highly recommended to initiate a new Laravel project to fully embrace the power of Laravel Boiler Template. However, integration into an existing project is also possible.

## 🛠️ Features
- **Repository Pattern:** Embrace the elegance of the Repository Pattern, a widely adopted design pattern in Laravel and other frameworks. This pattern abstracts the data access layer, offering a pristine and organized approach to interact with databases and various data sources.

- **Multi Model Creation:** Streamline your workflow with the ability to create multiple models in a single command. The enhanced `php artisan make:model` command empowers you to craft a single model or multiple models at once, complete with migration/migrations. The system graciously prompts you, allowing you to seamlessly choose whether to create a repository alongside each model.

- **Authentication System:** Seamlessly integrated Laravel authentication system with advanced features for web and API, including login, registration, and password reset functionalities.

- **API Ready:** Unleash the potential of easy API integration and development using the meticulously crafted structure provided.

## 📦 How To Install
Install this package effortlessly using Composer:

```bash
composer require joynal.a/boiler
```

## 🚀 What Happens After Installing This Package?
- The `php artisan make:model` command has undergone a luxurious transformation, featuring an added repository pattern.
  - **Command Excellence:**
    ```bash
    php artisan make:model ExampleModel --m
    ```
    ```bash
    php artisan make:model ExampleModel1 ExampleModel2 ExampleModel3 --m
    ```
    Enjoy the ability to create a single model or multiple models at once, complete with migration/migrations. The system gracefully prompts you, inquiring if you wish to create a repository alongside the model.

- Behold, a new command emerges:
  ```bash
  php artisan auth:generate
  ```
  Elevate your application's authentication system with this regal command. When invoked, the terminal becomes a realm of choices, asking whether you desire authentication for the web or API. Opt for web, and witness the creation of a web authentication marvel. Choose API, and the terminal further inquires if you prefer authentication with Passport or Sanctum. Your wishes shape the system accordingly.
  
  Alternatively, embark on this journey with personalized commands:
  ```bash
  php artisan auth:generate web
  ```
  or
  ```bash
  php artisan auth:generate api --type=passport/sanctum
  ```
  
Embark on your Laravel journey with the elegance and sophistication of Laravel Boiler Template! 
🌟 Add stars, fork the project, and engage with the community. 🚀
