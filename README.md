# Sourcetoad Logger

This package allows you to log important events, including what data is viewed by who.

### Laravel

This package can be used in Laravel 5.6.

You can install the package via composer:

``` bash
composer require sourcetoad/logger
```

## Setup

#### Morphable Entities
Due to the large amount of records anticipated to be created, you must create an integer mapping to models in your system. We give an example as follows:

```php
    use Sourcetoad\Logger\Enums\ModelMapping;
 
    'morphs' => [
        ModelMapping::USER => 'App\User'
    ];
```

This points our `App\User::class` to an enum (integer). This means our database is created with small integers vs large fully qualified namespaces.

Recommended action is creating an enum class to describe all models in your system. If an integer mapping is not detected. The system will error out with an `/InvalidArgumentException`.

This enforces the user to create