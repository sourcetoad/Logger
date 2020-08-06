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
