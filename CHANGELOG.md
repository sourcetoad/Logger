# v6.1.0 (November 26, 2024)
 * Allow deleted entities and owners to be resolved when running the audit resolver command.
 * Prevent `LazyLoadingViolation` errors when running the audit resolver command.

# v6.0.1 (November 21, 2024)
 * Add error checking and documentation to prevent morph maps from using a `0` key in the logger config.
 * Fix an issue where `audit_changes.owner_type` and `audit_models.owner_type` were not properly set as integers.

# v6.0.0 (November 19, 2024)
 * Drop User model associations on tracked entities in favor of polymorphic "owner" associations.

# v5.1.1 (October 31, 2024)
 * Fix down migration for create_logger_tables.

# v5.1.0 (October 31, 2024)
 * Add support for Postgres.

# v5.0.1 (July 18, 2024)
 * Remove throws on down methods for migrations.

# v5.0.0 (July 16, 2024)
 * Add Laravel 11 support
 * Drop PHP <= 8.1 support

# v4.2.0 (March 2, 2023)
 * Add Laravel 10 support
 * GitHub Action workflow update

# v4.1.0 (January 17, 2023)
 * Upgrade away from deprecated GitHub Action tasks.
 * Upgrade away from deprecated `phpunit.xml` config.
 * Add feature to dedupe audit_keys more accurately.
 * Refactor logic to php8 language features.

# v4.0.0 (August 39, 2022)
 * Drop PHP < 8 support.
 * Add PHP 8.1 support.
 * Add Laravel 9 support
 * Drop Laravel < 9 support.

# v3.0.1 (July 20, 2022)
 * Fix issue where a JSON response does not return parseable json.

# v3.0.0 (November 1, 2021)
 * Drop PHP 7.2 support
 * Add PHP 8.0 support

# v2.1.0 (December 11, 2020)
 * Laravel 8.x support
 * Fix issue with missing indexes on "processed" columns.
 * Update development docker container to php74 / xdebug3

# v2.0.1 (August 6, 2020)
 * Fix issue with multiple database connections using wrong variable in driver field.

# v2.0.0 (April 23, 2020)
 * Laravel 6.x support
 * Laravel 7.x support
 * GitHub Action - Code Style
 * Github Action - Tests

# v1.3.2 (October 23, 2019)
 * Support for race conditions during creation, with locks.

# v1.3.1 (August 20, 2019)
 * Support for instance where unsaved model (null entity_id) is provided

# v1.3.0 (July 25, 2019)
 * Laravel 5.7 support
 * Laravel 5.8 support

# v1.2.1 (July 7, 2019)
 * Revert non-async process.

# v1.2.0 (July 5, 2019)
 * [#5](https://github.com/sourcetoad/Logger/issues/5) - Support for models with no user found.
 * [#7](https://github.com/sourcetoad/Logger/issues/7) - Swap to non-async process for finding user.

# v1.1.1 (July 1, 2019)
 * [#3](https://github.com/sourcetoad/Logger/issues/3) - Translations for enums.
 * Trim route to prevent duplicate entries.

# v1.1.0 (April 19, 2019)
 * [#1](https://github.com/sourcetoad/Logger/issues/1) - Prevent destroying existing morphs.
 * Use proper type for logout event. 
 * Add `ext-json` as required extension.
 
# v1.0.4 (April 3, 2019)
 * Use proper listener for password reset event.

# v1.0.3 (April 3, 2019)
 * Fix bad composer.json version constraint.

# v1.0.2 (April 3, 2019)
 * Fix event for PasswordReset.
 * Add composer dependency on Laravel config().
 
# v1.0.1 (April 3, 2019)
 * Add support for 'Illuminate\Http\Response`
 * Don't die out if unknown response detected.

# v1.0.0 (April 1, 2019)
 * initial release
