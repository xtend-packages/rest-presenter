<picture class="filament-hidden">
  <source media="(prefers-color-scheme: dark)" srcset="https://www.codelabx.ltd/assets/images/xtend-packages/rest-presenter/rest-presenter-banner-dark.png">
  <img alt="XtendLaravel" src="https://www.codelabx.ltd/assets/images/xtend-packages/rest-presenter/rest-presenter-banner-light.png">
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

RESTPresenter simplifies Laravel API development by providing a lightweight package that seamlessly integrates with Laravel API Resources and Spatie's powerful Data objects. Now, you can effortlessly generate your API collection for any Filament project.

### Key Features

- Effortless REST API creation with Laravel API Resources.
- Simplified data transformation using our Presenter layer.
- Built-in example filters and presenters for rapid API development.
- Testing support to ensure reliability and stability.
- Automating API creation from resources in Filament.
- TypeScript auto-generation for API resource DTOs.
- Postman and Insomnia collection generation for easy API testing.
- Secure your API collection with a security API key.
- Simple authentication middleware for your API resources.

### Planned Features
- Release of Sponsorware API Kits for comprehensive API integration.
- Customisable kits providing a solid foundation for API development.
- Future open-sourcing of full kits upon reaching sponsorship milestones.
- Widget API generation for easy integration with your frontend.
- Social Kit for login and registration with social media platforms.

Check out our [Roadmap](https://github.com/orgs/xtend-packages/projects/1/views/1) for upcoming features and improvements. Feel free to open an issue for suggestions or feature requests. Join us on Discord to start a discussion and stay updated on the latest news.

### What Makes This Package Unique?
RESTPresenter is more than just a CRUD generator. It offers:
- A Presenter layer for easy data transformation without modifying API resources.
- Compliance with standard features like OpenAPI, RESTful CRUD, filtering/pagination.
- Better business logic and direct access to required data for requests.
- Everything is extendable and customisable to fit your project's needs.
- API Kits to jumpstart your development with pre-built features and resources.

### So What Are Presenters?
Presenters are simply a way to transform data before it's sent to the client. They allow you to modify the data in any way you want, without modifying the API resources. This is especially useful when you need to transform data in a specific way for a particular endpoint before sending it to the client.

To use a presenter, simply add the header property `X-REST-PRESENTER: PresenterName` to your request. RESTPresenter will automatically apply the presenter to the data before sending it to the client.
Presenters can be used with collections and single resources. They can also be used with nested resources, allowing you to transform data at any level of the response.

## Installation & Requirements

- PHP ^8.2
- Laravel 10+

Install the package via composer:

```bash
composer require xtend-packages/rest-presenter:^1.0.0
```

> **Note:** If you encounter an error during installation, you may need to update your `minimum-stability` requirement to `dev` in your `composer.json` file:

```json
"minimum-stability": "dev",
```

## Filament Plugin Integration

Manage your RESTPresenter resources directly from Filament with our dedicated plugin. This integration allows you to generate user tokens, manage your API resources, and more, all from within the Filament interface.

### Installation

To install the RESTPresenter Filament plugin, run the following command:

```bash
php artisan rest-presenter:filament --install
```

This command will prompt you to auto-commit the changes to your Git repository. If you choose not to commit, you can manually commit the changes yourself.

### Export API Collection

To generate your API collection, run the following command:

```bash
php artisan rest-presenter:generate-api-collection
```

By default, this command generates a Postman collection. If you prefer Insomnia, you can switch by setting the following in your `.env` file:

```bash
REST_PRESENTER_EXPORT_PROVIDER=insomnia
```

### Uninstall

To uninstall the RESTPresenter Filament plugin, run the following command:

```bash
php artisan rest-presenter:filament --uninstall
```

This command will prompt you to auto-commit and revert changes to your Git repository. If you choose not to commit, you can manually commit the changes yourself.


### RESTPresenter Panel
The new RESTPresenter panel serves as a centralized dashboard, offering a comprehensive overview and management interface for your API collection.

We have included the following features:
- **API Endpoints:** View all your API endpoints in one place especially useful when you update a resource to have authentication.


- **Token Generation:** Generate user tokens for secure API access you can define the token name, abilities, and expiration datetime. Simply copy the generated token to your API client to use with any authenticated endpoints.


- **Users Resource:** Detail view about users tokens from here you can revoke tokens.

> **Coming Soon:** Test Coverage and Reports just one of many features in active development.  

### Endpoint Authentication & Security
By default, all resources do not include middleware for authentication. However your resources are protected by a security API key.\
You can make any resource public by simply updated the isAuthenticatable property in the resource file. Here's an example setting customer resource to public:

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

- `app/Api/StarterKits/Auth/Sanctum` (allows you to override the default sanctum actions)
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


## Standalone Laravel Setup

### Initial Setup
Customize RESTPresenter for your project with our setup command:

```bash
php artisan rest-presenter:setup
```
Select the Starter Kits to use during setup to configure the package according to your needs. The command also publishes the configuration file rest-presenter.php to your config directory and automatically registers the RESTPresenterServiceProvider.

### Generate Resources (Prompts)
To generate a new resource, use the following command:

```bash
php artisan rest-presenter:make-resource
```
This command will guide you through creating a new resource. Prompts will allow you to automatically generate presenters, filters, data, and set up your resource ready to use. All model relationships and fields are automatically detected throughout the prompt process. Additionally, we provide a custom option for most prompts to generate without auto-detection.

### Configuration

Configure RESTPresenter in the `config/rest-presenter.php` file to customize the package according to your project requirements.

### Testing

RESTPresenter includes a comprehensive testing suite to ensure your API's reliability and stability. Test generators will soon be added to facilitate testing when creating new resources.

## Contributing

See [CONTRIBUTING](CONTRIBUTING) for details on how to contribute to RESTPresenter.

## License

RESTPresenter is open-source software licensed under the [MIT License](LICENSE)
