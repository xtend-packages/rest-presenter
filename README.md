<picture class="filament-hidden">
  <source media="(prefers-color-scheme: dark)" srcset="https://www.codelabx.ltd/assets/images/xtend-packages/rest-presenter/rest-presenter-banner-dark.png">
  <img alt="RESTPresenter" src="https://www.codelabx.ltd/assets/images/xtend-packages/rest-presenter/rest-presenter-banner-light.png">
</picture>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/xtend-packages/rest-presenter.svg?style=flat-square)](https://packagist.org/packages/xtend-packages/rest-presenter)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/xtend-packages/rest-presenter)](https://packagist.org/packages/xtend-packages/rest-presenter)
[![GitHub License](https://img.shields.io/github/license/xtend-packages/rest-presenter)](https://github.com/xtend-packages/rest-presenter/blob/main/LICENSE.md)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/xtend-packages/rest-presenter/tests.yml?label=tests)](https://github.com/xtend-packages/rest-presenter/actions/workflows/tests.yml)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/xtend-packages/rest-presenter/code-styling.yml?label=code%20style)](https://github.com/xtend-packages/rest-presenter/actions/workflows/code-styling.yml)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/xtend-packages/rest-presenter/phpstan.yml?label=static%20analysis)](https://github.com/xtend-packages/rest-presenter/actions/workflows/phpstan.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/xtend-packages/rest-presenter.svg?style=flat-square)](https://packagist.org/packages/xtend-packages/rest-presenter)
<a href="https://discord.gg/j7EAhVVcyX" class="filament-hidden">
  <img src="https://img.shields.io/discord/1033138523584331837?label=Discord&logo=discord" alt="Join our Discord">
</a>

## Introduction

RESTPresenter is a powerful, lightweight package designed to streamline Laravel API development. It integrates seamlessly with Laravel API Resources and Spatie's Data objects, making API creation and management effortless. 

With RESTPresenter, you can:

- **Quick API Generation:** Rapidly create comprehensive REST APIs with robust endpoint management and resource handling.
- **Seamless Integration:** Utilize Laravel's API Resources and Spatie's Data objects for streamlined data transformation. Automatically generate TypeScript definitions for ready-to-use, type-safe data structures in front-end frameworks.
- **Filament Plugin Support:** Manage API resources, user tokens, and more through an intuitive Filament plugin.
- **Automated Collections:** Generate Postman and Insomnia collections for easy API testing and documentation.
- **Security and Scalability:** Implement token-based authentication and configure endpoint security settings effortlessly.
- **Comprehensive Testing:** Automatically generate tests for API resources, ensuring data consistency and integrity.
- **Rapid Development Kits:** Use example filters, presenters, and tools for fast API development with extendable resources.

RESTPresenter enhances your Laravel projects with powerful tools for efficient and secure API development.

## Installation

To get started with RESTPresenter, you need to meet the following requirements:
- PHP ^8.2
- Laravel 10+

Install the package via composer:

```bash
composer require xtend-packages/rest-presenter
```

## Filament Plugin Integration

Manage your RESTPresenter resources directly from Filament with our dedicated plugin. This integration allows you to generate user tokens, manage your API resources, and more.

### Plugin Installation

To install the RESTPresenter Filament plugin, run:

```bash
php artisan rest-presenter:filament --install
```

This command will prompt you to auto-commit changes to your Git repository. If you choose not to commit, you can manually commit the changes yourself. The Sanctum StarterKit is automatically installed during this process. For more details, see [Sanctum StarterKit](#sanctum-starter-kit).

### Export API Collection

To generate your API collection, run:

```bash
php artisan rest-presenter:generate-api-collection
```

By default, this command generates a Postman collection. If you prefer Insomnia, switch by setting the following in your `.env` file:

```bash
REST_PRESENTER_EXPORT_PROVIDER=insomnia
```

For a full list of configuration options, see [Configuration](#configuration).

### Uninstall

To uninstall the RESTPresenter Filament plugin, run:

```bash
php artisan rest-presenter:filament --uninstall
```

This command will prompt you to auto-commit and revert changes to your Git repository. If you choose not to commit, you can manually commit the changes yourself.

### RESTPresenter Panel

The new RESTPresenter panel serves as a dashboard, offering a comprehensive overview and management interface for your API collection.

<picture>
  <source media="(prefers-color-scheme: dark)" srcset="https://www.codelabx.ltd/assets/images/xtend-packages/rest-presenter/rest-presenter-panel-dark.png">
  <img alt="RESTPresenter" src="https://www.codelabx.ltd/assets/images/xtend-packages/rest-presenter/rest-presenter-panel-light.png">
</picture>

You can access the RESTPresenter panel by navigating to `/rest-presenter` in your browser. the path is configurable in the `.env` file see [Configuration](#configuration) for more details.

RESTPresenter panel link has now conveniently been added to the user menu for all your filament panels.

Features include:
- **API Endpoints:** View all your API endpoints in one place, especially useful when you update a resource to require authentication.
- **Token Generation:** Generate user tokens for secure API access. You can define the token name, abilities, and expiration datetime. Simply copy the generated token to your API client to use with any authenticated endpoints.
- **Users Resource:** Detailed view of user tokens. From here, you can revoke tokens.

> **Coming Soon:** Test Coverage and Reports, just one of many features in active development.

### Endpoint Authentication & Security

By default, all endpoints are publicly available without Sanctum middleware, protected by a security API key which you can update via `REST_PRESENTER_AUTH_API_KEY` in your `.env` file.

You can make any endpoint authenticated by updating the `isAuthenticated` property in the resource controller. This will automatically add the Sanctum middleware to the endpoint.

```php
<?php

class CustomerResourceController extends ResourceController
{
    protected static string $model = Customer::class;

    public static bool $isAuthenticated = true;
    
    // ... rest of the resource controller
}
```

### Generated Files

The following directories with generated files will be created:

- `app/Api/StarterKits/Sanctum` (allows you to override the default Sanctum actions)
- `app/Api/StarterKits/Filament` (contains all auto-generated resources for Filament)
- `resources/rest-presenter/postman` (generated Postman collection)
- `resources/rest-presenter/insomnia` (generated Insomnia collection)
- `resources/rest-presenter/types/` (generated TypeScript DTOs)
- `tests/StarterKits` (generated tests)

### Filament Test Suite

Tests are generated for each resource in the Filament test suite.

> **_Warning:_** To prevent overriding your database, update `phpunit.xml` with the following:
> ```xml
> <env name="DB_CONNECTION" value="sqlite"/>
> <env name="DB_DATABASE" value=":memory:"/>
> ```

- **Initial Test Expectations:** We anticipate that your initial tests will fail. This is a standard part of the testing process and is one of the reasons we provide a comprehensive test suite.

- **Identifying Issues:** The test suite aids in identifying any missing relationships or properties in your factories. It serves as a diagnostic tool to highlight areas that may need adjustment or improvement.

- **Field Matching and Types:** One specific aspect of the tests is to verify that fields match and return the expected data type. For instance, if a field is supposed to contain an integer but is instead a string, the test will fail. This aspect helps ensure data consistency and integrity.

- **Adjustments:** You're encouraged to make adjustments as needed based on the test results. This could involve modifying either the data object or the factory to align with the expected types and specifications.

## RESTPresenter Package

### Key Features

- **Effortless REST API Creation:** Build REST APIs with ease using Laravel API Resources.
- **Simplified Data Transformation:** Utilize our Presenter layer for streamlined data transformation.
- **Built-in Filters and Presenters:** Accelerate API development with pre-built example filters and presenters.
- **Comprehensive Testing Support:** Ensure your APIs are reliable and stable with robust testing features.
- **Automated API Creation:** Seamlessly generate APIs from resources in Filament.
- **TypeScript Auto-Generation:** Automatically create TypeScript DTOs for your API resources.
- **API Collection Generation:** Easily test your APIs with Postman and Insomnia collection generation.
- **Secure API Access:** Protect your API with a configurable security API key.
- **Simple Authentication Middleware:** Add authentication to your API resources effortlessly.

### Planned Features

- **Sponsorware API Kits:** Comprehensive API integration kits available for sponsors.
- **Customizable Kits:** Provide a solid foundation for your API development needs.
- **Open-Source Milestones:** Full kits will be open-sourced upon reaching sponsorship goals.
- **Widget API Generation:** Simplify frontend integration with widget API generation.
- **Social Kit:** Facilitate login and registration using social media platforms.

Check out our [Roadmap](https://github.com/orgs/xtend-packages/projects/1/views/1) for upcoming features and improvements. Feel free to open an issue for suggestions or feature requests. Join us on Discord to start a discussion and stay updated on the latest news.

### What Makes This Package Unique?

RESTPresenter is more than just a CRUD generator. It offers:

- **Presenter Layer:** Easily transform data without altering API resources.
- **Standards Compliance:** Adheres to OpenAPI, RESTful CRUD, and filtering/pagination standards.
- **Enhanced Business Logic:** Directly access required data for requests, improving business logic.
- **Extendable and Customizable:** Tailor every aspect to fit your project's unique needs.
- **API Kits:** Jumpstart development with pre-built features and resources.

### So What Are Presenters?

Presenters allow you to transform data before it's sent to the client, enabling modifications without altering API resources. This is particularly useful for transforming data for specific endpoints.

To use a presenter, add the header property `X-REST-PRESENTER: PresenterName` to your request. RESTPresenter will automatically apply the presenter to the data before sending it to the client. Presenters work with collections, single resources, and nested resources, allowing for data transformation at any response level.

### Filament Starter Kit

The RESTPresenter package includes a Filament Starter Kit, providing a robust foundation for your Filament projects:

- **API Resources:** Auto-generated API resources for your Filament project.
- **Data Objects:** Utilize Spatie's Data objects for straightforward data transformation.
- **Presenters:** Example presenters for your API resources.
- **Comprehensive Tests:** Ensure reliability with a full test suite for your API resources.
- **TypeScript DTOs:** Automatically generate TypeScript DTOs for your API resources.

#### Need Something More Advanced?

We are developing a full Filament Kit with additional features for comprehensive CRUD generation in your Filament project. This advanced solution is intended for those who require more robust functionality and will be released under a sponsorship model. Once we reach our sponsorship milestones, the full Filament Kit will be open-sourced.

The full kit will save developers time by providing advanced solutions out of the box. However, the Starter Kit still allows for full CRUD implementation, though it may require more time and effort. If you’re interested in the advanced Filament Kit, reach out to us on Discord to express your interest and support its development through sponsorship.



## Standalone Laravel Setup

RESTPresenter seamlessly integrates into any Laravel application, allowing you to generate API resources effortlessly from your selected models.

### Initial Setup
Customize RESTPresenter for your project with our setup command:

```bash
php artisan rest-presenter:setup
```
We recommend installing the Sanctum starter kit, so this has been pre-selected for you. Note Filament now has a dedicated command to install the RESTPresenter plugin so has been removed from the setup command.

### Generate Resources (Prompts)
To generate a new resource, use the following command:

```bash
php artisan rest-presenter:make-resource
```
This command will guide you through creating a new resource. Prompts will allow you to automatically generate presenters, filters, data, and set up your resource ready to use. All model relationships and fields are automatically detected throughout the prompt process. Additionally, we provide a custom option for most prompts to generate without auto-detection.

### Configuration

We no longer publish the configuration by default. This is to provide better support for future updates and to prevent conflicts with your existing configuration. If you need to publish the configuration, you can do so with `vendor:publish` however we do not recommend this approach.

Instead we have made sure that you can override any configuration directly in your `.env` file. This allows you to customize the package to your needs without the need to publish the configuration.

Here is a list of all available configuration options including their default values:

```bash
# RESTPresenter Generator Configuration
REST_PRESENTER_GENERATOR_PATH=app/Api
REST_PRESENTER_GENERATOR_NAMESPACE=App\Api
REST_PRESENTER_GENERATOR_TS_TYPES_PATH=rest-presenter/types
REST_PRESENTER_GENERATOR_TS_TYPES_KEYWORD=interface
REST_PRESENTER_GENERATOR_TS_TYPES_TRAILING_SEMICOLON=true
REST_PRESENTER_GENERATOR_TEST_PATH=tests/Feature/Api/v1
REST_PRESENTER_GENERATOR_TEST_NAMESPACE=Tests\Feature\Api\v1

# RESTPresenter API Configuration
REST_PRESENTER_API_PREFIX=api
REST_PRESENTER_API_VERSION=v1
REST_PRESENTER_API_NAME=API
REST_PRESENTER_API_DEBUG=true
REST_PRESENTER_API_PRESENTER_HEADER=X-REST-PRESENTER

# RESTPresenter Auth Configuration
REST_PRESENTER_AUTH_API_KEY=rest-presenter-secret-key
REST_PRESENTER_AUTH_API_TOKEN_NAME=rest-presenter-api-token
REST_PRESENTER_AUTH_API_KEY_HEADER=X-REST-PRESENTER-API-KEY
REST_PRESENTER_AUTH_ENABLE_API_KEY=true
REST_PRESENTER_AUTH_REGISTER_DATA_NAME="required|string|max:255"
REST_PRESENTER_AUTH_REGISTER_DATA_EMAIL="required|string|email|max:255|unique:users,email"
REST_PRESENTER_AUTH_REGISTER_DATA_PASSWORD="required|string|min:8|max:255|confirmed"
REST_PRESENTER_AUTH_LOGIN_DATA_EMAIL="required|string|email|max:255"
REST_PRESENTER_AUTH_LOGIN_DATA_PASSWORD="required|string|min:8"
REST_PRESENTER_AUTH_LOGOUT_REVOKE_ALL_TOKENS=false
REST_PRESENTER_AUTH_RATE_LIMIT_MAX_ATTEMPTS=5

# RESTPresenter Export Configuration
REST_PRESENTER_EXPORT_PROVIDER=postman

# RESTPresenter Export Insomnia Configuration
REST_PRESENTER_EXPORT_INSOMNIA_WORKSPACE_NAME="${APP_NAME} (RESTPresenter)"
REST_PRESENTER_EXPORT_INSOMNIA_WORKSPACE_DESCRIPTION="${APP_NAME} RESTPresenter Workspace"
REST_PRESENTER_EXPORT_INSOMNIA_ENVIRONMENT_NAME="${APP_NAME} (RESTPresenter)"
REST_PRESENTER_EXPORT_INSOMNIA_ENVIRONMENT_BASE_URL="${APP_URL}"
REST_PRESENTER_EXPORT_INSOMNIA_ENVIRONMENT_VERSION=v1

# RESTPresenter Export Postman Configuration
REST_PRESENTER_EXPORT_POSTMAN_INFO_NAME="${APP_NAME} (RESTPresenter)"
REST_PRESENTER_EXPORT_POSTMAN_INFO_SCHEMA="https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
REST_PRESENTER_EXPORT_POSTMAN_AUTH_METHOD=bearer
REST_PRESENTER_EXPORT_POSTMAN_AUTH_TOKEN=YOUR_API_TOKEN

# RESTPresenter Resource Configuration
REST_PRESENTER_RESOURCES_USER_PROFILE=\XtendPackages\RESTPresenter\Resources\Users\Presenters\Profile
REST_PRESENTER_RESOURCES_USER_USER=\XtendPackages\RESTPresenter\Resources\Users\Presenters\User

# RESTPresenter Panel Configuration
REST_PRESENTER_PANEL_BRAND_NAME=RESTPresenter
REST_PRESENTER_PANEL_BRAND_LOGO=true
REST_PRESENTER_PANEL_PATH=rest-presenter
REST_PRESENTER_PANEL_MAX_WIDTH=7xl
REST_PRESENTER_PANEL_TOP_NAVIGATION=false
```
## Changelog

Please see [CHANGELOG](CHANGELOG.md) this is automatically generated from commits which follow the [conventional commits](https://www.conventionalcommits.org/en/v1.0.0/#summary) standards and updated with each release.

## Contributing

Please see our [CONTRIBUTING](CONTRIBUTING.md) guide if you are thinking of contributing to this package.

## License

RESTPresenter is open-source software licensed under the [MIT License](LICENSE.md)
