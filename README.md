# Sourcetoad Logger

This package allows you to log important events, including what data is viewed or updated by whom. Events include:

 * Login Events
 * Logout Events
 * Locked Events
 * Failed login attempt
 * Password Change
 * Attribute changes (except `created_at`/`updated_at`) for models with `Trackable` trait.
 * Retrieval Events (Detecting what models were loaded during a request, of those with `Trackable` trait)
 * Viewed Keys (Extracting keys in API requests to identify what could be viewed)
 
This helps answer questions such as:

 * Who accessed what information of this account and when?
 * When did this person change their password?
 * Who changed the information for this user?
 * What IP was responsible for these changes?
 
This plugin is additionally very slim, which makes installation more difficult than a regular plugin. This is because it is expected for perhaps legal reasons, that these logs may not be allowed to be removed for years. 
This means that the method for storage must be efficient, unlike other packages which may specialize in auditing/logging, but bloat the database with information.

### Laravel

You are reading the documentation for 11.x.

* If you're using Laravel 9 or 10 please see the docs for [4.x](https://github.com/sourcetoad/Logger/releases/tag/v4.2.0).
* If you're using Laravel 6, 7 or 8 please see the docs for [3.x](https://github.com/sourcetoad/Logger/releases/tag/v3.0.1).
* If you're using Laravel 5 or below please see docs for [1.x](https://github.com/sourcetoad/Logger/releases/tag/v1.3.0)

The currently supported database(s): `mysql`, `pgsql`

You can install the package via composer:

``` bash
composer require sourcetoad/logger
```

The package will use Auto Discovery feature of Laravel 5.5+ to register the service provider.

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
// In config/logger.php...
 
return [

    'morphs' => [
        0 => App\Models\User::class
    ],

];
```

This points our `App\Models\User::class` to an enum (integer). This means our database is created with small integers vs large fully qualified namespaces.

Recommended action is creating an enum class to describe all models in your system. If an integer mapping is not detected. The system will error out with an `\InvalidArgumentException`.

This enforces the user to create shorthand notation for all models to cut down on database size. If a numeric morph is not found, the system will fail out. Due to issues with blindly overwriting and applying these morphs globally, they are manually applied. This means that morphs in your application are left untouched.

#### Trackable Trait
For models that may contain information that you wish to be notified was accessed or mutated. You may add the `Trackable` trait and contract to these models:

```php
use Illuminate\Database\Eloquent\Model;
use Sourcetoad\Logger\Contracts\Trackable as TrackableContract;
use Sourcetoad\Logger\Traits\Trackable;
  
class TrackedModel extends Model implements TrackableContract {
    use Trackable;
    
    // Tracked models must implement this function
    public function trackableOwnerResolver(): ?Model
    {
        //
    }
}
```

The issue with this, is a record without an owner association is frankly useless. However, you may access a record that has no foreign key or relation to its owner model. Tracking that makes auditing quite useless.

For this reason, we've developed the notion of custom resolvers. They must be implemented for each Entity tracked, which (if tracked) must be in the `morphs` table in the above settings.

#### HasLoggerRelationships Trait

For models with the `Trackable` trait, you may add the `HasLoggerRelationships` trait to them to query retrieval and mutation audit records:

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Sourcetoad\Logger\Models\AuditChange;
use Sourcetoad\Logger\Models\AuditModel;
use Sourcetoad\Logger\Models\Relations\LoggerMorphMany;
use Sourcetoad\Logger\Traits\Trackable;
use Sourcetoad\Logger\Traits\HasLoggerRelationships;

/**
 * @property-read Collection<int, AuditChange> $auditChanges
 * @property-read Collection<int, AuditModel> $auditModels
 */
class TrackedModel extends Model {
    use Trackable;
    use HasLoggerRelationships;
    
    /**
     * Collection of mutation logs of this model
     *
     * @return LoggerMorphMany<AuditChange>
     */
    public function auditChanges(): LoggerMorphMany
    {
        return $this->loggerMorphMany(AuditChange::class, 'entity');
    }
    
    /**
     * Collection of accesses of this model
     * 
     * @return LoggerMorphMany<AuditModel>
     */
    public function auditModels(): LoggerMorphMany
    {
        return $this->loggerMorphMany(AuditModel::class, 'entity');
    }
}
```

#### Custom Resolvers

#### Cron
These custom resolvers don't run as items are entered, due to the load increase. This should arguably be queued, but easier on implementations for a command (cron) system. Use the Laravel Scheduler to execute our command.

```php
Schedule::command('logger:audit-resolver')
    ->hourly()
    ->withoutOverlapping();
```

This will run taking 200 items of both changes and retrieved models. It will identify the owner associated with the model through the `Trackable` contract. The functions for each individual model should be easy.

```php
public function trackableOwnerResolver(): Owner
{
    return $this->object->relation->owner;
}
```

As you can see, we have to traverse whatever relation/property we need in order to relate the model at hand to an owner. If there is no match, you probably shouldn't be logging it.
