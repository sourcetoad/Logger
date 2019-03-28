# Sourcetoad Logger

This package allows you to log important events, including what data is viewed by who.

### Laravel

This package can be used in Laravel 5.6.

You can install the package via composer:

``` bash
composer require sourcetoad/logger
```

Since we only support Laravel 5.6 right now, the package will use Auto Discovery feature of Laravel 5.5 to register the service provider.

## Configs
You may publish the configuration to make changes via:

```php
    php artisan vendor:publish --tag=logger
```

This will contain model maps, which can be read about below.

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

This enforces the user to create shorthand notation for all models. To cut down on database size. If a numeric morph is not found, the system will fail out. This means existing morphs are effectively broken with this plugin. However, due to internal use we haven't explored a correction for this.

#### Trackable Trait
For models that may contain information that you wish to be notified was access/retrieved. You may add the Trackable trait to these models. The issue with this, is a record without a user association is frankly useless. However, you may access a record that has no foreign key or relation to a user model. Tracking that makes auditing quite useless.

```php
    use Sourcetoad\Logger\Traits\Trackable;
  
    class Model {
        use Trackable;
    }
```

For this reason, we've developed the notion of custom resolvers. They must be implemented for each Entity tracked, which (if tracked) must be in the `morphs` table in the above settings.

#### Custom Resolvers