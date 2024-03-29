<picture>
  <source media="(prefers-color-scheme: dark)" srcset="https://www.codelabx.ltd/assets/images/xtend-packages/rest-presenter/rest-presenter-banner-dark.png">
  <img alt="XtendLaravel" src="https://www.codelabx.ltd/assets/images/xtend-packages/rest-presenter/rest-presenter-banner-light.png">
</picture>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/xtend-packages/rest-presenter.svg?style=flat-square)](https://packagist.org/packages/xtend-packages/rest-presenter)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/xtend-packages/rest-presenter)](https://packagist.org/packages/xtend-packages/rest-presenter)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/xtend-packages/rest-presenter/tests.yml?label=tests)](https://github.com/xtend-packages/rest-presenter/actions/workflows/tests.yml)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/xtend-packages/rest-presenter/code-styling.yml?label=code%20style)](https://github.com/xtend-packages/rest-presenter/actions/workflows/code-styling.yml)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/xtend-packages/rest-presenter/phpstan.yml?label=static%20analysis)](https://github.com/xtend-packages/rest-presenter/actions/workflows/phpstan.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/xtend-packages/rest-presenter.svg?style=flat-square)](https://packagist.org/packages/xtend-packages/rest-presenter)
[![GitHub Sponsors](https://img.shields.io/github/sponsors/adam-code-labx)](https://github.com/sponsors/adam-code-labx)
[![GitHub License](https://img.shields.io/github/license/xtend-packages/rest-presenter)](https://github.com/xtend-packages/rest-presenter/blob/main/LICENSE.md)

# RESTPresenter

RESTPresenter simplifies Laravel API development by providing a lightweight package that seamlessly integrates with Laravel API Resources and Spatie's powerful Data objects.

## Starter Kits
Our initial release includes the following Starter Kits to jumpstart your development:
- [Breeze Auth Starter Kit](https://github.com/xtend-packages/rest-presenter/issues/1)
- [Lunar API Starter Kit](https://github.com/xtend-packages/rest-presenter/issues/2) 

## Key Features

- Effortless REST API creation with Laravel API Resources.
- Simplified data transformation using our Presenter layer.
- Built-in example filters and presenters for rapid API development.
- Testing support to ensure reliability and stability.

## Planned Features
- Automating API creation from resources in packages like Filament.
- Release of Sponsorware API Kits for comprehensive API integration.
- Customisable kits providing a solid foundation for API development.
- Future open-sourcing of full kits upon reaching sponsorship milestones.

more to follow soon...

## What Makes This Package Unique?
RESTPresenter is more than just a CRUD generator. It offers:
- A Presenter layer for easy data transformation without modifying API resources.
- Compliance with standard features like OpenAPI, RESTful CRUD, filtering/pagination.
- Better business logic and direct access to required data for requests.
- Everything is extendable and customisable to fit your project's needs.
- API Kits to jumpstart your development with pre-built features and resources.

## Roadmap
Check out our [Roadmap](https://github.com/orgs/xtend-packages/projects/1/views/1) for upcoming features and improvements. Feel free to open an issue for suggestions or feature requests. We'll soon be launching our Discord for collaborative discussions.

## Requirements

- PHP ^8.2
- Laravel 10+

## Installation

You can install the package via composer:

```bash
composer require xtend-packages/rest-presenter
```

After installation, the package sets up default Breeze auth scaffolding along with the users resource. You can access the register endpoint at api/v1/auth/register using API clients like Insomnia.

## Setup
Customize RESTPresenter for your project with our setup command:

```bash
php artisan rest-presenter:setup
```
Select the Starter Kits to use during setup to configure the package according to your needs. The command also publishes the configuration file rest-presenter.php to your config directory and automatically registers the RESTPresenterServiceProvider.

## Usage

### Registering Kits

To register a kit, add it to the starterKits array in the register method of the RESTPresenterServiceProvider. For example:

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use XtendPackages\RESTPresenter\Facades\RESTPresenter;
use XtendPackages\RESTPresenter\StarterKits\Auth\Breeze\BreezeApiKitServiceProvider;

class RESTPresenterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        RESTPresenter::register($this->app)
            ->starterKits([
                BreezeApiKitServiceProvider::class,
            ]);
    }
}
```

### Configuration

Configure RESTPresenter in the `config/rest-presenter.php` file to customize the package according to your project requirements.

### Testing

RESTPresenter includes a comprehensive testing suite to ensure your API's reliability and stability. Test generators will soon be added to facilitate testing when creating new resources.

## Contributing

See [CONTRIBUTING](CONTRIBUTING) for details on how to contribute to RESTPresenter.

## License

RESTPresenter is open-source software licensed under the [MIT License](LICENSE)
